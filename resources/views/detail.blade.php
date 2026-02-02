@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pt-28 pb-20 font-sans italic">
    <div class="container mx-auto px-6">
        
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
            <div class="lg:col-span-2 space-y-10">
                
                <div x-data="{ activeImg: 0, images: ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267', 'https://images.unsplash.com/photo-1555854817-40e09806a491', 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60'] }">
                    <div class="relative h-[500px] rounded-[40px] overflow-hidden mb-6 shadow-xl">
                        <img :src="images[activeImg]" class="w-full h-full object-cover transition-all duration-500">
                        <button @click="activeImg = activeImg > 0 ? activeImg - 1 : images.length - 1" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><i data-lucide="chevron-left"></i></button>
                        <button @click="activeImg = activeImg < images.length - 1 ? activeImg + 1 : 0" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 rounded-full flex items-center justify-center shadow-lg"><i data-lucide="chevron-right"></i></button>
                    </div>
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        <template x-for="(img, index) in images" :key="index">
                            <button @click="activeImg = index" :class="activeImg === index ? 'border-[#0095FF] ring-2 ring-blue-100' : 'border-transparent'" class="w-32 h-24 rounded-2xl overflow-hidden border-2 transition shadow-md flex-shrink-0">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <h1 class="text-4xl font-black tracking-tight">Kos Putri Mawar Residence</h1>
                        <span class="bg-[#0095FF] text-white px-4 py-1.5 rounded-full text-[10px] font-black flex items-center gap-1 shadow-lg shadow-blue-100">
                            <i data-lucide="check-circle" class="w-3 h-3 fill-current"></i> Verified
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-6 text-slate-400 font-bold text-sm mb-8 italic">
                        <p class="flex items-center gap-2"><i data-lucide="map-pin" class="text-[#0095FF] w-4 h-4"></i> Jl. Kaliurang KM 5, Sleman, Yogyakarta</p>
                        <p class="text-[#0095FF] flex items-center gap-2"><i data-lucide="navigation" class="w-4 h-4"></i> 500m dari UGM</p>
                        <div class="flex items-center gap-2">
                            <div class="flex text-yellow-400"><i data-lucide="star" class="w-4 h-4 fill-current"></i></div>
                            <span class="text-slate-800">4.8</span> <span>(124 review)</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-50 pt-8">
                        <h4 class="text-xl font-black mb-4 italic">Deskripsi</h4>
                        <p class="text-slate-500 leading-relaxed italic">Kos eksklusif khusus putri dengan fasilitas lengkap dan lokasi strategis dekat kampus UGM. Kami menyediakan kamar yang nyaman, bersih, dan aman untuk mahasiswi. Dilengkapi fasilitas modern seperti AC, WiFi kencang, dan kamar mandi dalam.</p>
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-sm italic">
                    <h4 class="text-xl font-black mb-8">Fasilitas</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach(['Kasur & Lemari' => 'home', 'AC' => 'wind', 'WiFi 100 Mbps' => 'wifi', 'Kamar Mandi Dalam' => 'bath', 'Parkir Motor' => 'car', 'Security 24/7' => 'shield-check', 'CCTV' => 'video', 'Dapur Bersama' => 'utensils', 'Area Jemuran' => 'sun'] as $f => $i)
                        <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:bg-blue-50 transition">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-[#0095FF] shadow-sm"><i data-lucide="{{ $i }}" class="w-5 h-5"></i></div>
                            <span class="text-sm font-bold text-slate-700">{{ $f }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-10 rounded-[40px] border border-slate-50 shadow-sm italic">
                    <h4 class="text-xl font-black mb-8">Peraturan Kos</h4>
                    <ul class="space-y-4">
                        @foreach(['Khusus mahasiswi', 'Tidak boleh membawa tamu lawan jenis ke kamar', 'Jam malam maksimal pukul 23.00', 'Dilarang merokok di dalam kamar', 'Menjaga kebersihan kamar dan area bersama'] as $rule)
                        <li class="flex items-start gap-4 text-slate-500 font-medium">
                            <i data-lucide="check" class="w-5 h-5 text-[#0095FF] mt-1"></i> {{ $rule }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="space-y-8">
                <div class="sticky top-32 bg-white p-10 rounded-[40px] border border-slate-100 shadow-2xl shadow-blue-100 italic">
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-2">Harga Mulai Dari</p>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-black text-[#0095FF]">Rp1.200.000</span><span class="text-slate-400 font-bold">/bln</span>
                    </div>

                    <button class="w-full py-5 bg-[#0095FF] text-white rounded-2xl font-black text-xl shadow-lg hover:bg-blue-600 transition transform hover:scale-105 mb-6 active:scale-95">
                        Booking Sekarang
                    </button>

                    <div class="space-y-4 mb-10 text-xs font-bold text-slate-500">
                        <div class="flex items-center gap-3"><i data-lucide="clock" class="w-4 h-4 text-[#0095FF]"></i> Konfirmasi instan</div>
                        <div class="flex items-center gap-3"><i data-lucide="shield-check" class="w-4 h-4 text-[#0095FF]"></i> Pembayaran aman</div>
                        <div class="flex items-center gap-3"><i data-lucide="users" class="w-4 h-4 text-[#0095FF]"></i> Sudah dipercaya 124 mahasiswa</div>
                    </div>

                    <div class="border-t border-slate-100 pt-8">
                        <p class="text-xs text-slate-400 font-bold mb-4 uppercase italic">Tambah Layanan (Add-On)</p>
                        <div class="space-y-3 mb-10">
                            @foreach([['L' => 'Paket Katering Harian', 'P' => '450.000'], ['L' => 'Laundry Unlimited', 'P' => '150.000'], ['L' => 'Room Cleaning Service', 'P' => '100.000']] as $addon)
                            <label class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl cursor-pointer border border-transparent hover:border-blue-200 transition">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" class="w-5 h-5 rounded-md border-slate-300 text-[#0095FF] focus:ring-[#0095FF]">
                                    <div>
                                        <p class="text-xs font-black text-slate-800">{{ $addon['L'] }}</p>
                                        <p class="text-[10px] text-blue-500 font-bold">+Rp{{ $addon['P'] }}/bln</p>
                                    </div>
                                </div>
                                <span class="bg-yellow-400 text-[8px] font-black px-2 py-0.5 rounded text-white italic">POPULAR</span>
                            </label>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-4 p-6 bg-slate-50 rounded-3xl">
                            <img src="https://i.pravatar.cc/100?u=mawar" class="w-12 h-12 rounded-2xl object-cover shadow-md">
                            <div>
                                <p class="text-[10px] text-slate-400 font-black uppercase">Pemilik</p>
                                <p class="font-black text-slate-800">Bu Mawar</p>
                            </div>
                        </div>
                        <button class="w-full mt-4 py-3 border-2 border-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-50 transition italic">Hubungi Pemilik</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection