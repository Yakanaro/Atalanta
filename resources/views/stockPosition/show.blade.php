<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Информация о позиции #') }}{{ $stockPosition->id }}
            </h2>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 w-full sm:w-auto">
                <a href="{{ route('stockPosition.edit', $stockPosition->id) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Редактировать
                </a>
                <button type="button"
                    data-modal-target="deleteModal"
                    data-modal-toggle="deleteModal"
                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Удалить
                </button>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Назад
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Характеристики</h3>
                            <dl class="grid grid-cols-2 gap-4">
                                <dt class="font-medium">Тип:</dt>
                                <dd>{{ $stockPosition->getProductType() }}</dd>

                                <dt class="font-medium col-span-2 mt-2">Размеры:</dt>
                                <div class="col-span-2 bg-gray-100 dark:bg-gray-700 p-3 rounded-lg mb-2">
                                    <div class="grid grid-cols-3 gap-4 text-center">
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Длина</span>
                                            <span class="text-lg font-bold">{{ $stockPosition->formatted_length }}</span>
                                            <span class="text-xs">см</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Ширина</span>
                                            <span class="text-lg font-bold">{{ $stockPosition->formatted_width }}</span>
                                            <span class="text-xs">см</span>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Толщина</span>
                                            <span class="text-lg font-bold">{{ $stockPosition->formatted_thickness }}</span>
                                            <span class="text-xs">см</span>
                                        </div>
                                    </div>
                                </div>

                                <dt class="font-medium">Количество:</dt>
                                <dd>{{ $stockPosition->quantity }}</dd>

                                <dt class="font-medium">Вес:</dt>
                                <dd>{{ $stockPosition->formatted_weight ?? ($stockPosition->weight ?? '-') }} кг</dd>

                                @if($stockPosition->polishType)
                                <dt class="font-medium">Тип полировки:</dt>
                                <dd>{{ $stockPosition->getPolishType() }}</dd>
                                @endif

                                @if($stockPosition->pallet_number)
                                <dt class="font-medium">Номер поддона:</dt>
                                <dd>{{ $stockPosition->pallet_number }}</dd>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">QR-код</h3>
                                @if($stockPosition->qr_code_path)
                                <a href="{{ route('stockPosition.download-qr', $stockPosition->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Скачать QR-код
                                </a>
                                @endif
                            </div>
                            @if($stockPosition->qr_code_path)
                            <div class="max-w-xs border border-gray-300 dark:border-gray-600 p-2 rounded">
                                <img src="{{ Storage::url($stockPosition->qr_code_path) }}"
                                    alt="QR код для позиции #{{ $stockPosition->id }}"
                                    class="w-full">
                            </div>
                            @else
                            <p class="text-red-500">QR-код не найден</p>
                            @endif

                            @if($stockPosition->image_path)
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold mb-4">Изображение</h3>
                                <div class="border border-gray-300 dark:border-gray-600 p-2 rounded">
                                    <img src="{{ $stockPosition->image_path }}"
                                        alt="Изображение позиции #{{ $stockPosition->id }}"
                                        class="w-full rounded">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно подтверждения удаления -->
    <div id="deleteModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="deleteModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Закрыть</span>
                </button>
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-red-600 w-12 h-12 dark:text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Вы уверены, что хотите удалить эту позицию?</h3>
                    <p class="mb-5 text-sm text-gray-500 dark:text-gray-400">Это действие нельзя будет отменить. Все данные о позиции, включая QR-код, будут удалены.</p>
                    <form action="{{ route('stockPosition.destroy', $stockPosition->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                            Да, удалить
                        </button>
                    </form>
                    <button data-modal-hide="deleteModal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>