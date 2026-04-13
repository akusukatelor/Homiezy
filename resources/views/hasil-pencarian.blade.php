@extends('layouts.app')

@section('content')
<section class="pt-32 pb-20 bg-white italic font-sans" data-aos="fade-up" data-aos-delay="100">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-start mb-12">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-slate-600 font-bold hover:text-[#0095FF] transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali
            </a>
            <div class="text-center">
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Hasil Pencarian</h1>
                <p class="text-slate-400 font-medium italic">
                    @if($search_lokasi) Mencari di "{{ $search_lokasi }}" • @endif
                    Ditemukan {{ $recommendations->count() }} hasil
                </p>
            </div>
            <button class="flex items-center gap-2 border border-slate-200 px-6 py-2 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition">
                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i> Filter
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @forelse($recommendations as $item)
            <div class="rounded-[40px] overflow-hidden shadow-[0_15px_45px_rgba(0,0,0,0.06)] border border-slate-100 group transition hover:translate-y-[-10px] duration-500 bg-white flex flex-col">
                <div class="relative h-64 overflow-hidden">
                    {{-- Akses kolom 'image' --}}
                    <img src="{{ $item->image }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                    
                    <div class="absolute top-4 left-4 bg-white/95 px-4 py-1.5 rounded-full text-[10px] font-black shadow-sm uppercase">
                        @if(strtolower($item->type) == 'kos') 🏠 @elseif(strtolower($item->type) == 'katering') 🍱 @else 🧺 @endif 
                        {{ $item->type }}
                    </div>

                    {{-- Akses kolom 'is_verified' --}}
                    @if($item->is_verified)
                    <div class="absolute top-4 right-4 bg-[#0095FF] text-white px-4 py-1.5 rounded-full text-[10px] font-black flex items-center gap-1 shadow-lg">
                        <i data-lucide="check-circle" class="w-3 h-3 fill-current"></i> Verified
                    </div>
                    @endif
                </div>
                
                <div class="p-8 flex-1 flex flex-col">
                    <h3 class="text-xl font-black mb-4 tracking-tight leading-tight">{{ $item->name }}</h3>
                    
                    <div class="space-y-2 mb-6 text-sm italic">
                        {{-- Akses kolom 'location' dan 'distance' --}}
                        <p class="text-slate-400 flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4 text-[#0095FF]"></i> {{ $item->location }}</p>
                        <p class="text-[#0095FF] font-black flex items-center gap-2"><i data-lucide="navigation" class="w-4 h-4"></i> {{ $item->distance }}</p>
                    </div>

                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex text-yellow-400">
                            {{-- Generate Bintang berdasarkan 'rating' --}}
                            @for($i=0; $i<5; $i++) 
                                <i data-lucide="star" class="w-4 h-4 {{ $i < floor($item->rating) ? 'fill-current' : '' }}"></i> 
                            @endfor
                        </div>
                        <span class="text-slate-800 font-black text-xs">{{ $item->rating }}</span>
                        <span class="text-slate-400 text-[10px] font-medium">({{ $item->reviews_count }} review)</span>
                    </div>

                    {{-- Akses JSON 'features' yang sudah di-cast menjadi array di Model --}}
                    <div class="flex flex-wrap gap-2 mb-10">
                        @if($item->features)
                            @foreach($item->features as $tag)
                            <span class="bg-blue-50 text-[#0095FF] px-3 py-1 rounded-full text-[9px] font-black border border-blue-100 uppercase tracking-tighter">{{ $tag }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-50">
                        <div>
                            <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest mb-1 italic">Mulai dari</p>
                            {{-- Format harga ke Rupiah --}}
                            <p class="text-2xl font-black text-[#0095FF]">Rp {{ number_format($item->price, 0, ',', '.') }}<span class="text-[10px] font-medium">/bln</span></p>
                        </div>
                        {{-- Link ke Detail --}}
                        <a href="{{ route('service.detail', ['type' => strtolower($item->type), 'slug' => Str::slug($item->name)]) }}" 
                           class="bg-[#0095FF] text-white px-6 py-2 rounded-xl font-black text-sm shadow-md transition hover:bg-blue-600 italic text-center">
                           Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20 bg-slate-50 rounded-[40px] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i data-lucide="search-x" class="w-12 h-12 text-slate-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-400">Wah, hasil tidak ditemukan...</h2>
                <p class="text-slate-400 mt-2">Coba cari lokasi lain atau ganti filter layananmu.</p>
                <a href="{{ route('home') }}" class="inline-block mt-8 text-[#0095FF] font-black underline">Kembali ke Beranda</a>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection