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
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 hidden sm:block">Список позиций</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('stockPosition.export') }}" 
                       class="inline-block text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Экспорт в Excel
                        </span>
                    </a>
                    <a href="{{ route('stockPosition.create') }}" 
                       class="inline-block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Добавить
                        </span>
                    </a>
                </div>
            </div>
            
            <!-- Фильтры для мобильных (скрыты на десктопах) -->
            <div class="mb-6 md:hidden">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Список позиций</h2>
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
                            <label for="mobile_filter_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID</label>
                            <input type="text" id="mobile_filter_id" name="filter_id" value="{{ request('filter_id') }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                   placeholder="Поиск по ID">
                        </div>
                        
                        <div>
                            <label for="mobile_filter_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Тип</label>
                            <select id="mobile_filter_type" name="filter_type" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все виды</option>
                                @foreach($productTypes as $productType)
                                    <option value="{{ $productType->id }}" {{ request('filter_type') == $productType->id ? 'selected' : '' }}>{{ $productType->name }}</option>
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
                            <label for="mobile_filter_pallet_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Номер поддона</label>
                            <input type="text" id="mobile_filter_pallet_number" name="filter_pallet_number" value="{{ request('filter_pallet_number') }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                   placeholder="Поиск по номеру поддона">
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
                    <form action="{{ route('dashboard') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="filter_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID</label>
                            <input type="text" id="filter_id" name="filter_id" value="{{ request('filter_id') }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                   placeholder="Поиск по ID">
                        </div>
                        
                        <div>
                            <label for="filter_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Тип</label>
                            <select id="filter_type" name="filter_type" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Все виды</option>
                                @foreach($productTypes as $productType)
                                    <option value="{{ $productType->id }}" {{ request('filter_type') == $productType->id ? 'selected' : '' }}>{{ $productType->name }}</option>
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
                            <label for="filter_pallet_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Номер поддона</label>
                            <input type="text" id="filter_pallet_number" name="filter_pallet_number" value="{{ request('filter_pallet_number') }}" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                   placeholder="Поиск по номеру поддона">
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
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg hidden md:block">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Номер поддона
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Тип
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Размеры
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Кол-во
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Вид полировки
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">Действия</span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stockPositions as $position)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $position->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $position->pallet_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $position->getType() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium">Д: {{ $position->formatted_length }} см</span>
                                    <span class="font-medium">Ш: {{ $position->formatted_width }} см</span>
                                    <span class="font-medium">Т: {{ $position->formatted_thickness }} см</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $position->quantity }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $position->getPolishType() ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('stockPosition.show', $position->id) }}" 
                                       class="text-blue-600 dark:text-blue-500 hover:bg-blue-100 dark:hover:bg-blue-900 p-1.5 rounded-full transition-colors"
                                       title="Просмотр">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('stockPosition.edit', $position->id) }}" 
                                       class="text-green-600 dark:text-green-500 hover:bg-green-100 dark:hover:bg-green-900 p-1.5 rounded-full transition-colors"
                                       title="Редактировать">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <button type="button" 
                                        data-modal-target="deleteModal-{{ $position->id }}" 
                                        data-modal-toggle="deleteModal-{{ $position->id }}"
                                        class="text-red-600 dark:text-red-500 hover:bg-red-100 dark:hover:bg-red-900 p-1.5 rounded-full transition-colors"
                                        title="Удалить">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <!-- Модальное окно подтверждения удаления -->
                                <div id="deleteModal-{{ $position->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                    <div class="relative w-full max-w-md max-h-full">
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal-{{ $position->id }}">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                </svg>
                                                <span class="sr-only">Закрыть</span>
                                            </button>
                                            <div class="p-6 text-center">
                                                <svg class="mx-auto mb-4 text-red-600 w-12 h-12 dark:text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Вы уверены, что хотите удалить позицию #{{ $position->id }}?</h3>
                                                <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Это действие нельзя будет отменить. Все данные о позиции, включая QR-код, будут удалены.</p>
                                                <form action="{{ route('stockPosition.destroy', $position->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                        Да, удалить
                                                    </button>
                                                </form>
                                                <button data-modal-hide="deleteModal-{{ $position->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Отмена</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    
                    @if(count($stockPositions) === 0)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Нет данных
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Карточки для мобильных устройств (скрыты на десктопах) -->
            <div class="md:hidden space-y-4">
                @if(count($stockPositions) === 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-lg font-medium">Нет данных</p>
                        <p class="mt-1 text-sm">Записи не найдены или отсутствуют</p>
                    </div>
                @endif
                
                @foreach($stockPositions as $position)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $position->id }}</span>
                                @if($position->pallet_number)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Поддон: {{ $position->pallet_number }}</p>
                                @endif
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $position->getType() }}</h3>
                            </div>
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <div class="flex space-x-2">
                                    <a href="{{ route('stockPosition.show', $position->id) }}" 
                                       class="inline-flex items-center justify-center p-2 bg-blue-600 text-white text-xs font-medium rounded-full hover:bg-blue-700"
                                       title="Просмотр">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('stockPosition.edit', $position->id) }}" 
                                       class="inline-flex items-center justify-center p-2 bg-green-600 text-white text-xs font-medium rounded-full hover:bg-green-700"
                                       title="Редактировать">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <button type="button" 
                                       data-modal-target="deleteMobileModal-{{ $position->id }}" 
                                       data-modal-toggle="deleteMobileModal-{{ $position->id }}"
                                       class="inline-flex items-center justify-center p-2 bg-red-600 text-white text-xs font-medium rounded-full hover:bg-red-700"
                                       title="Удалить">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Модальное окно подтверждения удаления для мобильных -->
                            <div id="deleteMobileModal-{{ $position->id }}" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative w-full max-w-md max-h-full">
                                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                        <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteMobileModal-{{ $position->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                            </svg>
                                            <span class="sr-only">Закрыть</span>
                                        </button>
                                        <div class="p-6 text-center">
                                            <svg class="mx-auto mb-4 text-red-600 w-12 h-12 dark:text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                            </svg>
                                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Вы уверены, что хотите удалить позицию #{{ $position->id }}?</h3>
                                            <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Это действие нельзя будет отменить. Все данные о позиции, включая QR-код, будут удалены.</p>
                                            <form action="{{ route('stockPosition.destroy', $position->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                                                    Да, удалить
                                                </button>
                                            </form>
                                            <button data-modal-hide="deleteMobileModal-{{ $position->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Отмена</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="grid grid-cols-3 gap-2 bg-gray-100 dark:bg-gray-700 p-2 rounded">
                                <div class="flex flex-col items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Длина</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $position->formatted_length }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">см</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Ширина</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $position->formatted_width }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">см</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Толщина</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $position->formatted_thickness }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">см</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between">
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Количество</span>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $position->quantity }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Вид полировки</span>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $position->getPolishType() ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Автоматическое скрытие уведомлений через 5 секунд
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.transition = 'opacity 1s ease-out';
                    successAlert.style.opacity = '0';
                    setTimeout(function() {
                        successAlert.style.display = 'none';
                    }, 1000);
                }, 5000);
            }
            
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.transition = 'opacity 1s ease-out';
                    errorAlert.style.opacity = '0';
                    setTimeout(function() {
                        errorAlert.style.display = 'none';
                    }, 1000);
                }, 5000);
            }
            
            // Код для десктопных фильтров
            const desktopFilterButton = document.getElementById('desktop-filter-button');
            const desktopFilterPanel = document.getElementById('desktop-filter-panel');
            const desktopFilterIcon = document.getElementById('desktop-filter-icon');
            
            if (desktopFilterButton && desktopFilterPanel && desktopFilterIcon) {
                // Проверяем, есть ли активные фильтры, и если да, то открываем панель
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('filter_id') || urlParams.has('filter_type') || urlParams.has('filter_polish_type_id') || urlParams.has('filter_pallet_number')) {
                    desktopFilterPanel.classList.remove('hidden');
                    desktopFilterIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
                }
                
                desktopFilterButton.addEventListener('click', function() {
                    desktopFilterPanel.classList.toggle('hidden');
                    
                    if (desktopFilterPanel.classList.contains('hidden')) {
                        desktopFilterIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
                    } else {
                        desktopFilterIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
                    }
                });
            }
            
            // Существующий код для мобильных фильтров
            const mobileFilterButton = document.getElementById('mobile-filter-button');
            const mobileFilterPanel = document.getElementById('mobile-filter-panel');
            const mobileFilterIcon = document.getElementById('mobile-filter-icon');
            
            if (mobileFilterButton && mobileFilterPanel && mobileFilterIcon) {
                // Проверяем, есть ли активные фильтры, и если да, то открываем панель
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('filter_id') || urlParams.has('filter_type') || urlParams.has('filter_polish_type_id') || urlParams.has('filter_pallet_number')) {
                    mobileFilterPanel.classList.remove('hidden');
                    mobileFilterIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
                }
                
                mobileFilterButton.addEventListener('click', function() {
                    mobileFilterPanel.classList.toggle('hidden');
                    
                    if (mobileFilterPanel.classList.contains('hidden')) {
                        mobileFilterIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />';
                    } else {
                        mobileFilterIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />';
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
