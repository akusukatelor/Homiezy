@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans italic" x-data="basicWizard">
    <div class="container mx-auto px-6">
        
        {{-- Header & Back Button --}}
        <div class="max-w-4xl mx-auto mb-8">
            <button @click="goBack()" class="flex items-center gap-2 text-slate-500 font-black hover:text-[#0095FF] transition group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition"></i>
                <span>Kembali ke Beranda</span>
            </button>
        </div>

        {{-- Progress Tracker --}}
        <div class="max-w-4xl mx-auto mb-20">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2"></div>
                <div class="absolute top-1/2 left-0 h-1 bg-[#0095FF] -translate-y-1/2 transition-all duration-500" 
                     :style="`width: ${step === 2 ? '33%' : '100%'}`"></div>
                
                @foreach(['Paket', 'Pilih Kos', 'Review', 'Selesai'] as $i => $label)
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black transition duration-500 shadow-sm"
                         :class="(step === 2 && {{ $i }} <= 1) || (step === 5 && {{ $i }} <= 2) ? 'bg-[#0095FF] text-white shadow-blue-200' : 'bg-white text-slate-400 border-2 border-slate-100'">
                        <template x-if="(step === 5 && {{ $i }} < 2)"><i data-lucide="check" class="w-6 h-6"></i></template>
                        <template x-if="!(step === 5 && {{ $i }} < 2)"><span x-text="{{ $i+1 }}"></span></template>
                    </div>
                    <span class="text-[10px] uppercase font-black tracking-widest" :class="step >= {{ $i+1 }} ? 'text-[#0095FF]' : 'text-slate-400'">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
            {{-- Bagian Utama Selection --}}
            <div class="lg:col-span-2 space-y-10">
                
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300">
                    <div class="mb-10">
                        <h2 class="text-4xl font-black mb-2 italic text-slate-800">Pilih Kos Basic 🏠</h2>
                        <p class="text-slate-400 font-bold mb-8">Pilih hunian yang paling cocok dengan budget mahasiswa.</p>
                        
                        {{-- Search & Filter --}}
                        <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 mb-8">
                            <div class="flex-1 relative">
                                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                                <input type="text" x-model="searchQuery" placeholder="Cari kos atau lokasi..." class="w-full pl-12 pr-4 py-3 bg-slate-50 rounded-2xl focus:outline-none italic text-sm border-none ring-0">
                            </div>
                            <div class="flex bg-slate-50 p-1.5 rounded-2xl">
                                <template x-for="g in ['Semua', 'Putra', 'Putri']">
                                    <button @click="filterGender = g" 
                                            :class="filterGender === g ? 'bg-[#0095FF] text-white shadow-md' : 'text-slate-500'" 
                                            class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition" x-text="g"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- List Card Kos --}}
                    <div class="grid gap-6">
                        <template x-for="item in filteredKos" :key="item.id">
                            <div @click="kos = item" 
                                 class="bg-white p-6 rounded-[40px] border-2 transition flex flex-col md:flex-row gap-8 cursor-pointer hover:shadow-xl group" 
                                 :class="kos?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                
                                {{-- Gambar dengan Fallback --}}
                                <div class="relative w-full md:w-64 h-44 overflow-hidden rounded-[32px] bg-slate-100 flex-shrink-0">
                                    <img :src="getImageUrl(item.image)" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black uppercase text-slate-700 shadow-sm" x-text="item.gender || 'Campur'"></span>
                                </div>

                                {{-- Informasi Lengkap (Sama dengan Seeder) --}}
                                <div class="flex-1 space-y-3">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-2xl font-black text-slate-800" x-text="item.name"></h3>
                                        <p class="text-[#0095FF] font-black text-2xl" x-text="'Rp ' + parseInt(item.price).toLocaleString()"></p>
                                    </div>
                                    <p class="text-slate-400 text-sm font-bold flex items-center gap-1 italic">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-[#0095FF]"></i> 
                                        <span x-text="item.location || 'Lokasi Purwokerto'"></span>
                                    </p>

                                    {{-- Fitur/Tags Dinamis dari CRUD --}}
                                    <div class="flex flex-wrap gap-2 pt-2">
                                        <template x-for="feature in (item.features || ['WiFi', 'Parkir'])">
                                            <span class="bg-blue-50 text-[#0095FF] px-4 py-1.5 rounded-full text-[10px] font-black uppercase italic" x-text="feature"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- STEP 5: REVIEW --}}
                <div x-show="step === 5" x-transition>
                    <h2 class="text-4xl font-black text-center mb-10 italic text-slate-800">Review Pesanan 📋</h2>
                    <div class="bg-white p-10 rounded-[48px] border border-slate-100 shadow-sm">
                        <template x-if="kos">
                            <div class="flex items-center justify-between p-8 bg-slate-50/50 rounded-[32px] border border-slate-100">
                                <div class="flex items-center gap-6">
                                    <img :src="getImageUrl(kos.image)" class="w-24 h-24 rounded-3xl object-cover border-4 border-white shadow-md">
                                    <div>
                                        <p class="font-black text-slate-800 text-xl italic" x-text="kos.name"></p>
                                        <p class="text-xs font-bold text-[#0095FF] uppercase tracking-widest mt-1">Paket Basic (Kos Saja)</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[#0095FF] font-black text-2xl" x-text="'Rp ' + parseInt(kos.price).toLocaleString()"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="sticky top-32">
                <div class="bg-white p-10 rounded-[50px] border border-slate-100 shadow-2xl shadow-blue-100/50 italic">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ringkasan Paket</h3>
                    <h2 class="text-4xl font-black text-[#0095FF] mb-12 uppercase">BASIC</h2>
                    
                    <div class="space-y-6 mb-12">
                        <div class="flex items-center gap-4 p-5 rounded-[28px] transition shadow-sm" 
                             :class="kos ? 'bg-blue-50 text-[#0095FF] border border-blue-100' : 'bg-slate-50 text-slate-300 border border-transparent'">
                            <i data-lucide="home" class="w-5 h-5"></i>
                            <span class="text-sm font-black truncate" x-text="kos ? kos.name : 'Kos belum dipilih...'"></span>
                        </div>
                    </div>

                    <div class="border-t-2 border-dashed border-slate-100 pt-8 space-y-4 mb-10">
                        <div class="flex justify-between text-slate-400 font-bold italic">
                            <span>Subtotal</span>
                            <span x-text="'Rp ' + subtotal.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between text-emerald-500 font-black italic">
                            <span>Bundle Discount (5%)</span>
                            <span x-text="'- Rp ' + discount.toLocaleString()"></span>
                        </div>
                        <div class="flex justify-between items-end pt-6">
                            <span class="text-slate-800 font-black text-xl italic leading-none">Total</span>
                            <span class="text-3xl font-black text-[#0095FF] leading-none" x-text="'Rp ' + total.toLocaleString()"></span>
                        </div>
                    </div>

                    <button @click="handleNext()" :disabled="!kos || isSaving" 
                            class="w-full py-6 rounded-[32px] font-black text-xl shadow-xl transition transform hover:scale-105 active:scale-95 text-white disabled:bg-slate-100 disabled:text-slate-400 bg-[#0095FF] shadow-blue-200">
                        <span x-text="isSaving ? 'Memproses...' : (step === 5 ? 'Konfirmasi & Bayar 🚀' : 'Lanjut ke Review')"></span>
                    </button>
                    <p class="text-center mt-6 text-[10px] text-slate-400 font-black uppercase tracking-widest italic">Aman • Cepat • Terpercaya</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div x-show="showSuccessModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm" x-transition>
        <div class="bg-white p-12 rounded-[56px] max-w-lg w-full text-center shadow-2xl">
            <div class="w-24 h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce shadow-inner">
                <i data-lucide="check-circle-2" class="w-12 h-12"></i>
            </div>
            <h2 class="text-4xl font-black text-slate-800 mb-4 italic">Alhamdulillah! 🚀</h2>
            <p class="text-slate-500 font-bold mb-10 italic leading-relaxed">Pesanan Paket Basic kamu sudah masuk. Tunggu kabar dari Mitra Kos ya!</p>
            <a href="{{ route('dashboard') }}" class="inline-block w-full py-5 bg-[#0095FF] text-white rounded-3xl font-black text-xl shadow-xl shadow-blue-200 italic hover:bg-blue-600 transition transform hover:scale-105">Ke Pesanan Saya</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('basicWizard', () => ({
            step: 2,
            showSuccessModal: false,
            searchQuery: '',
            filterGender: 'Semua',
            isSaving: false,
            
            // Data dari Laravel Controller
            kosItems: @json($kosItems),
            
            kos: null,
            discountRate: 0.05, // Diskon 5% untuk Paket Basic

            // Fungsi Penanganan Gambar (Anti-Pecah)
            getImageUrl(path) {
                if (!path) return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267';
                if (path.includes('http')) return path;
                if (path.includes('storage/')) return path.startsWith('/') ? path : '/' + path;
                return '/storage/services/' + path;
            },

            get filteredKos() {
                return this.kosItems.filter(item => {
                    const searchLower = this.searchQuery.toLowerCase();
                    const matchSearch = item.name.toLowerCase().includes(searchLower) || 
                                       (item.location && item.location.toLowerCase().includes(searchLower));
                    const matchGender = this.filterGender === 'Semua' || item.gender === this.filterGender;
                    return matchSearch && matchGender;
                });
            },

            get subtotal() { return (this.kos?.price || 0); },
            get discount() { return Math.round(this.subtotal * this.discountRate); },
            get total() { return this.subtotal - this.discount; },

            handleNext() {
                if (this.step === 2 && this.kos) {
                    this.step = 5;
                    // Refresh icon lucide setelah pindah step
                    setTimeout(() => { lucide.createIcons(); }, 10);
                } else if (this.step === 5) {
                    this.saveOrder();
                }
            },

            goBack() {
                if (this.step === 5) {
                    this.step = 2;
                    setTimeout(() => { lucide.createIcons(); }, 10);
                } else {
                    window.location.href = "{{ route('home') }}";
                }
            },

            async saveOrder() {
                this.isSaving = true;
                try {
                    const response = await fetch('{{ route('checkout.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name: 'Paket Basic - ' + this.kos.name,
                            price: this.total,    
                            type: 'bundling',
                            kos_id: this.kos.id,
                            kos_price: this.kos.price,
                            catering_price: 0,
                            laundry_price: 0
                        })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.showSuccessModal = true;
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                } catch (e) {
                    console.error(e);
                    alert('Koneksi bermasalah atau kamu belum login.');
                } finally {
                    this.isSaving = false;
                }
            }
        }));
    });
</script>
@endsection