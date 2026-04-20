@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans italic" x-data="premiumWizard">
    <div class="container mx-auto px-6">
        
        {{-- Tombol Kembali --}}
        <div class="max-w-4xl mx-auto mb-8">
            <button @click="goBack()" class="flex items-center gap-2 text-slate-500 font-black hover:text-[#0095FF] transition group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition"></i>
                <span>Kembali</span>
            </button>
        </div>

        {{-- Progress Tracker (Lengkap 5 Step) --}}
        <div class="max-w-4xl mx-auto mb-20">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2"></div>
                <div class="absolute top-1/2 left-0 h-1 bg-[#0095FF] -translate-y-1/2 transition-all duration-500" 
                     :style="`width: ${(step-1)*25}%` "></div>
                
                @foreach(['Paket', 'Pilih Kos', 'Pilih Makan', 'Pilih Laundry', 'Bayar'] as $i => $label)
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black transition duration-500 shadow-sm"
                         :class="step >= {{ $i+1 }} ? 'bg-[#0095FF] text-white shadow-blue-200' : 'bg-white text-slate-300 border-2 border-slate-100'">
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
                
                {{-- STEP 2: PILIH KOS --}}
                <div x-show="step === 2" x-transition>
                    <h2 class="text-4xl font-black mb-8 italic text-slate-800">1. Pilih Hunian Kos 🏠</h2>
                    <div class="grid gap-6">
                        <template x-for="item in kosItems" :key="item.id">
                            <div @click="kos = item" 
                                 class="bg-white p-6 rounded-[40px] border-2 transition flex flex-col md:flex-row gap-8 cursor-pointer hover:shadow-xl group" 
                                 :class="kos?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                <div class="relative w-full md:w-64 h-44 overflow-hidden rounded-[32px] bg-slate-100 flex-shrink-0">
                                    <img :src="getImageUrl(item.image)" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black uppercase text-slate-700 shadow-sm" x-text="item.gender || 'Campur'"></span>
                                </div>
                                <div class="flex-1 space-y-3">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-2xl font-black text-slate-800" x-text="item.name"></h3>
                                        <p class="text-[#0095FF] font-black text-2xl" x-text="'Rp ' + parseInt(item.price).toLocaleString()"></p>
                                    </div>
                                    <p class="text-slate-400 text-sm font-bold flex items-center gap-1 italic">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-[#0095FF]"></i> <span x-text="item.location"></span>
                                    </p>
                                    <div class="flex flex-wrap gap-2 pt-2">
                                        <template x-for="f in (item.features || [])"><span class="bg-blue-50 text-[#0095FF] px-4 py-1.5 rounded-full text-[10px] font-black uppercase italic" x-text="f"></span></template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- STEP 3: PILIH KATERING --}}
                <div x-show="step === 3" x-transition>
                    <h2 class="text-4xl font-black mb-8 italic text-slate-800">2. Pilih Katering Makan 🍱</h2>
                    <div class="grid gap-8">
                        <template x-for="item in cateringItems" :key="item.id">
                            <div @click="catering = item" 
                                 class="bg-white p-0 rounded-[48px] border-2 transition flex flex-col md:flex-row overflow-hidden cursor-pointer hover:shadow-2xl group" 
                                 :class="catering?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                <div class="w-full md:w-72 h-64 md:h-auto overflow-hidden bg-emerald-50 flex-shrink-0">
                                    <img :src="getImageUrl(item.image)" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                </div>
                                <div class="flex-1 p-8 md:p-10 space-y-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-3xl font-black text-slate-800" x-text="item.name"></h3>
                                            <p class="text-emerald-500 font-black text-sm italic mt-1" x-text="item.subtitle || 'Menu Bergizi'"></p>
                                        </div>
                                        <p class="text-[#0095FF] font-black text-3xl" x-text="'Rp ' + parseInt(item.price).toLocaleString()"></p>
                                    </div>
                                    <div class="flex flex-wrap gap-3">
                                        <template x-for="f in (item.features || [])">
                                            <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-xl text-[10px] font-black italic" x-text="f"></span>
                                        </template>
                                    </div>
                                    <div class="bg-[#F0FDF4] p-6 rounded-3xl border border-[#DCFCE7] space-y-2">
                                        <p class="text-[#166534] text-[10px] font-black uppercase tracking-widest">Contoh Menu:</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4"><template x-for="menu in (item.extra_info || [])"><li class="text-[#15803D] text-xs font-bold list-none flex items-center gap-2"><span>•</span> <span x-text="menu"></span></li></template></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- STEP 4: PILIH LAUNDRY --}}
                <div x-show="step === 4" x-transition>
                    <h2 class="text-4xl font-black mb-8 italic text-slate-800">3. Pilih Layanan Laundry 👕</h2>
                    <div class="grid gap-8">
                        <template x-for="item in laundryItems" :key="item.id">
                            <div @click="laundry = item" 
                                 class="bg-white p-0 rounded-[48px] border-2 transition flex flex-col md:flex-row overflow-hidden cursor-pointer hover:shadow-2xl group" 
                                 :class="laundry?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                <div class="w-full md:w-72 h-64 md:h-auto overflow-hidden bg-indigo-50 flex-shrink-0 relative">
                                    <img :src="getImageUrl(item.image)" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                </div>
                                <div class="flex-1 p-8 md:p-10 space-y-6">
                                    <div class="flex justify-between items-start">
                                        <div><h3 class="text-3xl font-black text-slate-800" x-text="item.name"></h3><p class="text-indigo-500 font-black text-sm italic mt-1" x-text="item.subtitle || 'Express Service'"></p></div>
                                        <p class="text-[#0095FF] font-black text-3xl" x-text="'Rp ' + parseInt(item.price).toLocaleString()"></p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100"><p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kapasitas</p><p class="text-xs font-bold text-slate-800" x-text="item.extra_info?.capacity || 'Standard'"></p></div>
                                        <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100"><p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jemput</p><p class="text-xs font-bold text-slate-800" x-text="item.extra_info?.frequency || 'On-demand'"></p></div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="f in (item.features || [])"><span class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-xl text-[10px] font-black italic" x-text="f"></span></template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- STEP 5: REVIEW --}}
                <div x-show="step === 5" x-transition class="space-y-10">
                    <h2 class="text-4xl font-black text-center italic">Review Premium Bundle 📋</h2>
                    <div class="bg-white p-10 rounded-[48px] border border-slate-100 shadow-sm space-y-6">
                        <template x-for="item in [kos, catering, laundry]" :key="item?.id">
                            <template x-if="item">
                                <div class="flex items-center justify-between p-6 bg-slate-50/50 rounded-3xl border border-slate-100">
                                    <div class="flex items-center gap-6"><img :src="getImageUrl(item.image)" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-sm"><p class="font-black text-slate-800 text-lg italic" x-text="item.name"></p></div>
                                    <p class="text-[#0095FF] font-black text-xl" x-text="'Rp ' + parseInt(item.price).toLocaleString()"></p>
                                </div>
                            </template>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Sidebar Ringkasan --}}
            <div class="sticky top-32">
                <div class="bg-white p-10 rounded-[50px] border border-slate-100 shadow-2xl shadow-blue-100 italic">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Layanan Premium</h3>
                    <h2 class="text-4xl font-black text-[#0095FF] mb-12 uppercase tracking-tighter">FULL PACK</h2>
                    
                    <div class="space-y-6 mb-12">
                        <div class="flex items-center gap-4 p-5 rounded-[28px] transition" :class="kos ? 'bg-blue-50 text-[#0095FF]' : 'bg-slate-50 text-slate-300'"><i data-lucide="home"></i><span class="text-xs font-black truncate" x-text="kos ? kos.name : 'Pilih Kos...'"></span></div>
                        <div class="flex items-center gap-4 p-5 rounded-[28px] transition" :class="catering ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-300'"><i data-lucide="utensils-2"></i><span class="text-xs font-black truncate" x-text="catering ? catering.name : 'Pilih Katering...'"></span></div>
                        <div class="flex items-center gap-4 p-5 rounded-[28px]" :class="laundry ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-50 text-slate-300'"><i data-lucide="shirt"></i><span class="text-xs font-black truncate" x-text="laundry ? laundry.name : 'Pilih Laundry...'"></span></div>
                    </div>

                    <div class="border-t-2 border-dashed border-slate-100 pt-8 space-y-4 mb-10">
                        <div class="flex justify-between text-emerald-500 font-black italic"><span>Premium Discount (20%)</span><span x-text="'- Rp ' + discount.toLocaleString()"></span></div>
                        <div class="flex justify-between items-end pt-6 border-t border-slate-50">
                            <span class="text-slate-800 font-black text-xl">Total Bayar</span>
                            <span class="text-3xl font-black text-[#0095FF]" x-text="'Rp ' + total.toLocaleString()"></span>
                        </div>
                    </div>

                    <button @click="handleNext()" :disabled="(step === 2 && !kos) || (step === 3 && !catering) || (step === 4 && !laundry) || isSaving" 
                            class="w-full py-6 rounded-[32px] font-black text-xl shadow-xl transition transform hover:scale-105 active:scale-95 text-white bg-[#0095FF] shadow-blue-200">
                        <span x-text="isSaving ? 'Processing...' : (step === 5 ? 'Pesan Sekarang 🚀' : 'Lanjutkan')"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Sukses --}}
    <div x-show="showSuccessModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white p-12 rounded-[56px] max-w-lg w-full text-center shadow-2xl">
            <div class="w-24 h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce"><i data-lucide="award" class="w-12 h-12"></i></div>
            <h2 class="text-4xl font-black text-slate-800 mb-4 italic">Premium Terpilih! 👑</h2>
            <p class="text-slate-500 font-bold mb-10 italic">Lengkap! Pesanan Kos, Katering, dan Laundry kamu sudah dikirim ke semua mitra. Selamat menikmati hidup praktis!</p>
            <a href="{{ route('dashboard') }}" class="inline-block w-full py-5 bg-[#0095FF] text-white rounded-3xl font-black text-xl shadow-xl italic shadow-blue-200">Lihat Status</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('premiumWizard', () => ({
            step: 2,
            showSuccessModal: false,
            isSaving: false,
            
            kosItems: @json($kosItems),
            cateringItems: @json($cateringItems),
            laundryItems: @json($laundryItems),

            kos: null,
            catering: null,
            laundry: null,
            discountRate: 0.20, // Diskon 20% untuk Paket Premium

            getImageUrl(path) {
                if (!path) return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267';
                if (path.includes('http')) return path;
                if (path.includes('storage/')) return path.startsWith('/') ? path : '/' + path;
                return '/storage/services/' + path;
            },

            get subtotal() { return (this.kos?.price || 0) + (this.catering?.price || 0) + (this.laundry?.price || 0); },
            get discount() { return Math.round(this.subtotal * this.discountRate); },
            get total() { return this.subtotal - this.discount; },

            handleNext() {
                if (this.step < 4) this.step++;
                else if (this.step === 4) this.step = 5;
                else if (this.step === 5) this.saveOrder();
                
                setTimeout(() => { lucide.createIcons(); }, 10);
            },

            goBack() {
                if (this.step > 2) this.step--;
                else window.location.href = "{{ route('home') }}";
                
                setTimeout(() => { lucide.createIcons(); }, 10);
            },

            async saveOrder() {
                this.isSaving = true;
                try {
                    const response = await fetch('{{ route('checkout.store') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({
                            name: 'Paket PREMIUM - ' + this.kos.name,
                            price: this.total,
                            type: 'bundling',
                            kos_id: this.kos.id,
                            catering_id: this.catering.id,
                            laundry_id: this.laundry.id,
                            kos_price: this.kos.price,
                            catering_price: this.catering.price,
                            laundry_price: this.laundry.price
                        })
                    });
                    const data = await response.json();
                    if (data.success) this.showSuccessModal = true;
                } catch (e) { alert('Terjadi kesalahan memproses pesanan.'); } 
                finally { this.isSaving = false; }
            }
        }));
    });
</script>
@endsection