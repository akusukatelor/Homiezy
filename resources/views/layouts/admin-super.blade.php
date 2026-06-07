<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin - Homiezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>

<body class="bg-[#F8FAFC] text-slate-900" x-data="{ sidebarOpen: true }">
    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'w-80' : 'w-20'"
               class="bg-[#0F172A] text-white transition-all duration-300 flex flex-col fixed h-full z-50 shadow-2xl">

            {{-- Logo --}}
            <div class="p-8 flex items-center justify-between">
                <span x-show="sidebarOpen"
                      class="text-2xl font-black italic tracking-tighter text-orange-400">
                    SUPERADMIN.
                </span>
                <button @click="sidebarOpen = !sidebarOpen"
                        class="hover:text-orange-400 transition">
                    <i data-lucide="menu"></i>
                </button>
            </div>

            {{-- Badge role --}}
            <div x-show="sidebarOpen" class="px-8 -mt-4 mb-6">
                <span class="px-3 py-1 bg-orange-500/20 text-orange-400 rounded-full text-[9px] font-black uppercase tracking-widest italic">
                    👑 Super Administrator
                </span>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('superadmin.dashboard') }}"
                   class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition duration-300
                   {{ request()->routeIs('superadmin.dashboard')
                       ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/20'
                       : 'text-slate-400 hover:bg-white/5' }}">
                    <i data-lucide="layout-dashboard"></i>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('superadmin.layanan', ['type' => 'kos']) }}"
                   class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition duration-300
                   {{ request()->routeIs('superadmin.layanan')
                       ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/20'
                       : 'text-slate-400 hover:bg-white/5' }}">
                    <i data-lucide="building-2"></i>
                    <span x-show="sidebarOpen">Kelola Layanan</span>
                </a>

                <a href="{{ route('superadmin.users') }}"
                   class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition duration-300
                   {{ request()->routeIs('superadmin.users')
                       ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/20'
                       : 'text-slate-400 hover:bg-white/5' }}">
                    <i data-lucide="users"></i>
                    <span x-show="sidebarOpen">Kelola User</span>
                </a>

                {{-- Divider --}}
                <div class="pt-4 pb-2 px-4" x-show="sidebarOpen">
                    <p class="text-[9px] font-black text-slate-600 uppercase tracking-widest italic">
                        Navigasi Lain
                    </p>
                </div>

                <a href="{{ route('home') }}"
                   class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition duration-300 text-slate-400 hover:bg-white/5">
                    <i data-lucide="globe"></i>
                    <span x-show="sidebarOpen">Lihat Website</span>
                </a>
            </nav>

            {{-- User info + Logout --}}
            <div class="p-4 border-t border-white/5 space-y-2">
                {{-- User info --}}
                <div x-show="sidebarOpen"
                     class="px-4 py-3 bg-white/5 rounded-2xl flex items-center gap-3">
                    <div class="w-8 h-8 bg-orange-500 rounded-xl flex items-center justify-center font-black text-white text-sm flex-shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-black text-white truncate italic">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[9px] font-bold text-orange-400 uppercase tracking-widest">
                            Super Admin
                        </p>
                    </div>
                </div>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl text-red-400 hover:bg-red-500/10 transition font-bold italic">
                        <i data-lucide="log-out"></i>
                        <span x-show="sidebarOpen">Keluar Panel</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main :class="sidebarOpen ? 'ml-80' : 'ml-20'"
              class="flex-1 transition-all duration-300">

            {{-- Top Header --}}
            <header class="bg-white border-b border-slate-100 py-6 px-10 flex justify-between items-center sticky top-0 z-40">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        Homiezy Super Admin
                    </p>
                    <h2 class="font-black text-slate-800 italic">
                        @yield('page_title', 'Dashboard')
                    </h2>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-black text-slate-800 leading-none italic">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mt-1 italic">
                            👑 Super Admin
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-orange-100 rounded-full border-2 border-orange-200 shadow-sm flex items-center justify-center font-black text-orange-500">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <div class="p-10">
                @yield('admin_content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
