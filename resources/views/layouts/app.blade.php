<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Ruda Gamestore') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b from-blue-100 to-white min-h-screen font-sans text-blue-900">
    <div id="app">
        <!-- Navbar -->
        <nav class="shadow-sm border-b border-blue-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo/Nama Aplikasi -->
                    <a href="{{ url('/') }}" class="flex items-center text-2xl font-bold text-blue-800 hover:text-blue-900 transition-colors">
                        <img src="{{ asset('images/game-store-logo-design-template-260nw-2465937507-removebg-preview.png') }}" alt="Ruda Gamestore Logo" class="h-8 w-15 mr-2">
                        Ruda Gamestore
                    </a>

                    <!-- Tab Navigasi -->
                    <div class="relative">
                        <div class="flex space-x-6">
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('admin.games.index') }}" 
                                   class="{{ request()->routeIs('admin.games.index') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Jelajahi Game Admin">
                                    Jelajahi
                                </a>
                                <a href="{{ route('admin.transactions.index') }}" 
                                   class="{{ request()->routeIs('admin.transactions.index') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Transaksi Admin">
                                    Transaksi
                                </a>
                                <a href="{{ route('admin.games.create') }}" 
                                   class="{{ request()->routeIs('admin.games.create') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Upload Game Admin">
                                    Upload Game
                                </a>
                                <a href="{{ route('admin.categories.index') }}" 
                                   class="{{ request()->routeIs('admin.categories.index') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Kategori Admin">
                                    Kategori
                                </a>
                            @else
                                <a href="{{ route('user.games.index') }}" 
                                   class="{{ request()->routeIs('user.games.index') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Jelajahi Game">
                                    Jelajahi
                                </a>
                                <a href="{{ route('transactions.index') }}" 
                                   class="{{ request()->routeIs('transactions.index') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Transaksi">
                                    Transaksi
                                </a>
                                <a href="{{ route('user.games.create') }}" 
                                   class="{{ request()->routeIs('user.games.create') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Tambah Game">
                                    Upolad Game
                                </a>
                                <a href="{{ route('cart.index') }}" 
                                   class="{{ request()->routeIs('cart.index') ? 'text-blue-800' : 'text-blue-600 hover:text-blue-800' }} px-4 py-2 font-medium transition-colors"
                                   aria-label="Halaman Keranjang">
                                    Keranjang
                                </a>
                            @endif
                        </div>
                        <div class="absolute bottom-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 ease-in-out" id="underline"></div>
                    </div>

                    <!-- Menu untuk layar kecil -->
                    <div class="flex md:hidden">
                        <button type="button" class="text-blue-800 hover:text-blue-900 focus:outline-none focus:text-blue-900" id="menu-toggle">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Menu utama untuk layar besar -->
                    <div class="hidden md:flex items-center space-x-6">
                        @guest
                            <div class="space-x-4">
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" class="text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                        {{ __('Login') }}
                                    </a>
                                @endif
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                                        {{ __('Register') }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="relative inline-block text-left">
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-blue-600 bg-blue-800 px-4 py-2 text-sm font-medium text-white hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="menu-button" aria-expanded="false" aria-haspopup="true" onclick="document.getElementById('dropdown-menu').classList.toggle('hidden')">
                                    {{ Auth::user()->name }}
                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="dropdown-menu" class="hidden origin-top-right absolute right-0 mt-2 w-36 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-20">
                                    <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-blue-700 hover:bg-blue-100" role="menuitem" tabindex="-1" id="menu-item-0">
                                                {{ __('Logout') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>

                <!-- Menu mobile (dropdown) -->
                <div id="mobile-menu" class="hidden md:hidden">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        @guest
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    {{ __('Login') }}
                                </a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    {{ __('Register') }}
                                </a>
                            @endif
                        @else
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('admin.games.index') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Jelajahi
                                </a>
                                <a href="{{ route('admin.transactions.index') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Transaksi
                                </a>
                                <a href="{{ route('admin.games.create') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Upload Game
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Kategori
                                </a>
                            @else
                                <a href="{{ route('user.games.index') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Jelajahi
                                </a>
                                <a href="{{ route('transactions.index') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Transaksi
                                </a>
                                <a href="{{ route('user.games.create') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Tambah Game
                                </a>
                                <a href="{{ route('cart.index') }}" class="block text-blue-800 hover:text-blue-900 px-3 py-2 rounded-md text-sm font-medium">
                                    Keranjang
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 text-blue-800 hover:text-blue-900 rounded-md text-sm font-medium">
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-6">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
    @stack('scripts')
    <script>
        // Toggle menu mobile
        document.getElementById('menu-toggle').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Tutup dropdown jika klik di luar
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('dropdown-menu');
            const button = document.getElementById('menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const toggle = document.getElementById('menu-toggle');
            if (!button.contains(e.target) && !toggle.contains(e.target)) {
                menu.classList.add('hidden');
                mobileMenu.classList.add('hidden');
            }
        });

        // Script untuk tab navigasi
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('nav .flex + .relative a');
            const underline = document.getElementById('underline');
            let activeTab = document.querySelector('nav .flex + .relative a.text-blue-800');

            // Debug untuk memastikan elemen terdeteksi
            console.log('Tabs:', tabs);
            console.log('Underline:', underline);
            console.log('Active Tab:', activeTab);

            // Inisialisasi garis bawah jika ada tab aktif, jika tidak gunakan tab pertama
            if (activeTab && underline) {
                updateUnderline(activeTab);
            } else if (tabs.length > 0 && underline) {
                activeTab = tabs[0];
                updateUnderline(activeTab);
            } else {
                console.log('Initialization failed: No active tab or underline found');
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    if (activeTab) {
                        activeTab.classList.remove('text-blue-800');
                        activeTab.classList.add('text-blue-600');
                    }
                    activeTab = this;
                    activeTab.classList.add('text-blue-800');
                    activeTab.classList.remove('text-blue-600');
                    updateUnderline(activeTab);
                    // Redirect dengan transisi halus
                    window.location.href = href;
                });

                // Event hover untuk menggerakkan garis biru
                tab.addEventListener('mouseover', function() {
                    if (this !== activeTab && underline) {
                        console.log('Hover on:', this.textContent);
                        updateUnderline(this);
                    }
                });

                // Kembali ke tab aktif saat mouseout
                tab.addEventListener('mouseout', function() {
                    if (activeTab && underline) {
                        console.log('Mouse out, returning to:', activeTab.textContent);
                        updateUnderline(activeTab);
                    }
                });
            });

            function updateUnderline(tab) {
                if (tab && underline) {
                    const tabRect = tab.getBoundingClientRect();
                    const containerRect = tab.parentElement.getBoundingClientRect();
                    console.log('Updating underline:', tab.textContent, tabRect, containerRect);
                    underline.style.width = `${tabRect.width}px`;
                    underline.style.transform = `translateX(${tabRect.left - containerRect.left}px)`;
                } else {
                    console.log('Update failed: tab or underline not available');
                }
            }
        });
    </script>
</body>
</html>