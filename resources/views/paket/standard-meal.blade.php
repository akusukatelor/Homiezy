@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans italic" x-data="mealWizard">
    <div class="container mx-auto px-6">
        
        {{-- Back Button --}}
        <div class="max-w-4xl mx-auto mb-8">
            <button @click="goBack()" class="flex items-center gap-2 text-slate-500 font-black hover:text-[#0095FF] transition group">
                <i data-lucide="arrow-left" class="w-5 h-5 group-hover:-translate-x-1 transition"></i>
                <span>Kembali</span>
            </button>
        </div>

        {{-- Progress Tracker (Meal: Skip Step 4 Laundry) --}}
        <div class="max-w-4xl mx-auto mb-20">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-200 -translate-y-1/2"></div>
                <div class="absolute top-1/2 left-0 h-1 bg-[#0095FF] -translate-y-1/2 transition-all duration-500" 
                     :style="`width: ${step === 2 ? '25%' : (step === 3 ? '50%' : '100%')}`"></div>
                
                @foreach(['Paket', 'Pilih Kos', 'Pilih Makan', 'Laundry', 'Review'] as $i => $label)
                @php $stepNum = $i + 1; @endphp
                <div class="relative z-10 flex flex-col items-center gap-3 {{ $stepNum === 4 ? 'opacity-20' : '' }}">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-black transition duration-500 shadow-sm"
                         :class="step >= {{ $stepNum }} ? 'bg-[#0095FF] text-white shadow-blue-200' : 'bg-white text-slate-400 border-2 border-slate-100'">
                        <template x-if="step > {{ $stepNum }} || (step === 5 && {{ $stepNum }} === 4)">
                            <i data-lucide="check" class="w-6 h-6"></i>
                        </template>
                        <template x-if="!(step > {{ $stepNum }} || (step === 5 && {{ $stepNum }} === 4))">
                            <span x-text="{{ $stepNum }}"></span>
                        </template>
                    </div>
                    <span class="text-[10px] uppercase font-black tracking-widest" :class="step >= {{ $stepNum }} ? 'text-[#0095FF]' : 'text-slate-400'">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
            <div class="lg:col-span-2 space-y-10">
                
                {{-- STEP 2: PILIH KOS --}}
                <div x-show="step === 2" x-transition>
                    <h2 class="text-4xl font-black mb-8 italic text-slate-800">1. Pilih Kos 🏠</h2>
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

                {{-- STEP 3: PILIH KATERING (DETAIL LENGKAP) --}}
                <div x-show="step === 3" x-transition>
                    <h2 class="text-4xl font-black mb-8 italic text-slate-800">2. Pilih Katering Sehat 🍱</h2>
                    <div class="grid gap-8">
                        <template x-for="item in cateringItems" :key="item.id">
                            <div @click="catering = item" 
                                 class="bg-white p-0 rounded-[48px] border-2 transition flex flex-col md:flex-row overflow-hidden cursor-pointer hover:shadow-2xl group" 
                                 :class="catering?.id === item.id ? 'border-[#0095FF] ring-4 ring-blue-50' : 'border-slate-50'">
                                
                                {{-- Visual Katering --}}
                                <div class="w-full md:w-80 h-64 md:auto overflow-hidden bg-emerald-50 flex-shrink-0 relative">
                                    <img :src="getImageUrl(item.image)" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                </div>

                                {{-- Informasi Detail Katering --}}
                                <div class="flex-1 p-8 md:p-10 space-y-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-3xl font-black text-slate-800" x-text="item.name"></h3>
                                            <p class="text-emerald-500 font-black text-sm italic mt-1" x-text="item.subtitle || 'Menu Bergizi Setiap Hari'"></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[#0095FF] font-black text-3xl" x-text="'Rp ' + parseInt(item.price).toLocaleString()"></p>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">/bulan</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-3">
                                        <div class="bg-slate-50 px-4 py-1.5 rounded-xl flex items-center gap-2 text-slate-700 text-xs font-bold border border-slate-100">
                                            <i data-lucide="calendar" class="w-4 h-4 text-emerald-500"></i> <span x-text="item.schedule || 'Setiap Hari'"></span>
                                        </div>
                                        <template x-for="f in (item.features || [])">
                                            <div class="bg-emerald-50 px-4 py-1.5 rounded-xl flex items-center gap-2 text-emerald-700 text-xs font-bold border border-emerald-100">
                                                <i data-lucide="check" class="w-4 h-4"></i> <span x-text="f"></span>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Contoh Menu Dinamis dari CRUD --}}
                                    <div class="bg-[#F0FDF4] p-6 rounded-3xl border border-[#DCFCE7] space-y-3">
                                        <p class="text-[#166534] text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                                            <i data-lucide="utensils-2" class="w-3 h-3"></i> Contoh Menu:
                                        </p>
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-1">
                                            <template x-for="menu in (item.extra_info || ['Menu harian bervariasi'])">
                                                <li class="text-[#15803D] text-xs font-bold flex items-start gap-2">
                                                    <span>•</span> <span x-text="menu"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- STEP 5: REVIEW --}}
                <div x-show="step === 5" x-transition class="space-y-10">
                    <h2 class="text-4xl font-black text-center italic text-slate-800">Konfirmasi Pesanan 📋</h2>
                    <div class="bg-white p-10 rounded-[48px] border border-slate-100 shadow-sm space-y-6">
                        <template x-if="kos">
                            <div class="flex items-center justify-between p-6 bg-slate-50/50 rounded-3xl border border-slate-100">
                                <div class="flex items-center gap-6"><img :src="getImageUrl(kos.image)" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-sm"><p class="font-black text-slate-800 text-lg italic" x-text="kos.name"></p></div>
                                <p class="text-[#0095FF] font-black text-xl" x-text="'Rp ' + parseInt(kos.price).toLocaleString()"></p>
                            </div>
                        </template>
                        <template x-if="catering">
                            <div class="flex items-center justify-between p-6 bg-slate-50/50 rounded-3xl border border-slate-100">
                                <div class="flex items-center gap-6"><img :src="getImageUrl(catering.image)" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-sm"><p class="font-black text-slate-800 text-lg italic" x-text="catering.name"></p></div>
                                <p class="text-[#0095FF] font-black text-xl" x-text="'Rp ' + parseInt(catering.price).toLocaleString()"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="sticky top-32">
                <div class="bg-white p-10 rounded-[50px] border border-slate-100 shadow-2xl shadow-blue-100 italic">
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Ringkasan Paket</h3>
                    <h2 class="text-4xl font-black text-[#0095FF] mb-12 uppercase tracking-tighter">Meal Standard</h2>
                    
                    <div class="space-y-6 mb-12">
                        <div class="flex items-center gap-4 p-5 rounded-[28px]" :class="kos ? 'bg-blue-50 text-[#0095FF]' : 'bg-slate-50 text-slate-300'"><i data-lucide="home"></i><span class="text-xs font-black truncate" x-text="kos ? kos.name : 'Pilih Kos...'"></span></div>
                        <div class="flex items-center gap-4 p-5 rounded-[28px]" :class="catering ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-300'"><i data-lucide="utensils-2"></i><span class="text-xs font-black truncate" x-text="catering ? catering.name : 'Pilih Katering...'"></span></div>
                        <div class="flex items-center gap-4 p-5 rounded-[28px] opacity-10 bg-slate-50 text-slate-300"><i data-lucide="shirt"></i><span class="text-xs font-black">Tanpa Laundry</span></div>
                    </div>

                    <div class="border-t-2 border-dashed border-slate-100 pt-8 space-y-4 mb-10">
                        <div class="flex justify-between text-emerald-500 font-black italic"><span>Bundling Discount (15%)</span><span x-text="'- Rp ' + discount.toLocaleString()"></span></div>
                        <div class="flex justify-between items-end pt-6 border-t border-slate-50">
                            <span class="text-slate-800 font-black text-xl leading-none">Total</span>
                            <span class="text-3xl font-black text-[#0095FF] leading-none" x-text="'Rp ' + total.toLocaleString()"></span>
                        </div>
                    </div>

                    <button @click="handleNext()" :disabled="(step === 2 && !kos) || (step === 3 && !catering) || isSaving" 
                            class="w-full py-6 rounded-[32px] font-black text-xl shadow-xl transition transform hover:scale-105 active:scale-95 text-white bg-[#0095FF] shadow-blue-200 disabled:bg-slate-100">
                        <span x-text="isSaving ? 'Processing...' : (step === 5 ? 'Pesan & Bayar 🚀' : 'Lanjutkan')"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Modal --}}
    <div x-show="showSuccessModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white p-12 rounded-[56px] max-w-lg w-full text-center shadow-2xl">
            <h2 class="text-4xl font-black text-slate-800 mb-4 italic">Berhasil! 🍱</h2>
            <p class="text-slate-500 font-bold mb-10 italic">Pesanan Kos & Katering kamu sudah diproses. Siap-siap makan enak setiap hari!</p>
            <a href="{{ route('dashboard') }}" class="inline-block w-full py-5 bg-[#0095FF] text-white rounded-3xl font-black text-xl shadow-xl shadow-blue-200 italic">Ke Dashboard</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('mealWizard', () => ({
            step: 2,
            showSuccessModal: false,
            searchQuery: '',
            isSaving: false,
            
            kosItems: @json($kosItems),
            cateringItems: @json($cateringItems),

            kos: null,
            catering: null,
            discountRate: 0.15, // Diskon 15% untuk Standard Meal

            getImageUrl(path) {
                if (!path) return 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267';
                if (path.includes('http')) return path;
                if (path.includes('storage/')) return path.startsWith('/') ? path : '/' + path;
                return '/storage/services/' + path;
            },

            get subtotal() { return (this.kos?.price || 0) + (this.catering?.price || 0); },
            get discount() { return Math.round(this.subtotal * this.discountRate); },
            get total() { return this.subtotal - this.discount; },

            handleNext() {
                if (this.step === 2 && this.kos) this.step = 3;
                else if (this.step === 3 && this.catering) this.step = 5; // SKIP STEP 4
                else if (this.step === 5) this.saveOrder();
                
                setTimeout(() => { lucide.createIcons(); }, 10);
            },

            goBack() {
                if (this.step === 5) this.step = 3;
                else if (this.step === 3) this.step = 2;
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
                            name: 'Paket Standard Meal - ' + this.kos.name,
                            price: this.total,
                            type: 'bundling',
                            kos_id: this.kos.id,
                            catering_id: this.catering.id,
                            kos_price: this.kos.price,
                            catering_price: this.catering.price,
                            laundry_price: 0
                        })
                    });
                    const data = await response.json();
                    if (data.success) this.showSuccessModal = true;
                } catch (e) { alert('Error memproses pesanan.'); } 
                finally { this.isSaving = false; }
            }
        }));
    });
</script>
@endsection