@extends('layouts.app')

@section('content')
<section class="pt-32 pb-20 bg-white italic font-sans" data-aos="fade-up" data-aos-delay="100">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-start mb-12">
            <a href="javascript:history.back()" class="flex items-center gap-2 text-slate-600 font-bold hover:text-[#0095FF] transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali
            </a>
            <div>
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Hasil Pencarian</h1>
                <p class="text-slate-400 font-medium">Ditemukan {{ $recommendations->count() }} hasil</p>
            </div>
            <button class="flex items-center gap-2 border border-slate-200 px-6 py-2 rounded-xl font-bold text-slate-600 hover:bg-slate-50 transition">
                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i> Filter
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            @forelse($recommendations as $item)
            <div class="rounded-[40px] overflow-hidden shadow-[0_15px_45px_rgba(0,0,0,0.06)] border border-slate-100 group transition hover:translate-y-[-10px] duration-500 bg-white">
                <div class="relative h-64 overflow-hidden">
                    <img src="{{ $item['img'] }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-1000">
                    
                    <div class="absolute top-4 left-4 bg-white/95 px-4 py-1.5 rounded-full text-[10px] font-black shadow-sm">
                        @if($item['type'] == 'Kos') 🏠 @elseif($item['type'] == 'Katering') 🍱 @else 🧺 @endif {{ $item['type'] }}
                    </div>

                    @if($item['verified'])
                    <div class="absolute top-4 right-4 bg-[#0095FF] text-white px-4 py-1.5 rounded-full text-[10px] font-black flex items-center gap-1 shadow-lg">
                        <i data-lucide="check-circle" class="w-3 h-3 fill-current"></i> Verified
                    </div>
                    @endif
                </div>
                
                <div class="p-8">
                    <h3 class="text-xl font-black mb-4 tracking-tight leading-tight">{{ $item['name'] }}</h3>
                    
                    <div class="space-y-2 mb-6 text-sm italic">
                        <p class="text-slate-400 flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4 text-[#0095FF]"></i> {{ $item['loc'] }}</p>
                        <p class="text-[#0095FF] font-black flex items-center gap-2"><i data-lucide="navigation" class="w-4 h-4"></i> {{ $item['dist'] }}</p>
                    </div>

                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex text-yellow-400">
                            @for($i=0; $i<5; $i++) <i data-lucide="star" class="w-4 h-4 {{ $i < floor($item['rate']) ? 'fill-current' : '' }}"></i> @endfor
                        </div>
                        <span class="text-slate-800 font-black text-xs">{{ $item['rate'] }}</span>
                        <span class="text-slate-400 text-[10px] font-medium">({{ $item['rev'] }} review)</span>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-10">
                        @foreach($item['tags'] as $tag)
                        <span class="bg-blue-50 text-[#0095FF] px-3 py-1 rounded-full text-[9px] font-black border border-blue-100">{{ $tag }}</span>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <div>
                            <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest mb-1 italic">Mulai dari</p>
                            <p class="text-2xl font-black text-[#0095FF]">Rp {{ $item['price'] }}<span class="text-[10px] font-medium">/bln</span></p>
                        </div>
                        <button class="bg-[#0095FF] text-white px-6 py-2 rounded-xl font-black text-sm shadow-md transition hover:bg-blue-600 italic">Lihat Detail</button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20">
                <i data-lucide="search-x" class="w-20 h-20 text-slate-200 mx-auto mb-4"></i>
                <h2 class="text-2xl font-bold text-slate-400">Hasil tidak ditemukan.</h2>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection