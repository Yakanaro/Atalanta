<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Сообщения об успехе или ошибке -->
            @if(session('success'))
            <div id="success-alert" class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div id="error-alert" class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 hidden sm:block">Список поддонов</h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('pallet.create') }}"
                        class="inline-block text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 focus:outline-none dark:focus:ring-purple-800">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            Создать поддон
                        </span>
                    </a>
                    <a href="{{ route('stockPosition.create') }}"
                        class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Добавить позицию
                        </span>
                    </a>
                    <a href="{{ route('stockPosition.export') }}"
                        class="inline-block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            Экспорт Excel
                        </span>
                    </a>
                </div>
            </div>

            <!-- Фильтры для мобильных (скрыты на десктопах) -->
            <div class="mb-6 md:hidden">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Список поддонов</h2>
                </div>

                <button id="mobile-filter-button" type="button" class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg shadow text-gray-900 dark:text-white">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Фильтры
                    </span>
                    <svg id="mobile-filter-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="mobile-filter-panel" class="hidden mt-2 bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                    <form action="{{ route('dashboard') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="mobile_filter_pallet_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Номер поддона</label>
                            <input type="text" id="mobile_filter_pallet_number" name="filter_pallet_number" value="{{ request('filter_pallet_number') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Поиск по номеру поддона">
                        </div>

                        <div>
                            <label for="mobile_filter_product_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Тип продукции</label>
                            <select id="mobile_filter_product_type" name="filter_product_type_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все виды</option>
                                @foreach($productTypes as $productType)
                                <option value="{{ $productType->id }}" {{ request('filter_product_type_id') == $productType->id ? 'selected' : '' }}>{{ $productType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="mobile_filter_polish_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид полировки</label>
                            <select id="mobile_filter_polish_type" name="filter_polish_type_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все виды</option>
                                @foreach($polishTypes as $polishType)
                                <option value="{{ $polishType->id }}" {{ request('filter_polish_type_id') == $polishType->id ? 'selected' : '' }}>{{ $polishType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="mobile_filter_stone_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Тип камня</label>
                            <select id="mobile_filter_stone_type" name="filter_stone_type_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все типы</option>
                                @foreach($stoneTypes as $stoneType)
                                <option value="{{ $stoneType->id }}" {{ request('filter_stone_type_id') == $stoneType->id ? 'selected' : '' }}>{{ $stoneType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="mobile_filter_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Статус поддона</label>
                            <select id="mobile_filter_status" name="filter_status"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все статусы</option>
                                @foreach($palletStatuses as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" {{ request('filter_status') == $statusKey ? 'selected' : '' }}>{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex space-x-2">
                            <button type="submit" class="flex-1 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Фильтровать
                            </button>
                            <a href="{{ route('dashboard') }}" class="flex-1 text-center text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                Сбросить
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Фильтры для десктопов (скрыты на мобильных) -->
            <div class="mb-6 hidden md:block">
                <button id="desktop-filter-button" type="button" class="w-full flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg shadow text-gray-900 dark:text-white mb-2">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Фильтры
                    </span>
                    <svg id="desktop-filter-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="desktop-filter-panel" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 hidden">
                    <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div>
                            <label for="filter_pallet_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Номер поддона</label>
                            <input type="text" id="filter_pallet_number" name="filter_pallet_number" value="{{ request('filter_pallet_number') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Поиск по номеру поддона">
                        </div>

                        <div>
                            <label for="filter_product_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Тип продукции</label>
                            <select id="filter_product_type" name="filter_product_type_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все виды</option>
                                @foreach($productTypes as $productType)
                                <option value="{{ $productType->id }}" {{ request('filter_product_type_id') == $productType->id ? 'selected' : '' }}>{{ $productType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filter_polish_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид полировки</label>
                            <select id="filter_polish_type" name="filter_polish_type_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все виды</option>
                                @foreach($polishTypes as $polishType)
                                <option value="{{ $polishType->id }}" {{ request('filter_polish_type_id') == $polishType->id ? 'selected' : '' }}>{{ $polishType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filter_stone_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Тип камня</label>
                            <select id="filter_stone_type" name="filter_stone_type_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все типы</option>
                                @foreach($stoneTypes as $stoneType)
                                <option value="{{ $stoneType->id }}" {{ request('filter_stone_type_id') == $stoneType->id ? 'selected' : '' }}>{{ $stoneType->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filter_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Статус поддона</label>
                            <select id="filter_status" name="filter_status"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все статусы</option>
                                @foreach($palletStatuses as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" {{ request('filter_status') == $statusKey ? 'selected' : '' }}>{{ $statusLabel }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end space-x-2">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                Фильтровать
                            </button>
                            <a href="{{ route('dashboard') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                Сбросить
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Таблица для десктопов (скрыта на мобильных) -->
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg hidden md:block" style="min-height: 400px;">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Номер поддона
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Статус
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Позиций
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Общий вес
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Общее количество
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Дата создания
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Действия</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pallets as $pallet)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $pallet->number }}
                            </th>
                            <td class="px-6 py-4">
                                @php
                                $statusData = $pallet->getStatusWithClass();
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusData['class'] }}">
                                    {{ $statusData['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $pallet->stock_positions_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $pallet->total_weight ? number_format($pallet->total_weight, 2) . ' кг' : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $pallet->total_quantity ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $pallet->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('pallet.show', $pallet->id) }}"
                                        class="text-blue-600 dark:text-blue-500 hover:bg-blue-100 dark:hover:bg-blue-900 p-1.5 rounded-full transition-colors"
                                        title="Просмотр">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('pallet.edit', $pallet->id) }}"
                                        class="text-green-600 dark:text-green-500 hover:bg-green-100 dark:hover:bg-green-900 p-1.5 rounded-full transition-colors"
                                        title="Редактировать">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('stockPosition.create', ['pallet_id' => $pallet->id]) }}"
                                        class="text-purple-600 dark:text-purple-500 hover:bg-purple-100 dark:hover:bg-purple-900 p-1.5 rounded-full transition-colors"
                                        title="Добавить позицию">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </a>
                                    <button type="button"
                                        data-modal-target="deleteModal-{{ $pallet->id }}"
                                        data-modal-toggle="deleteModal-{{ $pallet->id }}"
                                        class="text-red-600 dark:text-red-500 hover:bg-red-100 dark:hover:bg-red-900 p-1.5 rounded-full transition-colors"
                                        title="Удалить">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Модальное окно подтверждения удаления -->
                                <div id="deleteModal-{{ $pallet->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-md max-h-full">
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal-{{ $pallet->id }}">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                                <span class="sr-only">Закрыть</span>
                                            </button>
                                            <div class="p-6 text-center">
                                                <svg class="mx-auto mb-4 text-red-600 w-12 h-12 dark:text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Вы уверены, что хотите удалить поддон "{{ $pallet->number }}"?</h3>
                                                <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Это действие нельзя будет отменить. Все позиции в этом поддоне также будут удалены.</p>
                                                <form action="{{ route('pallet.destroy', $pallet->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                        Да, удалить
                                                    </button>
                                                </form>
                                                <button data-modal-hide="deleteModal-{{ $pallet->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                                    Отмена
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if(count($pallets) === 0)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="7" class="px-6 py-20 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <div>
                                        <p class="text-lg font-medium text-gray-900 dark:text-white">Нет поддонов</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Создайте первый поддон, чтобы начать работу</p>
                                    </div>
                                    <a href="{{ route('pallet.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Создать поддон
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Карточки для мобильных устройств (скрыты на десктопах) -->
            <div class="md:hidden space-y-4">
                @if(count($pallets) === 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-lg font-medium">Нет данных</p>
                    <p class="mt-1 text-sm">Поддоны не найдены или отсутствуют</p>
                </div>
                @endif

                @foreach($pallets as $pallet)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Поддон {{ $pallet->number }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pallet->created_at->format('d.m.Y H:i') }}</p>
                            @php
                            $statusData = $pallet->getStatusWithClass();
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusData['class'] }}">
                                {{ $statusData['status'] }}
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('pallet.show', $pallet->id) }}"
                                class="inline-flex items-center justify-center p-2 bg-blue-600 text-white text-xs font-medium rounded-full hover:bg-blue-700"
                                title="Просмотр">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            <a href="{{ route('pallet.edit', $pallet->id) }}"
                                class="inline-flex items-center justify-center p-2 bg-green-600 text-white text-xs font-medium rounded-full hover:bg-green-700"
                                title="Редактировать">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <a href="{{ route('stockPosition.create', ['pallet_id' => $pallet->id]) }}"
                                class="inline-flex items-center justify-center p-2 bg-purple-600 text-white text-xs font-medium rounded-full hover:bg-purple-700"
                                title="Добавить позицию">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                            <button type="button"
                                data-modal-target="deleteMobileModal-{{ $pallet->id }}"
                                data-modal-toggle="deleteMobileModal-{{ $pallet->id }}"
                                class="inline-flex items-center justify-center p-2 bg-red-600 text-white text-xs font-medium rounded-full hover:bg-red-700"
                                title="Удалить">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>

                        <!-- Модальное окно подтверждения удаления для мобильных -->
                        <div id="deleteMobileModal-{{ $pallet->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteMobileModal-{{ $pallet->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Закрыть</span>
                                    </button>
                                    <div class="p-6 text-center">
                                        <svg class="mx-auto mb-4 text-red-600 w-12 h-12 dark:text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Вы уверены, что хотите удалить поддон "{{ $pallet->number }}"?</h3>
                                        <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Это действие нельзя будет отменить. Все позиции в этом поддоне также будут удалены.</p>
                                        <form action="{{ route('pallet.destroy', $pallet->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                Да, удалить
                                            </button>
                                        </form>
                                        <button data-modal-hide="deleteMobileModal-{{ $pallet->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Отмена</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $pallet->stock_positions_count }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Позиций</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $pallet->total_weight ? number_format($pallet->total_weight, 2) : '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Общий вес (кг)</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $pallet->total_quantity ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Общее кол-во</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // Управление фильтрами
        document.addEventListener('DOMContentLoaded', function() {
            const mobileFilterButton = document.getElementById('mobile-filter-button');
            const mobileFilterPanel = document.getElementById('mobile-filter-panel');
            const mobileFilterIcon = document.getElementById('mobile-filter-icon');

            const desktopFilterButton = document.getElementById('desktop-filter-button');
            const desktopFilterPanel = document.getElementById('desktop-filter-panel');
            const desktopFilterIcon = document.getElementById('desktop-filter-icon');

            function toggleFilter(button, panel, icon) {
                if (panel.classList.contains('hidden')) {
                    panel.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    panel.classList.add('hidden');
                    icon.style.transform = 'rotate(0deg)';
                }
            }

            if (mobileFilterButton) {
                mobileFilterButton.addEventListener('click', function() {
                    toggleFilter(mobileFilterButton, mobileFilterPanel, mobileFilterIcon);
                });
            }

            if (desktopFilterButton) {
                desktopFilterButton.addEventListener('click', function() {
                    toggleFilter(desktopFilterButton, desktopFilterPanel, desktopFilterIcon);
                });
            }

            // Автоматическое скрытие уведомлений через 5 секунд
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');

            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    setTimeout(() => {
                        successAlert.remove();
                    }, 300);
                }, 5000);
            }

            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    setTimeout(() => {
                        errorAlert.remove();
                    }, 300);
                }, 5000);
            }
        });
    </script>
</x-app-layout>