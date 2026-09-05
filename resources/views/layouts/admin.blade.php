<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - VitaminD</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-slate-800" x-data="{ sidebarOpen: false, logoutModalOpen: false }">
    <div class="min-h-full flex">

        <!-- Sidebar (Mobile & Desktop) -->
        @include('layouts.sidebar')

        <!-- Main Wrapper -->
        <div class="flex-1 lg:pl-64 flex flex-col min-w-0">

            <!-- Top Navbar -->
            <header
                class="sticky top-0 z-20 h-16 bg-white border-b border-slate-200/80 shadow-xs flex items-center justify-between px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = true"
                        class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-base sm:text-lg font-bold text-slate-900">@yield('header_title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-500 hidden sm:block">@yield('header_subtitle', 'Sistem Manajemen & Skrining Resiko VitaminD')</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Date badge -->
                    <div
                        class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ now()->translatedFormat('l, d M Y') }}</span>
                    </div>

                    <!-- Logout Button Trigger -->
                    <button type="button" @click="logoutModalOpen = true"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-colors text-xs font-semibold cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <!-- Flash Alerts -->
                @if (session('success'))
                    <div
                        class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm animate-fade-in">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-6 flex items-center gap-3 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="mt-auto py-4 px-6 border-t border-slate-200/80 text-center text-xs text-slate-400 bg-white">
                &copy; {{ date('Y') }} VitaminD. Hak Cipta Dilindungi. Sistem Informasi Skrining Resiko Kesehatan.
            </footer>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div x-show="logoutModalOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4"
        style="display: none;" @keydown.escape.window="logoutModalOpen = false">

        <div x-show="logoutModalOpen" x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            @click.away="logoutModalOpen = false"
            class="relative bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 text-center">

            <!-- Icon -->
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 mx-auto flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </div>

            <!-- Title & Description -->
            <h3 class="text-lg font-bold text-slate-900">Konfirmasi Keluar</h3>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Apakah Anda yakin ingin keluar dari sistem Administrator?
            </p>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center gap-3">
                <button type="button" @click="logoutModalOpen = false"
                    class="flex-1 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition-colors cursor-pointer">
                    Batal
                </button>

                <form action="{{ route('admin.logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full py-2.5 px-4 bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
