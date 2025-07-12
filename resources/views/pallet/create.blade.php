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

        .position-item {
            border: 1px solid #374151;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #1f2937;
        }

        .position-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .remove-position {
            background-color: #dc2626;
            color: white;
            border: none;
            border-radius: 0.25rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }

        .remove-position:hover {
            background-color: #b91c1c;
        }

        .add-position {
            background-color: #16a34a;
            color: white;
            border: none;
            border-radius: 0.25rem;
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            margin-bottom: 1rem;
        }

        .add-position:hover {
            background-color: #15803d;
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

            @if ($errors->any())
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400" role="alert">
                <div class="font-medium">Ошибки валидации:</div>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Создание поддона</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Позиции можно добавить сейчас или позже</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Отмена
                    </a>
                </div>

                <form action="{{ route('pallet.store') }}" method="POST" class="p-4 sm:p-6">
                    @csrf

                    <!-- Информация о поддоне -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Информация о поддоне</h3>
                        <div class="grid gap-4 sm:gap-6 grid-cols-1 md:grid-cols-2">
                            <div>
                                <label for="pallet_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Номер поддона</label>
                                <input type="text" id="pallet_number" name="pallet_number" value="{{ old('pallet_number') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Введите номер поддона" required />
                            </div>
                        </div>
                    </div>

                    <!-- Позиции -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Позиции поддона</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Необязательно - можно добавить позиции позже</p>
                            </div>
                            <button type="button" class="add-position" onclick="addPosition()">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Добавить позицию
                            </button>
                        </div>

                        <div id="positions-container">
                            <!-- Позиции добавляются динамически -->
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400" id="empty-positions-message">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium">Позиции не добавлены</h3>
                                <p class="mt-1 text-sm">Нажмите "Добавить позицию" для добавления позиций к поддону</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center sm:justify-end">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Создать поддон
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let positionCounter = 0;

        $(document).ready(function() {
            initializeSelect2();
            updateEmptyMessage();
        });

        function initializeSelect2() {
            $('.product-type-select').select2({
                placeholder: 'Выберите вид продукции',
                width: '100%'
            });

            $('.polish-type-select').select2({
                placeholder: 'Выберите вид полировки',
                width: '100%'
            });
        }

        function addPosition() {
            const container = document.getElementById('positions-container');
            const newPositionHtml = `
                <div class="position-item" data-position="${positionCounter}">
                    <div class="position-header">
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">Позиция ${positionCounter + 1}</h4>
                        <button type="button" class="remove-position" onclick="removePosition(${positionCounter})">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Удалить
                        </button>
                    </div>
                    
                    <div class="grid gap-4 sm:gap-6 grid-cols-1 md:grid-cols-2">
                        <div>
                            <label for="positions[${positionCounter}][product_type_id]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид продукции</label>
                            <select name="positions[${positionCounter}][product_type_id]" class="product-type-select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                <option value="">Выберите вид продукции</option>
                                @foreach($productTypes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="positions[${positionCounter}][polish_type_id]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид полировки</label>
                            <select name="positions[${positionCounter}][polish_type_id]" class="polish-type-select bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="">Выберите вид полировки</option>
                                @foreach($polishTypes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid gap-4 sm:gap-6 grid-cols-1 md:grid-cols-4 mt-4">
                        <div>
                            <label for="positions[${positionCounter}][length]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Длина (см)</label>
                            <input type="number" name="positions[${positionCounter}][length]" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Длина" required />
                        </div>
                        <div>
                            <label for="positions[${positionCounter}][width]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ширина (см)</label>
                            <input type="number" name="positions[${positionCounter}][width]" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Ширина" required />
                        </div>
                        <div>
                            <label for="positions[${positionCounter}][thickness]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Толщина (см)</label>
                            <input type="number" name="positions[${positionCounter}][thickness]" step="0.01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Толщина" required />
                        </div>
                        <div>
                            <label for="positions[${positionCounter}][quantity]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Количество</label>
                            <input type="number" name="positions[${positionCounter}][quantity]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Количество" required />
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', newPositionHtml);

            // Инициализируем Select2 для новых элементов
            $(container).find('.position-item').last().find('.product-type-select, .polish-type-select').select2({
                placeholder: function() {
                    return $(this).hasClass('product-type-select') ? 'Выберите вид продукции' : 'Выберите вид полировки';
                },
                width: '100%'
            });

            positionCounter++;
            updateEmptyMessage();
        }

        function removePosition(index) {
            const positionElement = document.querySelector(`[data-position="${index}"]`);
            if (positionElement) {
                positionElement.remove();
                updateEmptyMessage();
            }
        }

        function updateEmptyMessage() {
            const positions = document.querySelectorAll('.position-item');
            const emptyMessage = document.getElementById('empty-positions-message');
            
            if (positions.length === 0) {
                emptyMessage.style.display = 'block';
            } else {
                emptyMessage.style.display = 'none';
            }
        }
    </script>
    @endpush
</x-app-layout>