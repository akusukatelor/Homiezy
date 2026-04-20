@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pt-28 pb-20 font-sans italic" data-aos="fade-up">
    <div class="container mx-auto px-6">
        
        {{-- Tombol Kembali --}}
        <div class="flex justify-between items-center mb-8">
            <a href="javascript:history.back()" class="flex items-center gap-2 text-slate-600 font-bold hover:text-[#0095FF] transition">
                <i data-lucide="arrow-left" class="w-5 h-5"></i> Kembali
            </a>
            <div class="flex gap-4">
                <button class="w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-red-500 shadow-sm transition">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                </button>
                <button class="w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-[#0095FF] shadow-sm transition">
                    <i data-lucide="share-2" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            {{-- KOLOM KIRI --}}
            <div class="lg:col-span-2 space-y-10">
                
                {{-- Image Hero --}}
                <div class="relative h-[500px] rounded-[40px] overflow-hidden shadow-xl">
                    <img src="{{ $item->image }}" class="w-full h-full object-cover">
                    @if($item->is_verified)
                    <div class="absolute top-8 left-8 bg-[#0095FF] text-white px-6 py-2 rounded-full text-xs font-black uppercase tracking-widest shadow-lg">
                        Verified Member
                    </div>
                    @endif
                </div>

                {{-- Card Info Utama --}}
                <div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-sm">
                    <h1 class="text-4xl font-black mb-4 text-slate-800">{{ $item->name }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-slate-400 font-bold text-sm mb-8 italic">
                        <p class="flex items-center gap-2"><i data-lucide="map-pin" class="text-[#0095FF] w-4 h-4"></i> {{ $item->location }}</p>
                        <p class="text-[#0095FF] flex items-center gap-2"><i data-lucide="navigation" class="w-4 h-4"></i> {{ $item->distance }}</p>
                        <div class="flex items-center gap-2">
                            <i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-current"></i>
                            <span class="text-slate-800">{{ $item->rating }}</span> 
                            <span>({{ $item->reviews_count }} ulasan)</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-50 pt-8">
                        <h4 class="text-xl font-black mb-4">Deskripsi</h4>
                        <p class="text-slate-500 leading-relaxed italic">
                            @if($item->type == 'kos')
                                {{ $item->name }} merupakan hunian strategis yang berlokasi di {{ $item->location }}. Menawarkan kenyamanan maksimal untuk mahasiswa dengan akses mudah hanya {{ $item->distance }}.
                            @elseif($item->type == 'katering' && $item->extra_info)
                                <div class="mt-8 border-t border-slate-50 pt-8">
                                    <h4 class="text-xl font-black mb-6 italic text-slate-800">🍱 Contoh Menu Kami</h4>
                                    <div class="space-y-3">
                                        @foreach($item->extra_info as $menu)
                                        <div class="flex items-center gap-4 p-5 bg-emerald-50/50 rounded-3xl border border-emerald-100/50">
                                            <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm">
                                                <i data-lucide="utensils" class="w-5 h-5"></i>
                                            </div>
                                            <span class="font-bold text-slate-700 italic">{{ $menu }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                {{ $item->name }} menyediakan layanan pencucian profesional dengan teknologi modern. Pakaian kamu akan bersih, wangi, dan rapi dalam waktu singkat.
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Fasilitas Berdasarkan Tipe --}}
              {{-- Fasilitas Berdasarkan Data Mitra --}}
        <div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-sm italic">
            <h4 class="text-xl font-black mb-8 italic">
                {{ $item->type == 'katering' ? 'Keunggulan Layanan' : 'Fasilitas Tersedia' }}
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @if($item->features)
                    @foreach($item->features as $feature)
                    <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:bg-blue-50 transition">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#0095FF] shadow-sm">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-bold text-slate-700 italic">{{ $feature }}</span>
                    </div>
                    @endforeach
                @else
                    <p class="text-slate-400 text-sm italic">Informasi fasilitas belum ditambahkan.</p>
                @endif
                    </div>
                </div>
            </div>

            {{-- SIDEBAR BOOKING (Sticky) --}}
            <div class="space-y-8">
                <div class="sticky top-32 bg-white p-10 rounded-[40px] border border-slate-100 shadow-2xl shadow-blue-100 italic">
                    <p class="text-slate-400 text-xs font-black uppercase mb-2">Harga Mulai Dari</p>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-black text-[#0095FF]">Rp{{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="text-slate-400 font-bold">{{ $item->type == 'laundry' ? '/kg' : '/bln' }}</span>
                    </div>

                    @php
                        // Logika merapikan nomor WA
                        $wa = $item->whatsapp;
                        if (str_starts_with($wa, '0')) {
                            $wa = '62' . substr($wa, 1);
                        } elseif (str_starts_with($wa, '+62')) {
                            $wa = substr($wa, 1);
                        }
                        
                        $pesanWA = "Halo Homiezy, saya tertarik dengan layanan *" . $item->name . "* yang berada di " . $item->location;
                    @endphp

                  <form action="{{ route('order.process', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full py-5 bg-[#0095FF] text-white rounded-2xl font-black text-xl shadow-lg hover:bg-blue-600 transition transform hover:scale-105 mb-6 text-center active:scale-95 italic">
                            Booking Sekarang
                        </button>
                    </form>

                    <div class="space-y-4 mb-10 text-xs font-bold text-slate-500">
                        <div class="flex items-center gap-3"><i data-lucide="clock" class="w-4 h-4 text-[#0095FF]"></i> Konfirmasi Instan</div>
                        <div class="flex items-center gap-3"><i data-lucide="shield-check" class="w-4 h-4 text-[#0095FF]"></i> Pembayaran Aman</div>
                        <div class="flex items-center gap-3"><i data-lucide="users" class="w-4 h-4 text-[#0095FF]"></i> Dipercaya {{ $item->reviews_count }} mahasiswa</div>
                    </div>

                    {{-- Info Vendor --}}
                    <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-3xl">
                        <img src="https://i.pravatar.cc/150?u={{ $item->id }}" class="w-12 h-12 rounded-2xl object-cover shadow-md">
                        <div>
                            <p class="text-[10px] text-slate-400 font-black uppercase">Dikelola oleh</p>
                            <p class="font-black text-slate-800">Mitra Homiezy Purwokerto</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection