@extends('layouts.app')

@section('content')
<div class="font-sans italic">

    <section class="relative min-h-[700px] flex items-center justify-center pt-20 overflow-hidden">
        <div class="absolute inset-0 bg-[#0095FF]/80 z-10"></div>
        <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?q=80&w=2074" class="absolute inset-0 w-full h-full object-cover z-0" alt="Partnership">
        
        <div class="container mx-auto px-6 relative z-20 text-center text-white">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-md px-6 py-2 rounded-full border border-white/30 mb-8">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Mitra Bisnis</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight tracking-tighter italic">
                Kembangkan Bisnis <br> Anda Bersama Homiezy
            </h1>
            <p class="text-xl md:text-2xl mb-12 opacity-90 max-w-3xl mx-auto font-medium">
                Jangkau ribuan mahasiswa dan tingkatkan omset bisnis kos, katering, atau laundry Anda
            </p>
            
            <div class="flex flex-col items-center gap-4">
                <a href="#" class="bg-white text-[#0095FF] px-12 py-5 rounded-2xl font-black text-xl shadow-2xl hover:bg-blue-50 transition transform hover:scale-105">
                    Daftar Sebagai Mitra
                </a>
                <p class="text-sm font-bold tracking-wide">Gratis untuk 100 mitra pertama! 🎉</p>
            </div>
        </div>
    </section>

    <section class="py-32 bg-white" data-aos="fade-up" data-aos-delay="100">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-5xl font-black mb-4 tracking-tight italic">Mengapa Bergabung?</h2>
            <p class="text-slate-400 text-xl mb-24 font-medium italic">Ratusan mitra sudah merasakan manfaatnya</p>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                @foreach([
                    ['icon' => 'users', 'title' => 'Akses ke 10,000+ Mahasiswa', 'desc' => 'Jangkau ribuan mahasiswa yang sedang mencari tempat kos, katering, atau laundry'],
                    ['icon' => 'trending-up', 'title' => 'Tingkatkan Omset', 'desc' => 'Partner kami rata-rata mengalami peningkatan omset hingga 40% dalam 3 bulan pertama'],
                    ['icon' => 'layout-dashboard', 'title' => 'Dashboard Lengkap', 'desc' => 'Kelola pesanan, tracking pembayaran, dan lihat analytics bisnis dalam satu platform'],
                    ['icon' => 'shield-check', 'title' => 'Pembayaran Aman & Cepat', 'desc' => 'Sistem pembayaran otomatis langsung ke rekening Anda setiap minggu']
                ] as $item)
                <div class="p-10 rounded-[40px] bg-white border border-slate-50 shadow-[0_15px_50px_rgba(0,0,0,0.03)] text-left group hover:bg-[#0095FF] transition duration-500">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0095FF] mb-8 group-hover:bg-white/20 group-hover:text-white transition italic">
                        <i data-lucide="{{ $item['icon'] }}" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-black mb-4 group-hover:text-white italic">{{ $item['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed group-hover:text-white/80 italic">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-32 bg-[#F8FAFC]" data-aos="fade-up" data-aos-delay="100">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-5xl font-black mb-4 tracking-tighter italic">Cara Bergabung</h2>
            <p class="text-slate-400 text-xl mb-24 font-medium italic">Mudah dan cepat, hanya 3 langkah</p>
            
            <div class="relative max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-12 mb-24">
                <div class="hidden md:block absolute top-12 left-0 w-full h-1 bg-blue-100 z-0"></div>
                
                @foreach([
                    ['num' => '1', 'title' => 'Daftar & Verifikasi', 'desc' => 'Isi formulir pendaftaran dan upload dokumen bisnis'],
                    ['num' => '2', 'title' => 'Setup Listing', 'desc' => 'Tambahkan foto, harga, dan deskripsi layanan Anda'],
                    ['num' => '3', 'title' => 'Mulai Menerima Pesanan', 'desc' => 'Listing langsung tayang dan mulai terima pelanggan baru']
                ] as $step)
                <div class="relative z-10 flex flex-col items-center max-w-[250px]">
                    <div class="w-24 h-24 bg-[#0095FF] text-white rounded-full flex items-center justify-center text-3xl font-black shadow-xl shadow-blue-200 mb-8 italic">
                        {{ $step['num'] }}
                    </div>
                    <h4 class="text-xl font-black mb-4 italic">{{ $step['title'] }}</h4>
                    <p class="text-slate-400 text-sm italic">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>
            
            <button class="bg-[#0095FF] text-white px-16 py-5 rounded-2xl font-black text-xl shadow-xl shadow-blue-200 hover:bg-blue-600 transition italic">
                Mulai Daftar Sekarang
            </button>
        </div>
    </section>

    <section class="py-32 bg-white" data-aos="fade-up" data-aos-delay="100">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-5xl font-black mb-24 italic">Kata Mitra Kami</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-6xl mx-auto mb-32">
                @foreach([
                    ['name' => 'Ria Ricis', 'role' => 'Pemilik Dapur Mama Ricis', 'img' => 'https://i.pravatar.cc/150?u=ria', 'quote' => '"Sejak bergabung dengan Homiezy, tingkat okupansi kos saya naik dari 60% jadi 95%. Platform ini sangat membantu!"'],
                    ['name' => 'Pak Raffi Ahmad', 'role' => 'Kos Eksklusif Melati', 'img' => 'https://i.pravatar.cc/150?u=raffi', 'quote' => '"Sistem order otomatis dan payment yang cepat bikin bisnis saya lebih efisien. Highly recommended!"']
                ] as $testi)
                <div class="p-12 rounded-[50px] bg-white border border-slate-50 shadow-[0_20px_60px_rgba(0,0,0,0.05)] text-left italic">
                    <div class="flex items-center gap-6 mb-8">
                        <img src="{{ $testi['img'] }}" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-xl">
                        <div>
                            <h4 class="text-xl font-black italic">{{ $testi['name'] }}</h4>
                            <p class="text-[#0095FF] font-bold text-xs uppercase tracking-widest">{{ $testi['role'] }}</p>
                            <div class="flex text-yellow-400 mt-2">
                                @for($i=0; $i<5; $i++) <i data-lucide="star" class="w-4 h-4 fill-current"></i> @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-500 text-lg leading-relaxed italic">{{ $testi['quote'] }}</p>
                </div>
                @endforeach
            </div>
            
            <div class="bg-[#0095FF] rounded-[60px] p-20 text-white relative overflow-hidden italic shadow-2xl">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black mb-6 italic">Siap Kembangkan Bisnis Anda?</h3>
                    <p class="text-xl opacity-90 mb-12 italic">Bergabunglah dengan 500+ mitra yang sudah sukses meningkatkan omset mereka</p>
                    <button class="bg-white text-[#0095FF] px-12 py-5 rounded-2xl font-black text-xl hover:bg-blue-50 transition shadow-xl italic flex items-center gap-3 mx-auto">
                        <i data-lucide="check-circle" class="w-6 h-6"></i> Daftar Gratis Sekarang
                    </button>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection