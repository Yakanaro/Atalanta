<x-app-layout>
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Общие стили для Select2 */
        .select2-container--default .select2-selection--single {
            height: 42px;
            padding: 6px;
            border-radius: 0.5rem;
            background-color: #1e293b;
            border-color: #374151;
            color: white;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white;
            padding-left: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #9ca3af transparent transparent transparent;
        }

        .select2-dropdown {
            background-color: #1e293b;
            border-color: #374151;
            border-radius: 0.5rem;
        }

        .select2-container--default .select2-results__option {
            color: white;
            padding: 8px 12px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #111827;
            border-color: #4b5563;
            color: white;
            border-radius: 0.25rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #374151;
        }

        /* Адаптивные стили для мобильных устройств */
        @media (max-width: 640px) {
            .select2-container {
                width: 100% !important;
            }

            .select2-container--default .select2-selection--single {
                height: 38px;
                padding: 4px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px;
            }
        }
    </style>
    @endpush

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

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Создание новой позиции</h2>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Отмена
                    </a>
                </div>
                <form action="{{ route('stockPosition.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
                    @csrf
                    <div class="grid gap-4 sm:gap-6 mb-4 sm:mb-6 grid-cols-1 md:grid-cols-2">
                        <div>
                            <label for="pallet_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Поддон</label>
                            <select id="pallet_id" name="pallet_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Выберите поддон</option>
                                @foreach($pallets as $id => $number)
                                <option value="{{ $id }}" {{ $selectedPalletId == $id ? 'selected' : '' }}>{{ $number }}</option>
                                @endforeach
                            </select>
                            <div class="mt-2">
                                <label for="pallet_number" class="block mb-1 text-xs font-medium text-gray-500 dark:text-gray-400">Или введите новый номер поддона</label>
                                <input type="text" id="pallet_number" name="pallet_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Введите номер нового поддона" />
                            </div>
                        </div>
                        <div>
                            <label for="product_type_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид продукции</label>
                            <select id="product_type_id" name="product_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="">Выберите вид продукции</option>
                                @foreach($productTypes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Размеры в одну строку на мобильных устройствах -->
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Размеры (см)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <div class="relative">
                                        <input type="number" id="length" name="length" step="0.01"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required placeholder="Длина" />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">Д</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="relative">
                                        <input type="number" id="width" name="width" step="0.01"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required placeholder="Ширина" />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">Ш</span>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="relative">
                                        <input type="number" id="thickness" name="thickness" step="0.01"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required placeholder="Толщина" />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400 text-sm">Т</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Количество штук</label>
                            <input type="number" id="quantity" name="quantity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                        </div>
                        <div>
                            <label for="polish_type_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид полировки</label>
                            <select id="polish_type_id" name="polish_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Выберите вид полировки</option>
                                @foreach($polishTypes as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-center w-full">
                        <label for="image" class="flex flex-col items-center justify-center w-full h-48 sm:h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 mb-4 sm:mb-6">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                <svg class="w-8 h-8 mb-3 sm:mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Нажмите чтобы загрузить</span> или перетащите файл</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG или GIF (MAX. 800x400px)</p>
                            </div>
                            <input id="image" name="image" type="file" class="hidden" />
                        </label>
                    </div>

                    <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Комментарий к продукции</label>
                    <textarea id="comment" name="comment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mb-4 sm:mb-6" placeholder="Напишите свой комментарий..."></textarea>
                    <div class="flex justify-center sm:justify-end">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Инициализация Select2 для поля "Поддон"
            $('#pallet_id').select2({
                placeholder: 'Выберите поддон',
                language: {
                    noResults: function() {
                        return "Поддоны не найдены";
                    }
                },
                width: '100%'
            });

            // Инициализация Select2 для поля "Вид продукции"
            $('#product_type_id').select2({
                placeholder: 'Выберите вид продукции',
                language: {
                    noResults: function() {
                        return "Ничего не найдено";
                    }
                },
                width: '100%'
            });

            // Инициализация Select2 для поля "Вид полировки"
            $('#polish_type_id').select2({
                placeholder: 'Выберите вид полировки',
                language: {
                    noResults: function() {
                        return "Ничего не найдено";
                    }
                },
                width: '100%'
            });

            // Логика переключения между выбором поддона и вводом нового номера
            $('#pallet_id').on('change', function() {
                if ($(this).val()) {
                    $('#pallet_number').val('').prop('disabled', true);
                } else {
                    $('#pallet_number').prop('disabled', false);
                }
            });

            $('#pallet_number').on('input', function() {
                if ($(this).val()) {
                    $('#pallet_id').val(null).trigger('change.select2').prop('disabled', true);
                } else {
                    $('#pallet_id').prop('disabled', false);
                }
            });

            // Инициализация состояния при загрузке страницы
            if ($('#pallet_id').val()) {
                $('#pallet_number').prop('disabled', true);
            }

            // Обработка изменения размера окна для корректной работы Select2
            $(window).resize(function() {
                $('#pallet_id, #product_type_id, #polish_type_id').select2({
                    width: '100%'
                });
            });
        });
    </script>
    @endpush
</x-app-layout>