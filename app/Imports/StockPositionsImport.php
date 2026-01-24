<?php

namespace App\Imports;

use App\Models\Pallet;
use App\Models\PolishType;
use App\Models\ProductType;
use App\Models\StockPosition;
use App\Models\StoneType;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StockPositionsImport implements ToModel, WithHeadingRow
{
    private $stats = [
        'created' => 0,
        'updated' => 0,
        'errors' => [],
        'created_types' => [
            'product_types' => 0,
            'polish_types' => 0,
            'stone_types' => 0,
            'pallets' => 0,
        ],
    ];

    public function model(array $row)
    {
        try {
            $rowId = $row['id'] ?? null;
            if (empty($rowId)) {
                return null;
            }

            $productTypeName = $row['tip'] ?? null;
            if (empty($productTypeName)) {
                $this->stats['errors'][] = "Строка {$rowId}: Тип продукции не указан";
                return null;
            }

            $productType = ProductType::firstOrCreate(
                ['name' => trim($productTypeName)],
                ['is_active' => true]
            );
            if ($productType->wasRecentlyCreated) {
                $this->stats['created_types']['product_types']++;
            }

            $polishTypeName = $row['vid_polirovki'] ?? null;
            $polishType = null;
            if (!empty($polishTypeName) && $polishTypeName !== '-') {
                $polishType = PolishType::firstOrCreate(
                    ['name' => trim($polishTypeName)],
                    ['is_active' => true]
                );
                if ($polishType->wasRecentlyCreated) {
                    $this->stats['created_types']['polish_types']++;
                }
            }

            $stoneTypeName = $row['vid_kamnia'] ?? null;
            $stoneType = null;
            if (!empty($stoneTypeName) && $stoneTypeName !== '-') {
                $stoneType = StoneType::firstOrCreate(
                    ['name' => trim($stoneTypeName)],
                    ['is_active' => true]
                );
                if ($stoneType->wasRecentlyCreated) {
                    $this->stats['created_types']['stone_types']++;
                }
            }

            $palletNumber = $row['nomer_poddona'] ?? null;
            if (empty($palletNumber)) {
                $this->stats['errors'][] = "Строка {$rowId}: Номер поддона не указан";
                return null;
            }

            $pallet = Pallet::firstOrCreate(
                ['number' => trim($palletNumber)],
                ['status' => Pallet::STATUS_IN_WAREHOUSE]
            );
            if ($pallet->wasRecentlyCreated) {
                $this->stats['created_types']['pallets']++;
                if (!$pallet->qr_code_path) {
                    $this->generateQrCode($pallet);
                }
            }

            $length = $this->normalizeNumber($row['dlina_sm'] ?? null);
            $width = $this->normalizeNumber($row['sirina_sm'] ?? null);
            $thickness = $this->normalizeNumber($row['tolshhina_sm'] ?? null);
            $weight = $this->normalizeNumber($row['ves_kg'] ?? null);
            $quantity = (int) ($row['kolicestvo'] ?? 0);

            if ($length <= 0 || $width <= 0 || $thickness <= 0) {
                $this->stats['errors'][] = "Строка {$rowId}: Некорректные размеры (длина={$length}, ширина={$width}, толщина={$thickness})";
                return null;
            }

            if ($quantity <= 0) {
                $this->stats['errors'][] = "Строка {$rowId}: Количество должно быть больше 0";
                return null;
            }

            $existing = StockPosition::find($rowId);
            $isUpdate = $existing !== null;

            $stockPosition = StockPosition::updateOrCreate(
                ['id' => $rowId],
                [
                    'product_type_id' => $productType->id,
                    'polish_type_id' => $polishType?->id,
                    'stone_type_id' => $stoneType?->id,
                    'pallet_id' => $pallet->id,
                    'length' => $length,
                    'width' => $width,
                    'thickness' => $thickness,
                    'weight' => $weight,
                    'quantity' => $quantity,
                ]
            );

            if ($isUpdate) {
                $this->stats['updated']++;
            } else {
                $this->stats['created']++;
            }

            return $stockPosition;
        } catch (\Exception $e) {
            $rowId = $row['id'] ?? 'неизвестно';
            $this->stats['errors'][] = "Строка {$rowId}: " . $e->getMessage();
            return null;
        }
    }

    private function normalizeNumber($value): float
    {
        if ($value === null || $value === '' || $value === '-') {
            return 0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = str_replace(',', '.', trim((string) $value));
        $normalized = preg_replace('/[^\d.]/', '', $normalized);
        
        return empty($normalized) ? 0 : (float) $normalized;
    }

    private function generateQrCode(Pallet $pallet): void
    {
        $qrData = route('pallet.show', $pallet->id);
        $fileName = 'qr_code_pallet_' . $pallet->id . '.svg';
        $filePath = 'qr_codes/' . $fileName;

        if (!Storage::disk('public')->exists('qr_codes')) {
            Storage::disk('public')->makeDirectory('qr_codes');
        }

        $qrSvg = QrCode::format('svg')
            ->size(200)
            ->margin(1)
            ->encoding('UTF-8')
            ->generate($qrData);

        $qrSvgWithText = $this->addTextToSvg($qrSvg, $pallet->number);

        Storage::disk('public')->put($filePath, $qrSvgWithText);

        $pallet->update(['qr_code_path' => $filePath]);
    }

    private function addTextToSvg(string $svg, string $text): string
    {
        $dom = new \DOMDocument();
        $dom->loadXML($svg);
        $svgElement = $dom->getElementsByTagName('svg')->item(0);

        $width = (float) $svgElement->getAttribute('width');
        $height = (float) $svgElement->getAttribute('height');

        $newHeight = $height + 40;
        $svgElement->setAttribute('height', $newHeight);
        $svgElement->setAttribute('viewBox', '0 0 ' . $width . ' ' . $newHeight);

        $bgRect = $dom->createElement('rect');
        $bgRect->setAttribute('x', '0');
        $bgRect->setAttribute('y', $height);
        $bgRect->setAttribute('width', $width);
        $bgRect->setAttribute('height', '40');
        $bgRect->setAttribute('fill', 'white');
        $svgElement->appendChild($bgRect);

        $textElement = $dom->createElement('text');
        $textElement->setAttribute('x', $width / 2);
        $textElement->setAttribute('y', $height + 32);
        $textElement->setAttribute('text-anchor', 'middle');
        $textElement->setAttribute('font-family', 'Arial, sans-serif');
        $textElement->setAttribute('font-size', '28');
        $textElement->setAttribute('font-weight', 'bold');
        $textElement->setAttribute('fill', 'black');
        $textElement->nodeValue = $text;

        $svgElement->appendChild($textElement);

        return $dom->saveXML();
    }

    public function getStats(): array
    {
        return $this->stats;
    }
}
