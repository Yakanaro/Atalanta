<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center sm:text-left">
                {{ __('Редактирование поддона') }} {{ $pallet->number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('pallet.show', $pallet) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Отмена
                </a>
            </div>
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('pallet.update', $pallet) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Номер поддона</label>
                            <input type="text" id="number" name="number" value="{{ old('number', $pallet->number) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-1/2 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Введите номер поддона" required />
                        </div>

                        <!-- Изображение поддона -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Изображение поддона</label>
                            
                            @if($pallet->getImagePath())
                                <div class="mb-4 text-center">
                                    <img src="{{ $pallet->getImageUrl() }}" alt="Текущее изображение поддона" class="max-w-xs max-h-48 rounded-lg border border-gray-300 dark:border-gray-600 mx-auto">
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Текущее изображение</p>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-center w-full">
                                <label for="image" class="flex flex-col items-center justify-center w-full h-48 sm:h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                        <svg class="w-8 h-8 mb-3 sm:mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Нажмите чтобы загрузить</span> или перетащите файл</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, SVG до 10MB (необязательно)</p>
                                    </div>
                                    <input id="image" name="image" type="file" class="hidden" accept="image/*" onchange="previewImage(event)" />
                                </label>
                            </div>
                            
                            <!-- Предварительный просмотр изображения -->
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="previewImg" src="" alt="Предварительный просмотр" class="max-w-xs max-h-48 rounded-lg border border-gray-300 dark:border-gray-600 mx-auto">
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Статистика поддона</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                        <div class="flex justify-center sm:justify-end space-x-2">
                            <a href="{{ route('pallet.show', $pallet) }}"
                                class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                                Отмена
                            </a>
                            <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (file) {
                // Проверяем размер файла (10MB = 10 * 1024 * 1024 bytes)
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Размер файла превышает 10MB. Пожалуйста, выберите файл меньшего размера.');
                    event.target.value = '';
                    return;
                }
                
                // Проверяем тип файла
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Неподдерживаемый формат файла. Разрешены только: JPEG, PNG, JPG, GIF, SVG.');
                    event.target.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
                previewImg.src = '';
            }
        }

        // Drag and drop functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.querySelector('label[for="image"]');
            const fileInput = document.getElementById('image');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            }

            function unhighlight(e) {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                previewImage({ target: fileInput });
            }
        });
    </script>
</x-app-layout>