<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra Panel - Homiezy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
{{-- PINDAHKAN tab ke x-data paling atas --}}
<body class="bg-[#F8FAFC] text-slate-900" x-data="{ sidebarOpen: true, tab: 'overview' }">
    <div class="flex min-h-screen">
        
        {{-- SIDEBAR --}}
        <aside :class="sidebarOpen ? 'w-80' : 'w-20'" class="bg-[#0F172A] text-white transition-all duration-300 flex flex-col fixed h-full z-50 shadow-2xl">
            <div class="p-8 flex items-center justify-between">
                <span x-show="sidebarOpen" class="text-2xl font-black italic tracking-tighter text-[#0095FF]">HOMIEZY.</span>
                <button @click="sidebarOpen = !sidebarOpen" class="hover:text-[#0095FF] transition">
                    <i data-lucide="menu"></i>
                </button>
            </div>

            <nav class="flex-1 px-4 space-y-2 mt-4">
                {{-- Hapus x-data di sini agar menggunakan scope parent --}}
                <button @click="tab = 'overview'" 
                        :class="tab === 'overview' ? 'bg-[#0095FF] text-white' : 'text-slate-400 hover:bg-white/5'" 
                        class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition duration-300">
                    <i data-lucide="layout-dashboard"></i>
                    <span x-show="sidebarOpen">Overview</span>
                </button>
                
                <button @click="tab = 'orders'" 
                        :class="tab === 'orders' ? 'bg-[#0095FF] text-white' : 'text-slate-400 hover:bg-white/5'" 
                        class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition duration-300">
                    <i data-lucide="shopping-bag"></i>
                    <span x-show="sidebarOpen">Pesanan Masuk</span>
                </button>
                
                <button @click="tab = 'manage'" 
                        :class="tab === 'manage' ? 'bg-[#0095FF] text-white' : 'text-slate-400 hover:bg-white/5'" 
                        class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl font-bold transition duration-300">
                    <i data-lucide="settings"></i>
                    <span x-show="sidebarOpen">Kelola Bisnis</span>
                </button>
            </nav>

            <div class="p-4 border-t border-white/5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-4 py-4 rounded-2xl text-red-400 hover:bg-red-500/10 transition font-bold italic">
                        <i data-lucide="log-out"></i>
                        <span x-show="sidebarOpen">Keluar Panel</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main :class="sidebarOpen ? 'ml-80' : 'ml-20'" class="flex-1 transition-all duration-300">
            {{-- Top Header --}}
            <header class="bg-white border-b border-slate-100 py-6 px-10 flex justify-between items-center sticky top-0 z-40">
                <h2 class="font-bold text-slate-400">
                    Panel Mitra / 
                    <span class="text-slate-800 italic" x-text="tab === 'overview' ? 'Overview' : (tab === 'orders' ? 'Pesanan Masuk' : 'Kelola Bisnis')"></span>
                </h2>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <p class="text-sm font-black text-slate-800 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mt-1 italic">Status: Aktif</p>
                    </div>
                    <div class="w-10 h-10 bg-slate-100 rounded-full border-2 border-white shadow-sm flex items-center justify-center font-black text-[#0095FF]">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

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