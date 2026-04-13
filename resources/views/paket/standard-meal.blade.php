@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>
<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans italic" 
     x-data="{ 
        step: 2, 
        showSuccessModal: false,
        searchQuery: '', 
        filterGender: 'Semua',
        paymentMethod: 'va',
        
        // DATA MASTER DISINKRONKAN
        kosItems: [
            {id: 1, name: 'Kos Putri Mawar Residence', price: 1200000, type: 'Kos', gender: 'Putri', loc: 'Dekat Unsoed, Grendeng', dist: '500m dari Unsoed', rate: 4.8, rev: 124, match: '95%', verified: true, img: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267', tags: ['WiFi', 'AC', 'Kamar Mandi']},
            {id: 2, name: 'Kost Eksklusif Melati', price: 1500000, type: 'Kos', gender: 'Putri', loc: 'Margono, Purwokerto Selatan', dist: '300m dari RSUD Margono', rate: 4.9, rev: 89, match: '90%', verified: true, img: 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af', tags: ['WC', 'AC']},
            {id: 3, name: 'Kos Campur Hijau', price: 900000, type: 'Kos', gender: 'Putra', loc: 'Karangnanas, Purwokerto Timur', dist: '1.2km dari kampus UNU Purwokerto', rate: 4.5, rev: 42, match: '85%', verified: false, img: 'https://images.unsplash.com/photo-1493809842364-78817add7ffb', tags: ['Parkir Luas', 'Dapur']}
        ],

        cateringItems: [
            {
                id: 5, 
                name: 'Paket Katering Ekonomis', 
                price: 450000, 
                subtitle: 'Makan siang & malam untuk hari kerja',
                freq: '2x Makan',
                schedule: 'Senin - Sabtu',
                rate: '4.5',
                rev: '234',
                benefits: ['Menu bervariasi setiap hari', 'Lunch box tersedia', 'Porsi dapat disesuaikan', 'Pengiriman tepat waktu'],
                menuSamples: ['Ikan Bakar + Nasi + Tumis Kangkung', 'Rendang + Nasi + Perkedel', 'Ayam Goreng + Nasi + Sayur'],
                img: 'https://images.unsplash.com/photo-1547573854-74d2a71d0826'
            },
            {
                id: 6, 
                name: 'Paket Katering Premium', 
                price: 850000, 
                subtitle: 'Makan 3x sehari setiap hari',
                freq: '3x Makan',
                schedule: 'Setiap Hari',
                rate: '4.8',
                rev: '458',
                benefits: ['Menu premium & bergizi', 'Free snack 2x seminggu', 'Nutrisi terjamin', 'Pilihan menu vegetarian'],
                menuSamples: ['Sarapan: Nasi Uduk + Ayam + Telur', 'Makan Siang: Steak + Mashed Potato', 'Makan Malam: Salmon + Brown Rice'],
                img: 'https://images.unsplash.com/photo-1543332164-6e82f355badc'
            }
        ],

        laundryItems: [
    {
        id: 7, 
        name: 'Paket Laundry Hemat', 
        price: 88000, 
        capacity: 'Max 10kg', 
        freq: '2x seminggu', 
        rate: '4.5',
        benefits: ['Cuci bersih & wangi', 'Lipat teratur', 'Setrika rapi', 'Pick up & delivery gratis'],
        services: ['Cuci Kering', 'Setrika', 'Lipat'],
        img: 'https://images.unsplash.com/photo-1545173168-9f1947eebb7f'
    },
    {
        id: 8, 
        name: 'Paket Laundry Premium', 
        price: 150000, 
        capacity: 'Unlimited', 
        freq: 'Setiap hari (on-demand)', 
        rate: '4.9',
        benefits: ['Laundry tanpa batas', 'Parfum premium pilihan', 'Priority pick up & delivery', 'Express service (4 jam)', 'Dry cleaning tersedia'],
        services: ['Cuci Kering', 'Setrika', 'Lipat', 'Dry Cleaning', 'Express'],
        img: 'https://images.unsplash.com/photo-1517677208171-0bc6725a3e60'
    }
],

        // State Pilihan User
        kos: null, catering: null, laundry: null,
        selectedAddOns: [], 
        discountRate: 0.15,

        handleNext() {
            if (this.step === 2 && this.kos) {
                this.step = 3; // Lanjut ke Katering
            } else if (this.step === 3 && this.catering) {
                this.step = 5; // LANGSUNG LONCAT KE REVIEW (Skip Laundry)
            } else if (this.step === 5) {
                this.saveOrder();
            }
        },

        async saveOrder() {
            this.isSaving = true;
            try {
                const response = await fetch('{{ route('checkout.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: 'Paket Standard Meal - ' + this.kos.name,
                        price: this.total,
                        type: 'paket'
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.showSuccessModal = true;
                }
            } catch (error) {
                alert('Terjadi kesalahan saat menyimpan pesanan.');
                console.error(error);
            } finally {
                this.isSaving = false;
            }
        },

        // Logic Filter
        get filteredKos() {
            return this.kosItems.filter(item => {
                const matchSearch = item.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || item.loc.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchGender = this.filterGender === 'Semua' || item.gender === this.filterGender;
                return matchSearch && matchGender;
            });
        },

        // Logic Harga
        get subtotal() {
            let addOnSum = this.selectedAddOns.reduce((sum, item) => sum + item.price, 0);
            return (this.kos?.price || 0) + (this.catering?.price || 0) + (this.laundry?.price || 0) + addOnSum;
        },
        get discount() { return Math.round(this.subtotal * this.discountRate); },
        get total() { return this.subtotal - this.discount; },

        toggleAddOn(name, price) {
            const index = this.selectedAddOns.findIndex(a => a.name === name);
            if (index > -1) { this.selectedAddOns.splice(index, 1); }
            else { this.selectedAddOns.push({name, price}); }
        }
     }">
    
     <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto mb-8">
            <button 
                @click="step === 5 ? step = 3 : (step === 3 ? step = 2 : window.location.href='{{ route('home') }}')" 
                class="flex items-center gap-2 text-slate-500 font-black hover:text-[#0095FF] transition group"
            >
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition"></i>
                <span>Kembali</span>
            </button>
        </div>

        <div class="max-w-4xl mx-auto mb-20">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2"></div>
                <div class="absolute top-1/2 left-0 h-1 bg-[#0095FF] -translate-y-1/2 transition-all duration-500" :style="`width: ${(step-1)*25}%` "></div>
                @foreach(['Paket', 'Kos', 'Katering', 'Laundry', 'Bayar'] as $i => $label)
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black transition duration-500"
                         :class="step >= {{ $i+1 }} ? 'bg-[#0095FF] text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-400 border-2 border-slate-200'">
                        <template x-if="step > {{ $i+1 }}"><i data-lucide="check" class="w-6 h-6"></i></template>
                        <template x-if="step <= {{ $i+1 }}"><span x-text="{{ $i+1 }}"></span></template>
                    </div>
                    <span class="text-[10px] uppercase font-black tracking-widest" :class="step >= {{ $i+1 }} ? 'text-[#0095FF]' : 'text-slate-400'">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
            <div class="lg:col-span-2 space-y-10">
                
                <div x-show="step === 2" x-transition>
                    <div class="mb-10">
                        <h2 class="text-4xl font-black mb-2 italic">Pilih Kos Impianmu 🏠</h2>
                        <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4 mb-8">
                            <div class="flex-1 relative">
                                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                                <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan nama atau lokasi..." class="w-full pl-12 pr-4 py-3 bg-slate-50 rounded-2xl focus:outline-none italic">
                            </div>
                            <div class="flex bg-slate-50 p-1.5 rounded-2xl">
                                <template x-for="g in ['Semua', 'Putra', 'Putri']">
                                    <button @click="filterGender = g" :class="filterGender === g ? 'bg-[#0095FF] text-white shadow-md' : 'text-slate-500'" class="px-8 py-2 rounded-xl text-xs font-black transition" x-text="g"></button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-6 italic">
                        <template x-for="item in filteredKos" :key="item.id">
                            <div @click="kos = item" class="bg-white p-6 rounded-[40px] border-2 transition flex flex-col md:flex-row gap-8 cursor-pointer hover:shadow-xl" :class="kos?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                <div class="relative w-full md:w-64 h-44 overflow-hidden rounded-[32px]">
                                    <img :src="item.img" class="w-full h-full object-cover">
                                    <template x-if="item.verified"><span class="absolute top-4 left-4 bg-[#0095FF] text-white text-[8px] font-black px-3 py-1 rounded-full uppercase">Verified</span></template>
                                    <span class="absolute top-4 right-4 bg-white/95 text-[#0095FF] text-[10px] font-black px-3 py-1 rounded-full shadow-md italic" x-text="item.match + ' Match'"></span>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div class="flex justify-between items-start"><h3 class="text-2xl font-black" x-text="item.name"></h3><p class="text-[#0095FF] font-black text-2xl" x-text="'Rp ' + item.price.toLocaleString()"></p></div>
                                    <p class="text-slate-400 text-sm font-bold flex items-center gap-1"><i data-lucide="map-pin" class="w-4 h-4 text-[#0095FF]"></i> <span x-text="item.loc"></span> • <span class="text-[#0095FF]" x-text="item.dist"></span></p>
                                    <div class="flex items-center gap-2 text-yellow-400 font-bold text-xs"><i data-lucide="star" class="w-4 h-4 fill-current"></i> <span class="text-slate-800" x-text="item.rate"></span> <span class="text-slate-400" x-text="'(' + item.rev + ' ulasan)'"></span></div>
                                    <div class="flex flex-wrap gap-2 pt-2"><template x-for="tag in item.tags"><span class="bg-blue-50 text-[#0095FF] px-4 py-1.5 rounded-full text-[10px] font-black uppercase" x-text="tag"></span></template></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="step === 3" x-transition>
                    <div class="mb-10 flex justify-between items-end italic">
                        <div><h2 class="text-4xl font-black mb-2">Pilih Paket Katering 🍱</h2><p class="text-slate-400 font-medium">Makan teratur dengan menu bergizi setiap hari.</p></div>
                        <button @click="catering = null; step = 4" class="text-slate-400 font-bold border border-slate-200 px-6 py-2 rounded-xl italic">Lewati Step Ini</button>
                    </div>
                    <div class="grid gap-10 italic">
                        <template x-for="item in cateringItems" :key="item.id">
                            <div @click="catering = item" class="bg-white p-0 rounded-[48px] border-2 transition flex flex-col md:flex-row overflow-hidden cursor-pointer hover:shadow-2xl" :class="catering?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                <div class="w-full md:w-80 h-64 md:h-auto"><img :src="item.img" class="w-full h-full object-cover"></div>
                                <div class="flex-1 p-8 md:p-10 space-y-6">
                                    <div class="flex justify-between items-start">
                                        <div><h3 class="text-3xl font-black text-slate-800 mb-1" x-text="item.name"></h3><p class="text-slate-500 font-bold text-sm" x-text="item.subtitle"></p></div>
                                        <div class="text-right"><p class="text-[#0095FF] font-black text-3xl" x-text="'Rp ' + item.price.toLocaleString()"></p><p class="text-slate-400 text-[10px] font-black uppercase">/bulan</p></div>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <div class="bg-slate-50 px-4 py-1.5 rounded-xl flex items-center gap-2 text-slate-700 text-xs font-bold border border-slate-100"><i data-lucide="utensils-2" class="w-4 h-4 text-[#0095FF]"></i> <span x-text="item.freq"></span></div>
                                        <div class="bg-slate-50 px-4 py-1.5 rounded-xl flex items-center gap-2 text-slate-700 text-xs font-bold border border-slate-100"><i data-lucide="calendar" class="w-4 h-4 text-[#0095FF]"></i> <span x-text="item.schedule"></span></div>
                                        <div class="bg-slate-50 px-4 py-1.5 rounded-xl flex items-center gap-2 text-slate-700 text-xs font-bold border border-slate-100"><i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-current"></i> <span x-text="item.rate + ' (' + item.rev + ')'"></span></div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-y-3 gap-x-6"><template x-for="benefit in item.benefits"><div class="flex items-center gap-2 text-slate-600 text-sm font-bold"><i data-lucide="check" class="w-4 h-4 text-[#0095FF]"></i> <span x-text="benefit"></span></div></template></div>
                                    <div class="bg-[#F0FDF4] p-6 rounded-3xl border border-[#DCFCE7] space-y-3">
                                        <p class="text-[#166534] text-xs font-black uppercase tracking-widest">💡 Contoh Menu:</p>
                                        <ul class="space-y-1.5"><template x-for="menu in item.menuSamples"><li class="text-[#15803D] text-sm font-bold flex items-start gap-2"><span>•</span> <span x-text="menu"></span></li></template></ul>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="step === 4" x-transition>
                    <div class="mb-10 flex justify-between items-end italic">
                        <div><h2 class="text-4xl font-black mb-2">Pilih Laundry 👕</h2><p class="text-slate-400 font-medium">Pakaian bersih & wangi setiap hari tanpa repot.</p></div>
                        <button @click="laundry = null; step = 5" class="text-slate-400 font-bold border border-slate-200 px-6 py-2 rounded-xl italic">Lewati</button>
                    </div>
                    <div class="grid gap-10 italic">
                        <template x-for="item in laundryItems" :key="item.id">
                            <div @click="laundry = item" class="bg-white p-0 rounded-[48px] border-2 transition flex flex-col md:flex-row overflow-hidden cursor-pointer hover:shadow-2xl" :class="laundry?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                <div class="w-full md:w-80 h-64 md:h-auto"><img :src="item.img" class="w-full h-full object-cover"></div>
                                <div class="flex-1 p-8 md:p-10 space-y-6">
                                    <div class="flex justify-between items-start"><div><h3 class="text-3xl font-black text-slate-800 mb-1" x-text="item.name"></h3><p class="text-slate-500 font-bold text-sm italic">Layanan cuci setrika express</p></div><div class="text-right"><p class="text-[#0095FF] font-black text-3xl" x-text="'Rp ' + item.price.toLocaleString()"></p><p class="text-slate-400 text-[10px] font-black uppercase">/bulan</p></div></div>
                                    <div class="flex flex-wrap gap-3"><div class="bg-slate-50 px-4 py-1.5 rounded-xl flex items-center gap-2 text-slate-700 text-xs font-bold border border-slate-100"><i data-lucide="package" class="w-4 h-4 text-[#0095FF]"></i> <span x-text="item.capacity"></span></div><div class="bg-slate-50 px-4 py-1.5 rounded-xl flex items-center gap-2 text-slate-700 text-xs font-bold border border-slate-100"><i data-lucide="star" class="w-4 h-4 text-yellow-400 fill-current"></i> <span x-text="item.rate"></span></div></div>
                                    <div class="grid grid-cols-2 gap-y-3 gap-x-6"><template x-for="benefit in item.benefits"><div class="flex items-center gap-2 text-slate-600 text-sm font-bold"><i data-lucide="check" class="w-4 h-4 text-[#0095FF]"></i> <span x-text="benefit"></span></div></template></div>
                                    <div class="bg-blue-50/50 p-6 rounded-3xl border border-blue-100 space-y-3"><p class="text-[#0095FF] text-xs font-black uppercase italic">Layanan Tersedia:</p><div class="flex flex-wrap gap-2"><template x-for="svc in item.services"><span class="bg-white px-3 py-1 rounded-lg text-[10px] font-black text-slate-500 border border-slate-200" x-text="svc"></span></template></div></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="step === 5" x-transition class="space-y-10">
    <h2 class="text-3xl md:text-4xl font-black text-center mb-10 italic">Review & Konfirmasi 📋</h2>
    
    <div class="bg-white p-6 md:p-10 rounded-[48px] border border-slate-100 italic space-y-6 shadow-sm">
        <h4 class="font-black text-xl md:text-2xl text-slate-800 flex items-center gap-3 italic">
            <i data-lucide="shopping-bag" class="text-[#0095FF] w-6 h-6"></i> Layanan Pilihan Kamu
        </h4>
        
        <div class="space-y-4">
            <template x-if="kos">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 md:p-6 bg-slate-50/50 rounded-3xl border border-slate-100 gap-4">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <img :src="kos.img" class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover border-2 border-white shadow-sm flex-shrink-0">
                        <div class="min-w-0">
                            <p class="font-black text-slate-800 text-base md:text-lg truncate" x-text="kos.name"></p>
                            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider">Layanan: Tempat Tinggal</p>
                        </div>
                    </div>
                    <p class="text-[#0095FF] font-black text-lg md:text-xl whitespace-nowrap self-end sm:self-center" 
                       x-text="'Rp ' + kos.price.toLocaleString()"></p>
                </div>
            </template>

            <template x-if="catering">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 md:p-6 bg-slate-50/50 rounded-3xl border border-slate-100 gap-4">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <img :src="catering.img" class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover border-2 border-white shadow-sm flex-shrink-0">
                        <div class="min-w-0">
                            <p class="font-black text-slate-800 text-base md:text-lg truncate" x-text="catering.name"></p>
                            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider">Layanan: Makan & Gizi</p>
                        </div>
                    </div>
                    <p class="text-[#0095FF] font-black text-lg md:text-xl whitespace-nowrap self-end sm:self-center" 
                       x-text="'Rp ' + catering.price.toLocaleString()"></p>
                </div>
            </template>

            <template x-if="laundry">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 md:p-6 bg-slate-50/50 rounded-3xl border border-slate-100 gap-4">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <img :src="laundry.img" class="w-16 h-16 md:w-20 md:h-20 rounded-2xl object-cover border-2 border-white shadow-sm flex-shrink-0">
                        <div class="min-w-0">
                            <p class="font-black text-slate-800 text-base md:text-lg truncate" x-text="laundry.name"></p>
                            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider">Layanan: Kebersihan Pakaian</p>
                        </div>
                    </div>
                    <p class="text-[#0095FF] font-black text-lg md:text-xl whitespace-nowrap self-end sm:self-center" 
                       x-text="'Rp ' + laundry.price.toLocaleString()"></p>
                </div>
            </template>
        </div>
    </div>
</div>
            </div>

            <div class="sticky top-32">
                <div class="bg-white p-10 rounded-[50px] border border-slate-100 shadow-2xl shadow-blue-100 italic">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ringkasan Paket</h3>
                    <h2 class="text-4xl font-black text-[#0095FF] mb-12 uppercase">Standard Meal</h2>
                    <div class="space-y-6 mb-12">
                        <div class="flex items-center gap-4 p-5 rounded-[32px] transition" :class="kos ? 'bg-blue-50 text-[#0095FF]' : 'bg-slate-50 text-slate-300'"><i data-lucide="home" class="w-5 h-5"></i><span class="text-sm font-black" x-text="kos ? kos.name : 'Belum memilih kos...'"></span></div>
                        <div class="flex items-center gap-4 p-5 rounded-[32px] transition" :class="catering ? 'bg-blue-50 text-[#0095FF]' : 'bg-slate-50 text-slate-300'"><i data-lucide="utensils" class="w-5 h-5"></i><span class="text-sm font-black" x-text="catering ? catering.name : 'Tanpa katering'"></span></div>
                        <div class="flex items-center gap-4 p-5 rounded-[32px] transition" :class="laundry ? 'bg-blue-50 text-[#0095FF]' : 'bg-slate-50 text-slate-300'"><i data-lucide="shirt" class="w-5 h-5"></i><span class="text-sm font-black" x-text="laundry ? laundry.name : 'Tanpa laundry'"></span></div>
                    </div>
                    <div class="border-t border-slate-50 pt-8 space-y-4 mb-10">
                        <div class="flex justify-between text-slate-400 font-bold"><span>Subtotal</span><span x-text="'Rp ' + subtotal.toLocaleString()"></span></div>
                        <div class="flex justify-between text-emerald-500 font-black italic"><span>Bundling Discount</span><span x-text="'- Rp ' + discount.toLocaleString()"></span></div>
                        <div class="flex justify-between items-end pt-4 border-t-2 border-dashed border-slate-100"><span class="text-slate-800 font-black text-xl">Total Bayar</span><span class="text-3xl font-black text-[#0095FF]" x-text="'Rp ' + total.toLocaleString()"></span></div>
                    </div>
                 <button @click="handleNext()" :disabled="(step === 2 && !kos) || (step === 3 && !catering)" 
                            class="w-full py-6 rounded-[32px] font-black text-xl shadow-xl transition transform hover:scale-105 active:scale-95 text-white" 
                            :class="((step === 2 && !kos) || (step === 3 && !catering)) ? 'bg-slate-200' : 'bg-[#0095FF] shadow-blue-200'">
                        <span x-text="step === 5 ? 'Konfirmasi & Bayar' : 'Lanjutkan Langkah'"></span>
                    </button>
                    <p class="text-center mt-6 text-[10px] text-slate-400 font-black uppercase tracking-widest">🔒 Pembayaran Aman & Terenkripsi</p>
                </div>
            </div>
        </div>
        
    </div>
    <div x-show="showSuccessModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="bg-white p-12 rounded-[56px] max-w-lg w-full text-center shadow-2xl transform transition-all"
             x-show="showSuccessModal"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="scale-90 opacity-0"
             x-transition:enter-end="scale-100 opacity-100">
            
            <div class="w-24 h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner animate-bounce">
                <i data-lucide="check-circle-2" class="w-12 h-12"></i>
            </div>

            <h2 class="text-4xl font-black text-slate-800 mb-4 italic text-center">Pembayaran Berhasil! 🚀</h2>
            <p class="text-slate-500 font-bold mb-10 leading-relaxed italic text-sm text-center text-sm">
                Terima kasih! Pesanan Standard Meal kamu sudah kami terima. Tim kami akan segera menghubungi kamu melalui WhatsApp.
            </p>

            <a href="{{ route('home') }}" 
               class="inline-block w-full py-5 bg-[#0095FF] text-white rounded-3xl font-black text-xl shadow-xl hover:bg-blue-600 transition transform hover:scale-105 active:scale-95 shadow-blue-200 italic text-center">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection