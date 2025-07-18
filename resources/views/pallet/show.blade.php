<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center sm:text-left">
                {{ __('Поддон') }} {{ $pallet->number }}
            </h2>
            @auth
            <div class="flex space-x-2">
                <a href="{{ route('pallet.edit', $pallet) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Редактировать
                </a>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Назад
                </a>
            </div>
            @endauth
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Алерты для сообщений -->
            @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-700 dark:text-green-400 flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400 flex items-center" role="alert">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <!-- Информация о поддоне -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Информация о поддоне</h3>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Номер поддона</div>
                            <div class="text-lg font-semibold">{{ $pallet->number }}</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Статус</div>
                            @php
                            $statusData = $pallet->getStatusWithClass();
                            @endphp
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusData['class'] }}">
                                    {{ $statusData['status'] }}
                                </span>
                                @auth
                                <form action="{{ route('pallet.update-status', $pallet) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                        class="text-xs bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-2 py-1 dark:bg-gray-600 dark:border-gray-500 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 w-full">
                                        @foreach(\App\Models\Pallet::getAvailableStatuses() as $statusKey => $statusLabel)
                                        <option value="{{ $statusKey }}" {{ $pallet->status === $statusKey ? 'selected' : '' }}>
                                            {{ $statusLabel }}
                                        </option>
                                        @endforeach
                                    </select>
                                </form>
                                @endauth
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Количество позиций</div>
                            <div class="text-lg font-semibold">{{ $pallet->stockPositions->count() }}</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Общий вес</div>
                            <div class="text-lg font-semibold">{{ number_format($pallet->getTotalWeight(), 2) }} кг</div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Общее количество</div>
                            <div class="text-lg font-semibold">{{ $pallet->getTotalQuantity() }} шт</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Изображение поддона -->
            @if($pallet->getImagePath())
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Изображение поддона</h3>
                    <div class="flex justify-center">
                        <img src="{{ $pallet->getImageUrl() }}" alt="Изображение поддона {{ $pallet->number }}" 
                             class="max-w-full max-h-96 rounded-lg border border-gray-300 dark:border-gray-600 shadow-md">
                    </div>
                </div>
            </div>
            @endif

            <!-- QR-код поддона -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <h3 class="text-lg font-semibold">QR-код поддона</h3>
                        @auth
                        @if($pallet->getQrCodePath())
                        <a href="{{ route('pallet.download-qr', $pallet) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Скачать QR-код
                        </a>
                        @endif
                        @endauth
                    </div>

                    @if($pallet->getQrCodePath())
                    <div class="flex justify-center">
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <img src="{{ $pallet->getQrCodeUrl() }}" alt="QR-код поддона {{ $pallet->number }}" class="w-48 h-48">
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">QR-код не найден</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">QR-код будет сгенерирован автоматически при создании поддона</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Позиции поддона -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <h3 class="text-lg font-semibold">Позиции поддона</h3>
                        @auth
                        @if($pallet->stockPositions->count() > 0)
                        <a href="{{ route('stockPosition.create') }}?pallet_id={{ $pallet->id }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Добавить позицию
                        </a>
                        @endif
                        @endauth
                    </div>

                    @if($pallet->stockPositions->count() > 0)

                    <!-- Мобильная версия: карточки -->
                    <div class="block md:hidden space-y-4">
                        @foreach($pallet->stockPositions as $position)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 shadow-sm">
                            <!-- Заголовок карточки -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">ID</span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $position->id }}</span>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                        {{ $position->getProductType() }}
                                    </div>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('stockPosition.show', $position) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-200 text-sm font-medium rounded-lg transition-colors duration-200 min-h-[44px]">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Просмотр
                                    </a>
                                    @auth
                                    <a href="{{ route('stockPosition.edit', $position) }}"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-700 dark:text-yellow-200 text-sm font-medium rounded-lg transition-colors duration-200 min-h-[44px]">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Редактировать
                                    </a>
                                    @endauth
                                </div>
                            </div>

                            <!-- Основная информация -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Размеры</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $position->getLength() }}×{{ $position->getWidth() }}×{{ $position->getThickness() }} см
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Количество</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $position->getQuantity() }} шт
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Вес</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ number_format($position->getWeight(), 2) }} кг
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Полировка</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $position->getPolishType() ?: 'Не указано' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Вид камня</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $position->getStoneType() ?: 'Не указано' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Десктопная версия: таблица -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Вид продукции</th>
                                    <th scope="col" class="px-6 py-3">Размеры (Д×Ш×Т)</th>
                                    <th scope="col" class="px-6 py-3">Количество</th>
                                    <th scope="col" class="px-6 py-3">Вес</th>
                                    <th scope="col" class="px-6 py-3">Полировка</th>
                                    <th scope="col" class="px-6 py-3">Вид камня</th>
                                    <th scope="col" class="px-6 py-3">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pallet->stockPositions as $position)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $position->id }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $position->getProductType() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $position->getLength() }}×{{ $position->getWidth() }}×{{ $position->getThickness() }} см
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $position->getQuantity() }} шт
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ number_format($position->getWeight(), 2) }} кг
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $position->getPolishType() ?: 'Не указано' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $position->getStoneType() ?: 'Не указано' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            <a href="{{ route('stockPosition.show', $position) }}"
                                                class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-200 text-xs font-medium rounded-md transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Просмотр
                                            </a>
                                            @auth
                                            <a href="{{ route('stockPosition.edit', $position) }}"
                                                class="inline-flex items-center px-2 py-1 bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-700 dark:text-yellow-200 text-xs font-medium rounded-md transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Редактировать
                                            </a>
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">В поддоне нет позиций</h3>
                        @auth
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Добавьте первую позицию в этот поддон</p>
                        <a href="{{ route('stockPosition.create') }}?pallet_id={{ $pallet->id }}"
                            class="inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Добавить позицию
                        </a>
                        @else
                        <p class="text-gray-500 dark:text-gray-400">Этот поддон пуст</p>
                        @endauth
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>