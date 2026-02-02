@extends('layouts.app')

@section('content')
<section class="min-h-[850px] flex items-center pt-24 relative overflow-hidden font-sans">
    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=2071" class="absolute inset-0 w-full h-full object-cover z-0">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-[#0095FF]/40 to-[#0095FF] z-10"></div>

    <div class="container mx-auto px-6 text-center text-white relative z-20" x-data="{ openLayanan: false, layananSelected: '{{ $search_layanan ?? '' }}' }">
        <form action="{{ route('search') }}" method="GET" class="max-w-5xl mx-auto glass-container p-4 rounded-[32px] shadow-2xl mb-24 flex flex-col md:flex-row gap-3">
            
            <div class="flex-1 bg-white rounded-2xl px-6 py-4 flex items-center gap-4 group">
                <i data-lucide="map-pin" class="text-[#0095FF] w-5 h-5"></i>
                <input type="text" name="lokasi" value="{{ $search_lokasi ?? '' }}" placeholder="Lokasi Kampus (Contoh: Grendeng)" class="w-full focus:outline-none text-slate-800 font-semibold placeholder:text-slate-400">
            </div>

            <div class="flex-1 bg-white rounded-2xl px-6 py-4 flex items-center gap-4 relative" @click.away="openLayanan = false">
                <i data-lucide="search" class="text-[#0095FF] w-5 h-5"></i>
                <input type="text" 
                       name="layanan" 
                       x-model="layananSelected" 
                       @focus="openLayanan = true"
                       placeholder="Pilih Layanan" 
                       class="w-full focus:outline-none text-slate-800 font-semibold placeholder:text-slate-400 cursor-pointer"
                       readonly>
                
                <div x-show="openLayanan" class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-xl overflow-hidden z-50 border border-slate-100">
                    @foreach(['Semua Layanan', 'Kos', 'Katering', 'Laundry'] as $item)
                    <div @click="layananSelected = '{{ $item }}'; openLayanan = false" 
                         class="px-6 py-3 text-slate-700 hover:bg-blue-50 hover:text-[#0095FF] cursor-pointer text-left font-medium transition">
                        {{ $item }}
                    </div>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="bg-[#0095FF] hover:bg-blue-600 text-white px-10 py-4 rounded-2xl font-black transition shadow-lg flex items-center justify-center gap-3">
                <i data-lucide="search" class="w-5 h-5"></i> Cari Sekarang
            </button>
        </form>

        <h1 class="text-6xl md:text-8xl font-bold mb-10 tracking-tight leading-tight">Feels like home,<br>Anywhere</h1>
        <p class="text-2xl font-light opacity-95 mb-4 italic">Satu platform untuk semua kebutuhan kos, makan, dan laundry.</p>
    </div>
</section>

<section class="py-32 bg-white">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-5xl font-black mb-8 tracking-tight">Mengapa Homiezy?</h2>
        <p class="text-slate-500 text-xl max-w-3xl mx-auto mb-24 leading-relaxed">Karena kuliah udah cukup bikin pusing. Urusan tempat tinggal, makan, dan laundry biar kami yang bantu!</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @foreach([['icon' => 'clock', 'title' => 'Hemat Waktu', 'desc' => 'Nggak perlu lagi chat puluhan pemilik kos atau cari laundry satu-satu. Semua ada di satu tempat!'], ['icon' => 'shield-check', 'title' => 'Terpercaya', 'desc' => 'Semua listing sudah diverifikasi. Harga transparan, review asli dari sesama mahasiswa.'], ['icon' => 'wallet', 'title' => 'Hemat Biaya', 'desc' => 'Bundling paket kos + makan + laundry bisa dapat diskon hingga 20%. Kelola budget jadi lebih mudah!']] as $why)
            <div class="bg-white p-12 rounded-[40px] shadow-[0_20px_60px_rgba(0,0,0,0.04)] border border-slate-50 text-left transition hover:-translate-y-3 duration-500">
                <div class="w-20 h-20 bg-[#0095FF] rounded-2xl flex items-center justify-center text-white mb-10 shadow-xl shadow-blue-100">
                    <i data-lucide="{{ $why['icon'] }}" class="w-10 h-10"></i>
                </div>
                <h3 class="text-3xl font-black mb-6">{{ $why['title'] }}</h3>
                <p class="text-slate-500 text-lg leading-relaxed">{{ $why['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-32 bg-[#F8FAFC]">
    <div class="container mx-auto px-6">
        <div class="text-center mb-20">
            <span class="bg-[#0095FF] text-white px-8 py-2 rounded-full text-xs font-black shadow-lg shadow-blue-200 uppercase tracking-widest">⚡ Penawaran Terbaik</span>
            <h2 class="text-6xl mt-8 tracking-tighter">Paket Bundling Hemat</h2>
            <p class="text-slate-500 mt-6 text-2xl font-medium opacity-80">Gabungin kos, makan, dan laundry dalam satu paket. Hemat sampai 20%!</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 items-end">
            @php $packages = [
                ['name'=>'Basic', 'price'=>'700rb', 'save'=>'150rb', 'feat'=>['Kamar standar nyaman & aman', 'Akses WiFi gratis', 'Listrik & air termasuk', 'Keamanan 24 jam'], 'icons'=>['home'], 'pop'=>false],
                ['name'=>'Standard Clean', 'price'=>'788rb', 'save'=>'162rb', 'feat'=>['Semua benefit paket Basic', 'Laundry 2x seminggu (max 10kg)', 'Cuci + Setrika + Lipat', 'Pick up & delivery gratis'], 'icons'=>['home', 'shirt'], 'pop'=>false],
                ['name'=>'Standard Meal', 'price'=>'1.6jt', 'save'=>'300rb', 'feat'=>['Semua benefit paket Basic', 'Makan 2x sehari (Senin-Sabtu)', 'Menu bervariasi & bergizi', 'Porsi bisa request'], 'icons'=>['home', 'utensils'], 'pop'=>true],
                ['name'=>'Premium', 'price'=>'1.8jt', 'save'=>'372rb', 'feat'=>['Kamar premium ber-AC', 'Kamar mandi dalam', 'Makan 3x sehari (Setiap hari)', 'Laundry unlimited'], 'icons'=>['home', 'utensils', 'shirt'], 'pop'=>false]
            ]; @endphp

            @foreach($packages as $pkg)
            <div class="{{ $pkg['pop'] ? 'bg-[#0095FF] text-white scale-105 shadow-2xl z-10' : 'bg-white text-slate-800' }} p-10 rounded-[48px] relative transition hover:scale-110 duration-500 border border-slate-100">
                @if($pkg['pop'])
                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-[#0F172A] text-white px-8 py-2 rounded-full text-[10px] font-black tracking-widest shadow-xl">★ PALING POPULER</div>
                @endif
                <h3 class="text-3xl font-black mb-2 tracking-tight">{{ $pkg['name'] }}</h3>
                <p class="text-xs font-medium opacity-70 mb-8">Solusi tempat tinggal ekonomis untuk mahasiswa</p>
                
                <div class="flex gap-2 mb-10">
                    @foreach($pkg['icons'] as $icon)
                    <div class="w-8 h-8 rounded-lg {{ $pkg['pop'] ? 'bg-blue-400/50' : 'bg-blue-50' }} flex items-center justify-center">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $pkg['pop'] ? 'text-white' : 'text-[#0095FF]' }}"></i>
                    </div>
                    @endforeach
                </div>

                <div class="mb-10">
                    <p class="text-sm opacity-60 line-through mb-1 font-bold">Rp 850.000</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-5xl font-black tracking-tighter">{{ $pkg['price'] }}</span><span class="text-lg font-bold">/bln</span>
                    </div>
                    <p class="{{ $pkg['pop'] ? 'text-yellow-300' : 'text-[#0095FF]' }} font-black text-sm mt-4 flex items-center gap-2 uppercase tracking-tight">💰 Hemat Rp {{ $pkg['save'] }}</p>
                </div>

                <ul class="space-y-5 mb-12 text-sm font-semibold opacity-90">
                    @foreach($pkg['feat'] as $f)
                        <li class="flex items-start gap-4">
                            <i data-lucide="check-circle" class="w-5 h-5 {{ $pkg['pop'] ? 'text-white' : 'text-[#0095FF]' }}"></i>
                            <span class="leading-tight">{{ $f }}</span>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('paket.wizard', ['type' => Str::slug($pkg['name'])]) }}" 
   class="w-full py-5 rounded-3xl font-black text-lg transition shadow-lg text-center block {{ $pkg['pop'] ? 'bg-white text-[#0095FF]' : 'bg-[#0095FF] text-white' }}">
    Pilih Paket
</a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 md:py-32 bg-white" x-data="{ showAll: false }">
    <div class="container mx-auto px-4 md:px-6">
        <div class="text-center mb-16 md:mb-24 relative">
            <span class="bg-[#0095FF] text-white px-6 py-2 rounded-full text-[10px] font-black shadow-lg uppercase tracking-widest">📈 Rekomendasi Pintar</span>
            <h2 class="text-3xl sm:text-4xl md:text-6xl font-black mt-8 tracking-tighter leading-tight">Smart Recommendations</h2>
            <p class="text-slate-500 mt-4 md:mt-6 text-lg md:text-2xl max-w-4xl mx-auto font-medium opacity-80 px-2">Berdasarkan lokasi kampus dan budget-mu, ini pilihan terbaik kami</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-12">
            @foreach($recommendations as $index => $item)
            <div 
                x-show="showAll || {{ $index }} < 3" 
                x-transition:enter="transition ease-out duration-500"
                class="rounded-[32px] md:rounded-[48px] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.06)] border border-slate-50 group transition hover:translate-y-[-10px] duration-500 bg-white flex flex-col"
            >
                <div class="relative h-56 md:h-72 overflow-hidden">
                    <img src="{{ $item['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                    <div class="absolute top-4 left-4 bg-white/95 px-4 py-1.5 rounded-full text-[9px] font-black shadow-md uppercase">🏠 {{ $item['type'] }}</div>
                    <div class="absolute top-4 right-4 bg-[#0095FF] text-white px-4 py-1.5 rounded-full text-[9px] font-black shadow-xl tracking-widest">{{ $item['match'] }} Match</div>
                </div>
                
                <div class="p-6 md:p-10 flex-1 flex flex-col">
                    <h3 class="text-xl md:text-2xl font-black mb-3 leading-tight text-slate-800">{{ $item['name'] }}</h3>
                    
                    <div class="flex items-center gap-2 mb-4 md:mb-6">
                        <div class="flex text-yellow-400">
                            @for($i=0; $i<5; $i++)
                                <i data-lucide="star" class="w-3.5 h-3.5 {{ $i < floor($item['rate']) ? 'fill-current' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="text-slate-800 font-black text-xs md:text-sm">{{ $item['rate'] }}</span>
                    </div>

                    <div class="space-y-2 mb-6 md:mb-8">
                        <p class="text-slate-400 text-xs md:text-sm flex items-center gap-2 font-medium">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#0095FF] flex-shrink-0"></i> 
                            <span class="truncate">{{ $item['loc'] }}</span>
                        </p>
                        <p class="text-[#0095FF] text-xs md:text-sm flex items-center gap-2 font-black">
                            <i data-lucide="navigation" class="w-4 h-4 flex-shrink-0"></i> {{ $item['dist'] }}
                        </p>
                    </div>

                    <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div>
                            <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest mb-1">Mulai dari</p>
                            <p class="text-xl md:text-2xl font-black text-[#0095FF]">Rp {{ $item['price'] }}<span class="text-[10px] font-medium text-slate-400">{{ $item['unit'] ?? '/bln' }}</span></p>
                        </div>
                        
                        <a href="{{ route('service.detail', ['type' => strtolower($item['type']), 'slug' => Str::slug($item['name'])]) }}" 
                           class="bg-[#0095FF] text-white px-5 md:px-7 py-2.5 rounded-xl font-black text-xs md:text-sm shadow-lg shadow-blue-50 transition hover:bg-blue-600">
                           Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-16 md:mt-24">
            <button @click="showAll = !showAll" 
                    class="border-2 border-[#0095FF] text-[#0095FF] px-10 md:px-16 py-4 md:py-5 rounded-2xl font-black text-base md:text-xl hover:bg-[#0095FF] hover:text-white transition duration-500 shadow-xl shadow-blue-50">
                <span x-text="showAll ? 'Sembunyikan' : 'Lihat Semua Rekomendasi'"></span>
            </button>
        </div>
    </div>
</section>

<section class="py-32 bg-[#F8FAFC]">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-6xl font-black mb-10 tracking-tighter">Kata Mereka Tentang Homiezy</h2>
        <p class="text-slate-400 text-2xl mb-24 max-w-4xl mx-auto font-medium">Sudah 10,000+ mahasiswa mempercayai Homiezy untuk kebutuhan hidup mereka</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @php $testis = [
                ['n'=>'Zee Asadel', 'r'=>'Model', 'l'=>'Kos + Katering'],
                ['n'=>'Angga Yunanda', 'r'=>'Mahasiswa UT', 'l'=>'Paket Bundling Premium'],
                ['n'=>'Gisselma Firmansyah', 'r'=>'Mahasiswa UI', 'l'=>'Kos Putri']
            ]; @endphp

            @foreach($testis as $t)
            <div class="bg-white p-12 rounded-[50px] shadow-[0_15px_40px_rgba(0,0,0,0.03)] text-left relative transition hover:scale-105 duration-500 group border border-slate-50">
                <div class="text-[#0095FF]/10 absolute top-10 right-12 group-hover:text-[#0095FF]/30 transition">
                    <i data-lucide="quote" class="w-16 h-16"></i>
                </div>
                <div class="flex items-center gap-1 mb-8 text-yellow-400">
                    @for($i=0; $i<5; $i++) <i data-lucide="star" class="w-5 h-5 fill-current"></i> @endfor
                </div>
                <p class="text-slate-600 text-lg mb-10 leading-relaxed font-medium">"Homiezy bener-bener ngebantu banget! Dulu pusing cari kos yang deket kampus, sekarang tinggal klik langsung dapet. Paket bundling-nya juara!"</p>
                <div class="inline-block bg-blue-50 text-[#0095FF] px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-wider mb-10">{{ $t['l'] }}</div>
                <div class="flex items-center gap-5 pt-8 border-t border-slate-50">
                    <img src="https://i.pravatar.cc/150?u={{ $t['n'] }}" class="w-16 h-16 rounded-full border-4 border-white shadow-xl object-cover">
                    <div>
                        <h4 class="font-black text-xl tracking-tight">{{ $t['n'] }}</h4>
                        <p class="text-slate-400 text-[10px] uppercase font-bold tracking-[0.2em]">{{ $t['r'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection