<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 sm:gap-0">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center sm:text-left">
                {{ __('Редактирование позиции #') }}{{ $stockPosition->id }}
            </h2>
            <div class="w-full sm:w-auto">
                <a href="{{ route('stockPosition.show', $stockPosition->id) }}" 
                   class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('stockPosition.update', $stockPosition->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 sm:gap-6 mb-4 sm:mb-6 grid-cols-1 md:grid-cols-2">
                            <div>
                                <label for="pallet_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Номер поддона</label>
                                <input type="text" id="pallet_number" name="pallet_number" value="{{ $stockPosition->pallet_number }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Введите номер поддона" />
                            </div>
                            <div>
                                <label for="product_type_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид продукции</label>
                                <select id="product_type_id" name="product_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                    <option value="">Выберите вид продукции</option>
                                    @foreach($productTypes as $id => $name)
                                        <option value="{{ $id }}" {{ $stockPosition->product_type_id == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Размеры (см)</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div>
                                        <div class="relative">
                                            <input type="number" id="length" name="length" step="0.01" value="{{ $stockPosition->length }}" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                                required placeholder="Длина" />
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">Д</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <input type="number" id="width" name="width" step="0.01" value="{{ $stockPosition->width }}" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                                required placeholder="Ширина" />
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">Ш</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="relative">
                                            <input type="number" id="thickness" name="thickness" step="0.01" value="{{ $stockPosition->thickness }}" 
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
                                <input type="number" id="quantity" name="quantity" value="{{ $stockPosition->quantity }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
                            </div>
                            <div>
                                <label for="polish_type_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Вид полировки</label>
                                <select id="polish_type_id" name="polish_type_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">Выберите вид полировки</option>
                                    @foreach($polishTypes as $key => $value)
                                        <option value="{{ $key }}" {{ $stockPosition->polish_type_id == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Отображение текущего изображения и поле для загрузки нового -->
                        <div class="mb-6">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Изображение</label>
                            
                            @if($stockPosition->image_path)
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Текущее изображение:</p>
                                    <img src="{{ $stockPosition->image_path }}" 
                                         alt="Изображение позиции #{{ $stockPosition->id }}"
                                         class="max-w-xs rounded border border-gray-300 dark:border-gray-600">
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-center w-full">
                                <label for="image" class="flex flex-col items-center justify-center w-full h-48 sm:h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4 text-center">
                                        <svg class="w-8 h-8 mb-3 sm:mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Нажмите чтобы загрузить</span> или перетащите файл</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG или GIF (MAX. 2MB)</p>
                                    </div>
                                    <input id="image" name="image" type="file" class="hidden" />
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-center sm:justify-end">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 