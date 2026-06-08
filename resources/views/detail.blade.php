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

                    @if($item->type === 'kos')
                    <div class="grid grid-cols-3 gap-4 mb-8 pt-6 border-t border-slate-50">
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center"><i data-lucide="ruler" class="w-5 h-5"></i></div>
                            <div><p class="text-[9px] font-black text-slate-400 uppercase">Ukuran Kamar</p><p class="text-sm font-black text-slate-800">{{ $item->room_size ?? 'Standard' }}</p></div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center gap-4">
                            <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center"><i data-lucide="zap" class="w-5 h-5"></i></div>
                            <div><p class="text-[9px] font-black text-slate-400 uppercase">Listrik</p><p class="text-sm font-black text-slate-800">{{ $item->electricity ?? 'Exclude' }}</p></div>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center gap-4">
                            <div class="w-10 h-10 bg-cyan-100 text-cyan-600 rounded-xl flex items-center justify-center"><i data-lucide="droplet" class="w-5 h-5"></i></div>
                            <div><p class="text-[9px] font-black text-slate-400 uppercase">Air</p><p class="text-sm font-black text-slate-800">{{ $item->water ?? 'Include' }}</p></div>
                        </div>
                    </div>
                    @endif

                    <div class="border-t border-slate-50 pt-8">
    <h4 class="text-xl font-black mb-4 italic">Deskripsi</h4>
    <div class="text-slate-500 leading-relaxed italic">

        {{-- KONDISI 1: RUMAH KOS --}}
        @if($item->type == 'kos')
            <p>
                {{ $item->name }} adalah hunian strategis yang berlokasi di {{ $item->location }}.
                Berada sekitar {{ $item->distance_info ?? $item->distance }} dari titik utama kampus,
                kos ini menawarkan kenyamanan dengan ukuran kamar {{ $item->room_size ?? 'standard' }}.
                Untuk fasilitas dasar, biaya air sudah {{ strtolower($item->water ?? 'include') }}
                dan listrik bersifat {{ strtolower($item->electricity ?? 'exclude') }}.
            </p>

        {{-- KONDISI 2: KATERING --}}
        @elseif($item->type == 'katering')
            <p>
                {{ $item->name }} menyediakan layanan katering harian yang sehat dan bergizi untuk mahasiswa di area {{ $item->location }}.
                Dapur kami berlokasi sangat dekat, hanya {{ $item->distance_info ?? $item->distance }} dari pusat keramaian,
                memastikan makanan sampai ke tanganmu tetap dalam kondisi segar dan hangat.
            </p>

            {{-- Bagian Menu: Hanya muncul jika datanya ada --}}
            @if($item->extra_info)
                <div class="mt-8 border-t border-slate-50 pt-8">
                    <h4 class="text-xl font-black mb-6 italic text-slate-800">🍱 Contoh Menu Kami</h4>
                    <div class="space-y-3">
                        @php
                            $menus = is_array($item->extra_info) ? $item->extra_info : json_decode($item->extra_info, true);
                        @endphp
                        @foreach($menus as $menu)
                            <div class="flex items-center gap-4 p-5 bg-emerald-50/50 rounded-3xl border border-emerald-100/50">
                                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm">
                                    <i data-lucide="utensils" class="w-5 h-5"></i>
                                </div>
                                <span class="font-bold text-slate-700 italic">{{ $menu }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        {{-- KONDISI 3: LAUNDRY --}}
        @elseif($item->type == 'laundry')
            <p>
                {{ $item->name }} hadir sebagai solusi laundry profesional di wilayah {{ $item->location }}.
                Dengan jarak sekitar {{ $item->distance_info ?? $item->distance }} dari pemukiman mahasiswa,
                kami menjamin pakaian kamu kembali bersih, wangi, dan rapi dalam waktu singkat menggunakan teknologi cuci terbaru.
            </p>
        @endif

    </div>
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
                {{-- SECTION ULASAN --}}
<div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-sm italic">

    {{-- Header rating --}}
    <div class="flex items-center justify-between mb-8">
        <h4 class="text-xl font-black italic">Ulasan Mahasiswa</h4>
        <div class="flex items-center gap-2 bg-yellow-50 px-4 py-2 rounded-2xl border border-yellow-100">
            <i data-lucide="star" class="w-5 h-5 text-yellow-400 fill-current"></i>
            <span class="font-black text-slate-800">{{ number_format($item->rating, 1) }}</span>
            <span class="text-slate-400 font-bold text-sm">({{ $item->reviews_count }} ulasan)</span>
        </div>
    </div>

    {{-- Form Tambah Review --}}
    @auth
        @if($userOrder && !$hasReviewed)
        <div class="mb-8 p-8 bg-blue-50/50 rounded-[32px] border border-blue-100"
             x-data="{ rating: 0, hovered: 0 }">
            <h5 class="font-black text-slate-800 mb-6 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-[#0095FF]"></i>
                Bagikan Pengalamanmu
            </h5>
            <form action="{{ route('review.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="service_id" value="{{ $item->id }}">
                <input type="hidden" name="order_id" value="{{ $userOrder->id }}">

                {{-- Star Rating --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rating</label>
                    <div class="flex gap-2">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}"
                                   x-on:change="rating = {{ $i }}" class="hidden">
                            <i data-lucide="star"
                               x-on:mouseenter="hovered = {{ $i }}"
                               x-on:mouseleave="hovered = 0"
                               :class="(hovered >= {{ $i }} || rating >= {{ $i }})
                                   ? 'text-yellow-400 fill-current w-8 h-8'
                                   : 'text-slate-200 w-8 h-8'"
                               class="transition-colors duration-150"></i>
                        </label>
                        @endfor
                    </div>
                </div>

                {{-- Komentar --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Komentar</label>
                    <textarea name="comment" rows="3" required
                              placeholder="Ceritakan pengalamanmu menggunakan layanan ini..."
                              class="w-full p-4 bg-white rounded-2xl border border-slate-200 font-bold text-slate-700 focus:ring-2 focus:ring-[#0095FF] focus:border-transparent outline-none resize-none text-sm"></textarea>
                </div>

                <button type="submit"
                        class="px-8 py-3 bg-[#0095FF] text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-600 transition italic shadow-lg shadow-blue-100">
                    Kirim Ulasan
                </button>
            </form>
        </div>
        @elseif($hasReviewed)
        <div class="mb-8 p-6 bg-emerald-50 rounded-[24px] border border-emerald-100 flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
            <p class="font-bold text-emerald-600 text-sm italic">Kamu sudah memberikan ulasan untuk layanan ini.</p>
        </div>
        @endif
    @endauth

    {{-- Flash success/error --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-emerald-600 font-bold text-sm italic flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 rounded-2xl border border-red-100 text-red-500 font-bold text-sm italic flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- List Review --}}
    <div class="space-y-6">
        @forelse($reviews as $review)
        <div class="p-6 bg-slate-50/50 rounded-[28px] border border-slate-100">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#0095FF] rounded-2xl flex items-center justify-center font-black text-white text-sm">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-black text-slate-800 text-sm">{{ $review->user->name }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            {{ $review->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                {{-- Bintang --}}
                <div class="flex gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                    <i data-lucide="star"
                       class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400 fill-current' : 'text-slate-200' }}"></i>
                    @endfor
                </div>
            </div>
            <p class="text-slate-600 font-bold text-sm leading-relaxed italic">
                {{ $review->comment }}
            </p>
        </div>
        @empty
        <div class="py-12 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-[20px] flex items-center justify-center mx-auto mb-4">
                <i data-lucide="message-square" class="w-7 h-7 text-slate-200"></i>
            </div>
            <p class="text-slate-400 font-bold text-sm italic">Belum ada ulasan. Jadilah yang pertama! ✨</p>
        </div>
        @endforelse
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

{{-- Tombol Booking --}}
<form action="{{ route('order.process', $item->id) }}" method="POST">
    @csrf
    <button type="submit"
            class="w-full py-5 bg-[#0095FF] text-white rounded-2xl font-black text-xl shadow-lg hover:bg-blue-600 transition transform hover:scale-105 mb-4 text-center active:scale-95 italic">
        Booking Sekarang
    </button>
</form>

{{-- Tombol Bayar Sekarang --}}
@auth
    @php
        $existingOrder = auth()->user()->orders()
            ->where('service_id', $item->id)
            ->where('status', 'Pending')
            ->whereNotNull('xendit_invoice_id')
            ->first();
    @endphp

    @if($existingOrder)
    <a href="{{ route('xendit.create', $existingOrder->id) }}"
       class="w-full py-5 bg-emerald-500 text-white rounded-2xl font-black text-xl shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition transform hover:scale-105 mb-4 text-center active:scale-95 italic flex items-center justify-center gap-3">
        <i data-lucide="credit-card" class="w-5 h-5"></i>
        Bayar Sekarang
    </a>
    @endif
@endauth

{{-- Tombol Chat WA --}}
<a href="https://wa.me/{{ $wa }}?text={{ urlencode($pesanWA) }}"
   target="_blank"
   class="w-full py-5 bg-white border-2 border-slate-200 text-slate-700 rounded-2xl font-black text-xl shadow-sm hover:border-green-400 hover:text-green-500 transition transform hover:scale-105 mb-6 text-center active:scale-95 italic flex items-center justify-center gap-3">
    <i data-lucide="message-circle" class="w-5 h-5"></i>
    Chat WhatsApp
</a>

                    <div class="space-y-4 mb-10 text-xs font-bold text-slate-500">
                        <div class="flex items-center gap-3"><i data-lucide="clock" class="w-4 h-4 text-[#0095FF]"></i> Konfirmasi Instan</div>
                        <div class="flex items-center gap-3"><i data-lucide="shield-check" class="w-4 h-4 text-[#0095FF]"></i> Pembayaran Aman</div>
                        <div class="flex items-center gap-3"><i data-lucide="users" class="w-4 h-4 text-[#0095FF]"></i> Dipercaya {{ $item->reviews_count }} mahasiswa</div>
                    </div>

                    {{-- Info Vendor --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
