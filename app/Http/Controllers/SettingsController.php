<?php

namespace App\Http\Controllers;

use App\Models\PolishType;
use App\Models\ProductType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\StockPosition;

class SettingsController extends Controller
{
    public function index(): View
    {
        $polishTypes = PolishType::orderBy('name')->get();
        $productTypes = ProductType::orderBy('name')->get();
        
        return view('settings', compact('polishTypes', 'productTypes'));
    }
    
    public function addPolishType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:polish_types',
        ]);
        
        $validated['is_active'] = true;
        
        PolishType::create($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид полировки успешно добавлен.');
    }
    
    public function updatePolishType(Request $request, PolishType $polishType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:polish_types,name,' . $polishType->id,
            'is_active' => 'required|boolean',
        ]);
        
        $polishType->update($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид полировки успешно обновлен.');
    }
    
    public function deletePolishType(PolishType $polishType): RedirectResponse
    {
        $usageCount = StockPosition::where('polish_type_id', $polishType->id)->count();
        
        if ($usageCount > 0) {
            return back()->with('error', "Невозможно удалить вид полировки, так как он используется в {$usageCount} позициях.");
        }
        
        $polishType->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид полировки успешно удален.');
    }
    
    public function addProductType(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_types',
        ]);
        
        $validated['is_active'] = true;
        
        ProductType::create($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид продукции успешно добавлен.');
    }
    
    public function updateProductType(Request $request, ProductType $productType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_types,name,' . $productType->id,
            'is_active' => 'required|boolean',
        ]);
        
        $productType->update($validated);
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид продукции успешно обновлен.');
    }
    
    public function deleteProductType(ProductType $productType): RedirectResponse
    {
        $usageCount = StockPosition::where('product_type_id', $productType->id)->count();
        
        if ($usageCount > 0) {
            return back()->with('error', "Невозможно удалить вид продукции, так как он используется в {$usageCount} позициях.");
        }
        
        $productType->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Вид продукции успешно удален.');
    }
} 