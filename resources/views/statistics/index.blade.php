<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Статистика по позициям</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Объединение по продукции, размеру, полировке и типу камня</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('statistics.export', request()->query()) }}"
                        class="inline-flex items-center rounded-lg bg-green-700 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Экспорт Excel
                    </a>
                </div>
            </div>

            <div class="mb-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Групп</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $totals['groups_count'] }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Суммарное количество</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totals['total_quantity'], 0, '.', ' ') }}</div>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Суммарный вес (кг)</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ number_format($totals['total_weight'], 2, '.', ' ') }}</div>
                </div>
            </div>

            <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form action="{{ route('statistics.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div>
                        <label for="filter_product_type_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Тип продукции</label>
                        <select id="filter_product_type_id" name="filter_product_type_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            <option value="">Все</option>
                            @foreach($productTypes as $productType)
                                <option value="{{ $productType->id }}" {{ (string) $filters['filter_product_type_id'] === (string) $productType->id ? 'selected' : '' }}>
                                    {{ $productType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter_polish_type_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Вид полировки</label>
                        <select id="filter_polish_type_id" name="filter_polish_type_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            <option value="">Все</option>
                            @foreach($polishTypes as $polishType)
                                <option value="{{ $polishType->id }}" {{ (string) $filters['filter_polish_type_id'] === (string) $polishType->id ? 'selected' : '' }}>
                                    {{ $polishType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filter_stone_type_id" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Тип камня</label>
                        <select id="filter_stone_type_id" name="filter_stone_type_id" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                            <option value="">Все</option>
                            @foreach($stoneTypes as $stoneType)
                                <option value="{{ $stoneType->id }}" {{ (string) $filters['filter_stone_type_id'] === (string) $stoneType->id ? 'selected' : '' }}>
                                    {{ $stoneType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="filter_min_length" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Длина от</label>
                            <input id="filter_min_length" name="filter_min_length" type="number" step="0.01" value="{{ $filters['filter_min_length'] }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="filter_max_length" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Длина до</label>
                            <input id="filter_max_length" name="filter_max_length" type="number" step="0.01" value="{{ $filters['filter_max_length'] }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="filter_min_width" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Ширина от</label>
                            <input id="filter_min_width" name="filter_min_width" type="number" step="0.01" value="{{ $filters['filter_min_width'] }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="filter_max_width" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Ширина до</label>
                            <input id="filter_max_width" name="filter_max_width" type="number" step="0.01" value="{{ $filters['filter_max_width'] }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label for="filter_min_thickness" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Толщина от</label>
                            <input id="filter_min_thickness" name="filter_min_thickness" type="number" step="0.01" value="{{ $filters['filter_min_thickness'] }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label for="filter_max_thickness" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Толщина до</label>
                            <input id="filter_max_thickness" name="filter_max_thickness" type="number" step="0.01" value="{{ $filters['filter_max_thickness'] }}" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="inline-flex items-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Применить
                        </button>
                        <a href="{{ route('statistics.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                            Сбросить
                        </a>
                    </div>
                </form>
            </div>

            <div class="space-y-3 md:hidden">
                @forelse($statistics as $row)
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $row->product_type_name }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ number_format((float) $row->length, 2, '.', '') }}
                                    ×
                                    {{ number_format((float) $row->width, 2, '.', '') }}
                                    ×
                                    {{ number_format((float) $row->thickness, 2, '.', '') }}
                                </p>
                            </div>
                            <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ number_format((float) $row->total_quantity, 0, '.', ' ') }} шт
                            </span>
                        </div>
                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-lg bg-gray-50 px-3 py-2 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                Полировка: {{ $row->polish_type_name }}
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                Камень: {{ $row->stone_type_name }}
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                Позиций: {{ $row->positions_count }}
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                Вес: {{ number_format((float) $row->total_weight, 2, '.', '') }} кг
                            </div>
                            <div class="rounded-lg bg-gray-50 px-3 py-2 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                Доля: {{ number_format((float) $row->share_percent, 2, '.', '') }}%
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-gray-200 bg-white px-4 py-12 text-center text-gray-500 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        По текущим фильтрам данные не найдены
                    </div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 md:block">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Тип продукции</th>
                            <th class="px-4 py-3">Размер (см)</th>
                            <th class="px-4 py-3">Полировка</th>
                            <th class="px-4 py-3">Тип камня</th>
                            <th class="px-4 py-3 text-right">Количество</th>
                            <th class="px-4 py-3 text-right">Вес (кг)</th>
                            <th class="px-4 py-3 text-right">Позиций</th>
                            <th class="px-4 py-3 text-right">Доля (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statistics as $row)
                            <tr class="border-t border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $row->product_type_name }}</td>
                                <td class="px-4 py-3">
                                    {{ number_format((float) $row->length, 2, '.', '') }}
                                    ×
                                    {{ number_format((float) $row->width, 2, '.', '') }}
                                    ×
                                    {{ number_format((float) $row->thickness, 2, '.', '') }}
                                </td>
                                <td class="px-4 py-3">{{ $row->polish_type_name }}</td>
                                <td class="px-4 py-3">{{ $row->stone_type_name }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">{{ number_format((float) $row->total_quantity, 0, '.', ' ') }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format((float) $row->total_weight, 2, '.', ' ') }}</td>
                                <td class="px-4 py-3 text-right">{{ $row->positions_count }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format((float) $row->share_percent, 2, '.', '') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    По текущим фильтрам данные не найдены
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($statistics->hasPages())
                <div class="mt-6">
                    {{ $statistics->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

