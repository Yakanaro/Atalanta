<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Импорт позиций из Excel</h2>
                <p class="text-gray-600 dark:text-gray-400">Загрузите файл Excel для импорта позиций на склад</p>
            </div>

            @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Ошибки при импорте:</h3>
                <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1 max-h-60 overflow-y-auto">
                    @foreach(session('import_errors') as $error)
                    <li class="flex items-start">
                        <span class="mr-2">•</span>
                        <span>{{ $error }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                <form action="{{ route('stockPosition.processImport') }}" method="POST" enctype="multipart/form-data" id="import-form">
                    @csrf

                    <div class="mb-6">
                        <label for="file" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Выберите файл Excel
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-900 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500" id="drop-zone">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Нажмите для загрузки</span> или перетащите файл сюда
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Excel файлы (.xlsx, .xls) до 10MB</p>
                                </div>
                                <input type="file" id="file" name="file" accept=".xlsx,.xls" class="hidden" required>
                            </label>
                        </div>
                        <div id="file-name" class="mt-2 text-sm text-gray-600 dark:text-gray-400 hidden"></div>
                        @error('file')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <h3 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">Требования к формату файла:</h3>
                        <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <li>• Файл должен быть в формате Excel (.xlsx или .xls)</li>
                            <li>• Первая строка должна содержать заголовки колонок</li>
                            <li>• Обязательные колонки: ID, Тип, Длина (см), Ширина (см), Толщина (см), Вес (кг), Количество, Номер поддона</li>
                            <li>• Опциональные колонки: Вид полировки, Вид камня</li>
                            <li>• Если тип, вид полировки или вид камня не существует, он будет создан автоматически</li>
                            <li>• Если поддон с указанным номером не существует, он будет создан автоматически</li>
                            <li>• Позиции с существующими ID будут обновлены, новые - созданы</li>
                        </ul>
                    </div>

                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-2">Пример структуры файла:</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs border-collapse border border-gray-300 dark:border-gray-600">
                                <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700">
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">ID</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Тип</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Длина (см)</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Ширина (см)</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Толщина (см)</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Вес (кг)</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Количество</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Вид полировки</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Вид камня</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-2 py-1 text-left">Номер поддона</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">15</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">Подставка</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">60</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">20</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">12</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">1336,32</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">29</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">5 сторон</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">Габбро-диаббаз</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-2 py-1">P-001</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            ← Вернуться к списку
                        </a>
                        <button type="submit" id="submit-btn" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12a4 4 0 11-8 0V6a4 4 0 118 0v7m-8-4l8 8m0 0l8-8m-8 8V6"></path>
                            </svg>
                            <span id="submit-text">Начать импорт</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('file');
            const dropZone = document.getElementById('drop-zone');
            const fileName = document.getElementById('file-name');
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const form = document.getElementById('import-form');

            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    fileName.textContent = `Выбран файл: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                    fileName.classList.remove('hidden');
                }
            });

            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropZone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/30');
                
                if (e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if (file.name.endsWith('.xlsx') || file.name.endsWith('.xls')) {
                        fileInput.files = e.dataTransfer.files;
                        fileName.textContent = `Выбран файл: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                        fileName.classList.remove('hidden');
                    } else {
                        alert('Пожалуйста, выберите файл Excel (.xlsx или .xls)');
                    }
                }
            });

            form.addEventListener('submit', function(e) {
                if (!fileInput.files.length) {
                    e.preventDefault();
                    alert('Пожалуйста, выберите файл для импорта');
                    return;
                }

                submitBtn.disabled = true;
                submitText.textContent = 'Импорт...';
            });
        });
    </script>
    @endpush
</x-app-layout>
