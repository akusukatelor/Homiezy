<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans italic" 
     x-data="{
        kosItems: @json($kosItems),
        cateringItems: @json($cateringItems),
        laundryItems: @json($laundryItems), 
        currentPackage: '{{ $type }}', // 'basic', 'standard-clean', dst
        step: 2, 
        kos: null, catering: null, laundry: null,

        // Logika Alur Paket
        nextStep() {
            if (this.step === 2) {
                // Jika Basic atau Standard Clean, lewati katering
                if (this.currentPackage === 'basic' || this.currentPackage === 'standard-clean') {
                    this.step = (this.currentPackage === 'basic') ? 5 : 4;
                } else { this.step = 3; }
            } 
            else if (this.step === 3) {
                // Jika Standard Meal, lewati laundry langsung ke review
                this.step = (this.currentPackage === 'standard-meal') ? 5 : 4;
            } 
            else if (this.step === 4) { this.step = 5; }
        }
     }">

    <div class="flex items-center justify-between relative max-w-4xl mx-auto mb-20">
        @foreach(['Pilih Paket', 'Pilih Kos', 'Pilih Katering', 'Pilih Laundry', 'Review'] as $i => $label)
            @php 
                $stepNum = $i + 1;
                // Logika sembunyikan label jika paket tidak mendukung layanan tersebut
                $isHidden = ($type === 'basic' && ($stepNum == 3 || $stepNum == 4)) || 
                            ($type === 'standard-clean' && $stepNum == 3) ||
                            ($type === 'standard-meal' && $stepNum == 4);
            @endphp
            
            <div class="relative z-10 flex flex-col items-center gap-3 {{ $isHidden ? 'opacity-20' : '' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center font-black transition"
                     :class="step >= {{ $stepNum }} ? 'bg-[#0095FF] text-white shadow-lg' : 'bg-white text-slate-400 border-2 border-slate-200'">
                    {{ $stepNum }}
                </div>
                <span class="text-[10px] uppercase font-black tracking-widest">{{ $label }}</span>
            </div>
        @endforeach
    </div>

    <div class="bg-white p-10 rounded-[48px] shadow-2xl shadow-blue-50 italic">
        <h3 class="text-3xl font-black text-[#0095FF] mb-10">{{ $packageName }}</h3>
        
        <div class="space-y-4 mb-10">
            <div class="p-4 rounded-3xl flex items-center gap-4 bg-blue-50 text-[#0095FF]">
                <i data-lucide="home"></i> <span class="text-xs font-bold" x-text="kos ? kos.name : 'Pilih Kos...'"></span>
            </div>

            @if($type !== 'basic' && $type !== 'standard-clean')
            <div class="p-4 rounded-3xl flex items-center gap-4" :class="catering ? 'bg-blue-50 text-[#0095FF]' : 'bg-slate-50 text-slate-300'">
                <i data-lucide="utensils"></i> <span class="text-xs font-bold" x-text="catering ? catering.name : 'Pilih Katering...'"></span>
            </div>
            @endif

            @if($type !== 'basic' && $type !== 'standard-meal')
            <div class="p-4 rounded-3xl flex items-center gap-4" :class="laundry ? 'bg-blue-50 text-[#0095FF]' : 'bg-slate-50 text-slate-300'">
                <i data-lucide="shirt"></i> <span class="text-xs font-bold" x-text="laundry ? laundry.name : 'Pilih Laundry...'"></span>
            </div>
            @endif
        </div>

        <button @click="nextStep()" class="w-full py-5 bg-[#0095FF] text-white rounded-3xl font-black text-xl shadow-xl">
            <span x-text="step === 5 ? 'Konfirmasi & Bayar' : 'Lanjutkan'"></span>
        </button>
    </div>
</div>