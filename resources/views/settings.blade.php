<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Уведомление о контактах -->
            <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            По всем вопросам обращаться в Телеграм: <a href="https://t.me/yamamotto" class="font-medium text-blue-600 dark:text-blue-400 hover:underline" target="_blank">@yamamotto</a>
                        </p>
                    </div>
                </div>
            </div>


            @php($canEdit = auth()->user() && method_exists(auth()->user(), 'canEdit') ? auth()->user()->canEdit() : false)
            @if(!$canEdit)
            <div class="mb-4 p-4 text-sm text-amber-700 bg-amber-100 rounded-lg dark:bg-amber-200 dark:text-amber-800" role="alert">
                Вы находитесь в режиме только просмотра. Изменение настроек недоступно.
            </div>
            @endif

            @if(auth()->user() && method_exists(auth()->user(), 'canEdit') && auth()->user()->canEdit())
            <!-- Пользователи -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Пользователи</h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Создание пользователя (пароль генерируется автоматически)</p>
                            <form action="{{ route('settings.users.create') }}" method="POST" class="mt-3 grid grid-cols-1 md:grid-cols-5 gap-3">
                                @csrf
                                <input type="text" name="name" placeholder="Имя (необязательно)" class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                <input type="text" name="username" placeholder="Логин (a-z, 0-9, -, _)" class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                <input type="email" name="email" placeholder="Email (необязательно)" class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                <select name="role" class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                    <option value="">Полный доступ</option>
                                    <option value="viewer">Только просмотр</option>
                                </select>
                                <button type="submit" class="flex items-center justify-center h-10 px-3 py-0 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">Создать</button>
                            </form>
                            
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Список пользователей</p>
                            <div id="gp-banner" class="hidden mb-3 p-3 text-sm text-indigo-700 bg-indigo-100 rounded-lg dark:bg-indigo-200 dark:text-indigo-800" role="alert">
                                <div class="flex items-center justify-between gap-3 flex-wrap">
                                    <div class="gp-text font-mono"></div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="gp-copy inline-flex items-center px-3 h-8 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white">Копировать</button>
                                        <button type="button" class="gp-clear inline-flex items-center px-3 h-8 rounded-md bg-gray-600 hover:bg-gray-700 text-white">Скрыть</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Desktop table -->
                            <div class="hidden md:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Email</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Логин</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Имя</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Роль</th>
                                            <th class="px-4 py-2 text-left font-medium text-gray-700 dark:text-gray-300">Пароль (новый)</th>
                                            <th class="px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($users as $u)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $u->email }}</td>
                                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $u->username }}</td>
                                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">{{ $u->name }}</td>
                                            <td class="px-4 py-2">
                                                <form action="{{ route('settings.users.update-role', $u) }}" method="POST" class="flex items-center gap-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="role" class="focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                                        <option value="" {{ $u->role ? '' : 'selected' }}>Полный доступ</option>
                                                        <option value="viewer" {{ $u->role === 'viewer' ? 'selected' : '' }}>Только просмотр</option>
                                                    </select>
                                                    <button type="submit" class="flex items-center justify-center h-9 px-3 bg-blue-500 hover:bg-blue-600 text-white rounded-md">Сохранить</button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                                <span class="pw-cell font-mono bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded" data-user-id="{{ $u->id }}" data-email="{{ $u->email }}" data-username="{{ $u->username }}">
                                                    @if(session('generated_password_email') === $u->email)
                                                        {{ session('generated_password') }}
                                                    @endif
                                                </span>
                                                <button type="button" class="copy-btn hidden ml-2 inline-flex items-center px-2 h-7 rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-xs" data-user-id="{{ $u->id }}" data-email="{{ $u->email }}" data-username="{{ $u->username }}">Копировать</button>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex items-center gap-2">
                                                    <form action="{{ route('settings.users.reset-password', $u) }}" method="POST" onsubmit="return confirm('Сбросить пароль пользователю {{ $u->email }}? Новый пароль появится в таблице.');">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 h-9 rounded-md bg-amber-600 hover:bg-amber-700 text-white text-sm">Сбросить пароль</button>
                                                    </form>
                                                    @if(auth()->id() !== $u->id)
                                                    <form action="{{ route('settings.users.delete', $u) }}" method="POST" onsubmit="return confirm('Удалить пользователя {{ $u->email }}? Это действие необратимо.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-3 h-9 rounded-md bg-red-600 hover:bg-red-700 text-white text-sm">Удалить</button>
                                                    </form>
                                                    @else
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">Нельзя удалить себя</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile list -->
                            <div class="md:hidden space-y-3">
                                @foreach($users as $u)
                                <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/40">
                                    @if(session('generated_password_email') === $u->email)
                                    <div class="mb-2">
                                        <div class="text-xs uppercase text-gray-500 dark:text-gray-400">Пароль (новый)</div>
                                        <div class="text-sm font-mono text-gray-900 dark:text-gray-100 break-all bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">{{ session('generated_password') }}</div>
                                    </div>
                                    @endif
                                    <div class="mb-2 gp-mobile hidden" data-user-id="{{ $u->id }}" data-email="{{ $u->email }}" data-username="{{ $u->username }}">
                                        <div class="text-xs uppercase text-gray-500 dark:text-gray-400">Пароль (новый)</div>
                                        <div class="flex items-center gap-2">
                                            <div class="pw-mobile text-sm font-mono text-gray-900 dark:text-gray-100 break-all bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded"></div>
                                            <button type="button" class="copy-btn-mobile inline-flex items-center px-2 h-8 rounded bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 text-xs" data-user-id="{{ $u->id }}" data-email="{{ $u->email }}" data-username="{{ $u->username }}">Копировать</button>
                                        </div>
                                    </div>
                                    <div class="text-xs uppercase text-gray-500 dark:text-gray-400">Email</div>
                                    <div class="text-sm text-gray-900 dark:text-gray-100 break-all">{{ $u->email }}</div>
                                    <div class="mt-2 text-xs uppercase text-gray-500 dark:text-gray-400">Имя</div>
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $u->name }}</div>
                                    <div class="mt-3">
                                        <form action="{{ route('settings.users.update-role', $u) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                                <option value="" {{ $u->role ? '' : 'selected' }}>Полный доступ</option>
                                                <option value="viewer" {{ $u->role === 'viewer' ? 'selected' : '' }}>Только просмотр</option>
                                            </select>
                                            <button type="submit" class="w-full sm:w-auto flex items-center justify-center h-10 px-3 bg-blue-500 hover:bg-blue-600 text-white rounded-md">Сохранить</button>
                                        </form>
                                        <form action="{{ route('settings.users.reset-password', $u) }}" method="POST" class="mt-2" onsubmit="return confirm('Сбросить пароль пользователю {{ $u->email }}? Новый пароль появится в карточке.');">
                                            @csrf
                                            <button type="submit" class="w-full sm:w-auto flex items-center justify-center h-10 px-3 bg-amber-600 hover:bg-amber-700 text-white rounded-md">Сбросить пароль</button>
                                        </form>
                                        @if(auth()->id() !== $u->id)
                                        <form action="{{ route('settings.users.delete', $u) }}" method="POST" class="mt-2" onsubmit="return confirm('Удалить пользователя {{ $u->email }}? Это действие необратимо.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full sm:w-auto flex items-center justify-center h-10 px-3 bg-red-600 hover:bg-red-700 text-white rounded-md">Удалить</button>
                                        </form>
                                        @else
                                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Нельзя удалить себя</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(session('generated_password'))
            <div id="gp-seed" data-gp="{{ session('generated_password') }}" data-ge="{{ session('generated_password_email') }}" data-guid="{{ session('generated_password_user_id') }}" data-gu="{{ session('generated_password_username') }}" class="hidden"></div>
            @endif
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    try {
                        // Seed from server (one-time after create)
                        var seed = document.getElementById('gp-seed');
                        if (seed) {
                            var seedPw = seed.getAttribute('data-gp');
                            var seedEmail = seed.getAttribute('data-ge');
                            var seedUserId = seed.getAttribute('data-guid');
                            var seedUsername = seed.getAttribute('data-gu');
                            if (seedPw) {
                                localStorage.setItem('generated_password', seedPw);
                                if (seedEmail) localStorage.setItem('generated_password_email', seedEmail);
                                if (seedUserId) localStorage.setItem('generated_password_user_id', seedUserId);
                                if (seedUsername) localStorage.setItem('generated_password_username', seedUsername);
                            }
                        }
                        var gp = localStorage.getItem('generated_password');
                        var ge = localStorage.getItem('generated_password_email');
                        var guid = localStorage.getItem('generated_password_user_id');
                        var gu = localStorage.getItem('generated_password_username');
                        if (gp) {
                            // Fill table cells
                            document.querySelectorAll('.pw-cell').forEach(function(el){
                                var match = (guid && el.dataset.userId === guid) || (el.dataset.email && el.dataset.email === ge) || (gu && el.dataset.username === gu);
                                if (match) {
                                    el.textContent = gp;
                                    el.classList.remove('hidden');
                                    var selector = '.copy-btn';
                                    var btn = el.parentElement.querySelector(selector);
                                    // ensure it's the same row button
                                    if (btn) btn.classList.remove('hidden');
                                }
                            });
                            // Fill mobile cards
                            document.querySelectorAll('.gp-mobile').forEach(function(card){
                                var match = (guid && card.dataset.userId === guid) || (card.dataset.email && card.dataset.email === ge) || (gu && card.dataset.username === gu);
                                if (match) {
                                    card.classList.remove('hidden');
                                    var box = card.querySelector('.pw-mobile');
                                    if (box) box.textContent = gp;
                                    var mbtn = card.querySelector('.copy-btn-mobile');
                                    if (mbtn) mbtn.classList.remove('hidden');
                                }
                            });
                            // Show banner
                            var banner = document.getElementById('gp-banner');
                            if (banner) {
                                banner.classList.remove('hidden');
                                var text = banner.querySelector('.gp-text');
                                if (text) {
                                    var who = ge || (gu ? ('логин ' + gu) : (guid ? ('ID ' + guid) : 'новый пользователь'));
                                    text.textContent = 'Пароль для ' + who + ': ' + gp;
                                }
                                banner.querySelectorAll('.gp-clear').forEach(function(btn){
                                    btn.addEventListener('click', function(){
                                        localStorage.removeItem('generated_password');
                                        localStorage.removeItem('generated_password_email');
                                        localStorage.removeItem('generated_password_user_id');
                                        localStorage.removeItem('generated_password_username');
                                        banner.classList.add('hidden');
                                        // Clear cells
                                        document.querySelectorAll('.pw-cell').forEach(function(el){
                                            if ((guid && el.dataset.userId === guid) || (el.dataset.email === ge) || (gu && el.dataset.username === gu)) el.textContent = '';
                                        });
                                        document.querySelectorAll('.gp-mobile').forEach(function(card){
                                            if ((guid && card.dataset.userId === guid) || (card.dataset.email === ge) || (gu && card.dataset.username === gu)) card.classList.add('hidden');
                                        });
                                        document.querySelectorAll('.copy-btn').forEach(function(b){ b.classList.add('hidden'); });
                                    });
                                });
                                banner.querySelectorAll('.gp-copy').forEach(function(btn){
                                    btn.addEventListener('click', function(){ copyToClipboard(gp, btn); });
                                });
                            }
                        }
                    } catch (e) { /* ignore */ }
                });

                function copyToClipboard(text, el){
                    var original = el && el.textContent;
                    var setDone = function(){ if(el){ el.textContent = 'Скопировано'; setTimeout(function(){ el.textContent = original; }, 1500); } };
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(setDone).catch(function(){
                            fallbackCopy(text); setDone();
                        });
                    } else { fallbackCopy(text); setDone(); }
                }
                function fallbackCopy(text){
                    var ta = document.createElement('textarea');
                    ta.value = text; document.body.appendChild(ta); ta.select();
                    try { document.execCommand('copy'); } catch(e) {}
                    document.body.removeChild(ta);
                }

                // Wire table/mobile copy buttons
                document.querySelectorAll('.copy-btn').forEach(function(btn){
                    btn.addEventListener('click', function(){
                        var email = btn.getAttribute('data-email');
                        var uid = btn.getAttribute('data-user-id');
                        var uname = btn.getAttribute('data-username');
                        var gp = localStorage.getItem('generated_password');
                        var ge = localStorage.getItem('generated_password_email');
                        var guid = localStorage.getItem('generated_password_user_id');
                        var gu = localStorage.getItem('generated_password_username');
                        var ok = (gp && ((guid && uid && guid === uid) || (ge && email && ge === email) || (gu && uname && gu === uname)));
                        if (ok) copyToClipboard(gp, btn);
                    });
                });
                document.querySelectorAll('.copy-btn-mobile').forEach(function(btn){
                    btn.addEventListener('click', function(){
                        var email = btn.getAttribute('data-email');
                        var uid = btn.getAttribute('data-user-id');
                        var uname = btn.getAttribute('data-username');
                        var gp = localStorage.getItem('generated_password');
                        var ge = localStorage.getItem('generated_password_email');
                        var guid = localStorage.getItem('generated_password_user_id');
                        var gu = localStorage.getItem('generated_password_username');
                        var ok = (gp && ((guid && uid && guid === uid) || (ge && email && ge === email) || (gu && uname && gu === uname)));
                        if (ok) copyToClipboard(gp, btn);
                    });
                });
            </script>
            @if(!$canEdit)
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    try {
                        // Заблокировать все формы настроек (создание/редактирование/удаление)
                        document.querySelectorAll('form[action*="/settings"]').forEach(function(f){
                            f.addEventListener('submit', function(e){ e.preventDefault(); });
                            f.querySelectorAll('input, select, textarea, button').forEach(function(el){
                                el.disabled = true;
                                el.classList.add('opacity-60','cursor-not-allowed');
                            });
                        });
                    } catch (e) { /* ignore */ }
                });
            </script>
            @endif
            <!-- Сообщения об успехе или ошибке -->
            @if(session('success'))
            <div id="success-alert" class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            

            @if(session('error'))
            <div id="error-alert" class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Настройки</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Управление настройками приложения</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Виды полировки</h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Управление списком видов полировки для позиций</p>

                        <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Для изменения вида полировки отредактируйте название и/или статус активности, затем нажмите кнопку "Сохранить" рядом с соответствующим видом полировки.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущие виды полировки</label>

                                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <div class="space-y-4" id="polish-types-container">
                                        @forelse($polishTypes as $polishType)
                                        <div class="polish-type-item border-b border-gray-200 dark:border-gray-600 pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                <form action="{{ route('settings.polish-types.update', $polishType) }}" method="POST" class="flex-grow">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                        <div class="w-full sm:flex-1">
                                                            <input type="text" name="name" value="{{ $polishType->name }}" placeholder="Название"
                                                                class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                                        </div>
                                                        <div class="flex items-center mt-2 sm:mt-0">
                                                            <!-- Скрытое поле для гарантии передачи значения -->
                                                            <input type="hidden" name="is_active" value="0">
                                                            <div class="flex items-center">
                                                                <input id="is_active_{{ $polishType->id }}" name="is_active" type="checkbox" value="1" {{ $polishType->is_active ? 'checked' : '' }}
                                                                    class="hidden">
                                                                <label for="is_active_{{ $polishType->id }}" class="checkbox-label flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-md bg-blue-100 dark:bg-blue-900 cursor-pointer {{ $polishType->is_active ? 'bg-blue-500 dark:bg-blue-600 text-white' : 'text-blue-500 dark:text-blue-400' }}">
                                                                    <svg class="w-5 h-5 {{ $polishType->is_active ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </label>
                                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Активен</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-end mt-3 sm:mt-0">
                                                            <div class="flex space-x-2">
                                                                <button type="submit" class="flex items-center justify-center h-10 px-2 py-0 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                                    </svg>
                                                                    <span>Сохранить</span>
                                                                </button>
                                                                <button type="button" onclick="document.getElementById('delete-form-polish-{{ $polishType->id }}').submit();" class="flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-md">
                                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <form id="delete-form-polish-{{ $polishType->id }}" action="{{ route('settings.polish-types.delete', $polishType) }}" method="POST" class="hidden" onsubmit="return confirm('Вы уверены, что хотите удалить этот вид полировки?');">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                        @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Нет добавленных видов полировки</p>
                                        @endforelse
                                    </div>

                                    <form action="{{ route('settings.polish-types.add') }}" method="POST" class="mt-5 flex flex-col sm:flex-row items-center gap-3">
                                        @csrf
                                        <div class="w-full sm:flex-1">
                                            <input type="text" name="name" placeholder="Название" required
                                                class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                        </div>
                                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center h-10 px-3 py-0 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Добавить</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Виды продукции</h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Управление списком видов продукции для позиций</p>

                        <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Для изменения вида продукции отредактируйте название и/или статус активности, затем нажмите кнопку "Сохранить" рядом с соответствующим видом продукции.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущие виды продукции</label>

                                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <div class="space-y-4" id="product-types-container">
                                        @forelse($productTypes as $productType)
                                        <div class="product-type-item border-b border-gray-200 dark:border-gray-600 pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                <form action="{{ route('settings.product-types.update', $productType) }}" method="POST" class="flex-grow">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                        <div class="w-full sm:flex-1">
                                                            <input type="text" name="name" value="{{ $productType->name }}" placeholder="Название"
                                                                class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                                        </div>
                                                        <div class="flex items-center mt-2 sm:mt-0">
                                                            <!-- Скрытое поле для гарантии передачи значения -->
                                                            <input type="hidden" name="is_active" value="0">
                                                            <div class="flex items-center">
                                                                <input id="is_active_product_{{ $productType->id }}" name="is_active" type="checkbox" value="1" {{ $productType->is_active ? 'checked' : '' }}
                                                                    class="hidden">
                                                                <label for="is_active_product_{{ $productType->id }}" class="checkbox-label flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-md bg-blue-100 dark:bg-blue-900 cursor-pointer {{ $productType->is_active ? 'bg-blue-500 dark:bg-blue-600 text-white' : 'text-blue-500 dark:text-blue-400' }}">
                                                                    <svg class="w-5 h-5 {{ $productType->is_active ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </label>
                                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Активен</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-end mt-3 sm:mt-0">
                                                            <div class="flex space-x-2">
                                                                <button type="submit" class="flex items-center justify-center h-10 px-2 py-0 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                                    </svg>
                                                                    <span>Сохранить</span>
                                                                </button>
                                                                <button type="button" onclick="document.getElementById('delete-form-product-{{ $productType->id }}').submit();" class="flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-md">
                                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <form id="delete-form-product-{{ $productType->id }}" action="{{ route('settings.product-types.delete', $productType) }}" method="POST" class="hidden" onsubmit="return confirm('Вы уверены, что хотите удалить этот вид продукции?');">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                        @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Нет добавленных видов продукции</p>
                                        @endforelse
                                    </div>

                                    <form action="{{ route('settings.product-types.add') }}" method="POST" class="mt-5 flex flex-col sm:flex-row items-center gap-3">
                                        @csrf
                                        <div class="w-full sm:flex-1">
                                            <input type="text" name="name" placeholder="Название" required
                                                class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                        </div>
                                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center h-10 px-3 py-0 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Добавить</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Виды камня</h3>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Управление списком видов камня для позиций</p>

                        <div class="bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Для изменения вида камня отредактируйте название и/или статус активности, затем нажмите кнопку "Сохранить" рядом с соответствующим видом камня.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Текущие виды камня</label>

                                <div class="bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <div class="space-y-4" id="stone-types-container">
                                        @forelse($stoneTypes as $stoneType)
                                        <div class="stone-type-item border-b border-gray-200 dark:border-gray-600 pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                <form action="{{ route('settings.stone-types.update', $stoneType) }}" method="POST" class="flex-grow">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                        <div class="w-full sm:flex-1">
                                                            <input type="text" name="name" value="{{ $stoneType->name }}" placeholder="Название"
                                                                class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                                        </div>
                                                        <div class="flex items-center mt-2 sm:mt-0">
                                                            <!-- Скрытое поле для гарантии передачи значения -->
                                                            <input type="hidden" name="is_active" value="0">
                                                            <div class="flex items-center">
                                                                <input id="is_active_stone_{{ $stoneType->id }}" name="is_active" type="checkbox" value="1" {{ $stoneType->is_active ? 'checked' : '' }}
                                                                    class="hidden">
                                                                <label for="is_active_stone_{{ $stoneType->id }}" class="checkbox-label flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-md bg-blue-100 dark:bg-blue-900 cursor-pointer {{ $stoneType->is_active ? 'bg-blue-500 dark:bg-blue-600 text-white' : 'text-blue-500 dark:text-blue-400' }}">
                                                                    <svg class="w-5 h-5 {{ $stoneType->is_active ? '' : 'hidden' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </label>
                                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Активен</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center justify-end mt-3 sm:mt-0">
                                                            <div class="flex space-x-2">
                                                                <button type="submit" class="flex items-center justify-center h-10 px-2 py-0 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                                    </svg>
                                                                    <span>Сохранить</span>
                                                                </button>
                                                                <button type="button" onclick="document.getElementById('delete-form-stone-{{ $stoneType->id }}').submit();" class="flex items-center justify-center w-10 h-10 bg-red-500 hover:bg-red-600 text-white rounded-md">
                                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <form id="delete-form-stone-{{ $stoneType->id }}" action="{{ route('settings.stone-types.delete', $stoneType) }}" method="POST" class="hidden" onsubmit="return confirm('Вы уверены, что хотите удалить этот вид камня?');">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                        @empty
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Нет добавленных видов камня</p>
                                        @endforelse
                                    </div>

                                    <form action="{{ route('settings.stone-types.add') }}" method="POST" class="mt-5 flex flex-col sm:flex-row items-center gap-3">
                                        @csrf
                                        <div class="w-full sm:flex-1">
                                            <input type="text" name="name" placeholder="Название" required
                                                class="w-full focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2.5">
                                        </div>
                                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center h-10 px-3 py-0 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Добавить</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

            // Обработка клика по метке чекбокса для переключения активности
            document.querySelectorAll('.checkbox-label').forEach(function(label) {
                label.addEventListener('click', function(e) {
                    // Предотвращаем стандартное поведение метки и всплытие события
                    e.preventDefault();
                    e.stopPropagation();

                    // Находим связанный чекбокс по атрибуту for
                    const checkboxId = this.getAttribute('for');
                    const checkbox = document.getElementById(checkboxId);

                    if (checkbox) {
                        // Переключаем состояние чекбокса
                        checkbox.checked = !checkbox.checked;

                        // Находим иконку внутри метки
                        const checkIcon = this.querySelector('svg');

                        // Обновляем стиль метки и видимость иконки
                        if (checkbox.checked) {
                            this.classList.add('bg-blue-500', 'dark:bg-blue-600', 'text-white');
                            this.classList.remove('text-blue-500', 'dark:text-blue-400');
                            if (checkIcon) checkIcon.classList.remove('hidden');
                        } else {
                            this.classList.remove('bg-blue-500', 'dark:bg-blue-600', 'text-white');
                            this.classList.add('text-blue-500', 'dark:text-blue-400');
                            if (checkIcon) checkIcon.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>