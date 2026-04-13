<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homiezy - Feels like home, Anywhere</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Poppins', sans-serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); }
        .glass-search { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="bg-white text-slate-900">
    <nav class="fixed w-full z-[60] glass-nav py-4 border-b border-slate-100" x-data="{ mobileMenuOpen: false }">
    <div class="container mx-auto px-6 flex justify-between items-center">
        <a href="/" class="flex items-center relative z-[70]">
            <img src="{{ asset('images/logo-homiezy.png') }}" alt="Homiezy Logo" class="h-10 md:h-12 w-auto"> 
        </a>
        
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}" 
               class="{{ request()->routeIs('home') ? 'bg-[#0095FF] text-white shadow-lg shadow-blue-200' : 'text-slate-600 hover:text-[#0095FF]' }} px-6 py-2.5 rounded-full font-semibold text-sm flex items-center gap-2 transition transform hover:scale-105">
                <i data-lucide="home" class="w-4 h-4"></i> Beranda
            </a>

            @auth
            <a href="{{ route('dashboard') }}" 
               class="{{ request()->routeIs('dashboard') ? 'bg-[#0095FF] text-white shadow-lg shadow-blue-200' : 'text-slate-600 hover:text-[#0095FF]' }} px-6 py-2.5 rounded-full font-semibold text-sm flex items-center gap-2 transition transform hover:scale-105">
                <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard Saya
            </a>
            @endauth

            <a href="{{ route('mitra') }}" 
               class="{{ request()->routeIs('mitra') ? 'bg-[#0095FF] text-white shadow-lg shadow-blue-200' : 'text-slate-600 hover:text-[#0095FF]' }} px-6 py-2.5 rounded-full font-semibold text-sm flex items-center gap-2 transition transform hover:scale-105">
                <i data-lucide="building-2" class="w-4 h-4"></i> Jadi Mitra
            </a>
        </div>

        <div class="flex items-center gap-4 relative z-[70]">
            <div class="hidden md:flex items-center gap-6">
               @guest
                    <a href="{{ route('login') }}" class="text-slate-600 font-bold text-sm hover:text-[#0095FF] transition">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-[#0095FF] text-white px-8 py-2.5 rounded-full font-bold text-sm hover:bg-blue-600 transition shadow-lg shadow-blue-200">Daftar</a>
                @endguest

                @auth
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Halo, {{ Auth::user()->name }}!</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-500 font-black text-xs uppercase hover:underline">Keluar</button>
                        </form>
                    </div>
                @endauth
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600 hover:text-[#0095FF] transition">
                <i data-lucide="menu" x-show="!mobileMenuOpen" class="w-8 h-8"></i>
                <i data-lucide="x" x-show="mobileMenuOpen" x-cloak class="w-8 h-8"></i>
            </button>
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-y-full opacity-0"
         x-transition:enter-end="translate-y-0 opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-y-0 opacity-100"
         x-transition:leave-end="-translate-y-full opacity-0"
         class="absolute top-0 left-0 w-full bg-white shadow-2xl pt-24 pb-12 px-6 md:hidden z-[55] border-b-4 border-[#0095FF]">
        
        <div class="flex flex-col gap-6">
            <a href="{{ route('home') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-4 p-4 rounded-2xl {{ request()->routeIs('home') ? 'bg-blue-50 text-[#0095FF]' : 'text-slate-600' }} font-bold text-lg italic">
                <i data-lucide="home"></i> Beranda
            </a>
            @auth
            <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-[#0095FF]' : 'text-slate-600' }} font-bold text-lg italic">
                <i data-lucide="layout-dashboard"></i> Dashboard Saya
            </a>
            @endauth
            <a href="{{ route('mitra') }}" @click="mobileMenuOpen = false"
               class="flex items-center gap-4 p-4 rounded-2xl {{ request()->routeIs('mitra') ? 'bg-blue-50 text-[#0095FF]' : 'text-slate-600' }} font-bold text-lg italic">
                <i data-lucide="building-2"></i> Jadi Mitra
            </a>

            <hr class="border-slate-100 my-2">

            <div class="grid grid-cols-2 gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-center py-4 rounded-2xl border-2 border-slate-100 font-bold text-slate-600">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center py-4 rounded-2xl bg-[#0095FF] text-white font-bold shadow-lg shadow-blue-100">Daftar</a>
                @endguest

                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 rounded-2xl bg-red-50 text-red-500 font-bold italic">Keluar Akun</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>

    @yield('content')

    <footer class="bg-[#0B1221] text-white pt-24 pb-12">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
            <div class="space-y-8">
                <img src="{{ asset('images/logo-homiezy.png') }}" alt="Homiezy Logo" class="h-12 w-auto brightness-200 grayscale invert"> 
                <p class="text-slate-400 text-base leading-relaxed">Platform all-in-one untuk kebutuhan mahasiswa. Temukan kos, katering, dan laundry terpercaya dalam satu aplikasi.</p>
                <div class="space-y-4 text-sm text-slate-300">
                    <p class="flex items-center gap-4"><i data-lucide="mail" class="w-5 h-5 text-[#0095FF]"></i> hello@homiezy.id</p>
                    <p class="flex items-center gap-4"><i data-lucide="phone" class="w-5 h-5 text-[#0095FF]"></i> +62 812-3456-7890</p>
                    <p class="flex items-center gap-4"><i data-lucide="map-pin" class="w-5 h-5 text-[#0095FF]"></i> Purwokerto, Indonesia</p>
                </div>
            </div>
            
            @foreach(['Homiezy' => ['Tentang Kami', 'Cara Kerja', 'Karir', 'Blog'], 'Layanan' => ['Cari Kos', 'Pesan Katering', 'Laundry Express', 'Paket Bundling'], 'Dukungan' => ['Pusat Bantuan', 'Syarat & Ketentuan', 'Kebijakan Privasi', 'FAQ']] as $title => $links)
            <div>
                <h4 class="font-bold text-lg mb-10 uppercase tracking-[0.2em] text-white">{{ $title }}</h4>
                <ul class="space-y-5 text-slate-400 text-base font-medium">
                    @foreach($links as $link)
                    <li><a href="#" class="hover:text-[#0095FF] transition">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
        
        <div class="container mx-auto px-6 pt-10 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-8">
            <p class="text-slate-500 font-medium">© 2025 Homiezy. All rights reserved.</p>
            <div class="flex gap-6">
                @foreach(['instagram', 'facebook', 'twitter'] as $social)
                <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center hover:bg-[#0095FF] transition group cursor-pointer">
                    <i data-lucide="{{ $social }}" class="w-6 h-6 text-slate-400 group-hover:text-white transition"></i>
                </div>
                @endforeach
            </div>
        </div>
    </footer>
    <script>lucide.createIcons();</script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
     <script>
    AOS.init({
        duration: 1000, 
        once: true,     
    });
    </script>
</body>
</html>