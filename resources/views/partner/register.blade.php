@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans italic" x-data="{ type: 'kos', menus: [''] }">
    <div class="container mx-auto px-6 max-w-4xl">
        <form action="{{ route('partner.store') }}" method="POST" enctype="multipart/form-data" 
              class="bg-white p-12 rounded-[50px] shadow-sm border border-slate-100">
            @csrf
            
            <h1 class="text-4xl font-black mb-2 text-slate-800">Daftar Mitra Homiezy</h1>
            <p class="text-slate-400 mb-12 font-bold italic">Tingkatkan omset bisnis kos, katering, atau laundry Anda.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-2">
                    <label class="font-black text-xs uppercase text-slate-400 tracking-widest">Tipe Bisnis</label>
                    <select name="type" x-model="type" class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF] font-bold">
                        <option value="kos">🏠 Rumah Kos</option>
                        <option value="katering">🍱 Katering Makanan</option>
                        <option value="laundry">👕 Layanan Laundry</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="font-black text-xs uppercase text-slate-400 tracking-widest">Nama Bisnis</label>
                    <input type="text" name="name" required placeholder="Contoh: Kos Mawar Indah" class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF]">
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="font-black text-xs uppercase text-slate-400 tracking-widest">Lokasi Lengkap / Workshop</label>
                    <input type="text" name="location" required placeholder="Contoh: Grendeng, Purwokerto (Dekat Kampus Unsoed)" class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF]">
                </div>

                <div class="space-y-2">
                    <label class="font-black text-xs uppercase text-slate-400 tracking-widest">Harga Mulai Dari (Rp)</label>
                    <input type="number" name="price" required placeholder="Contoh: 500000" class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF]">
                </div>
                
                <div class="space-y-2">
                    <label class="font-black text-xs uppercase text-slate-400 tracking-widest">WhatsApp Bisnis</label>
                    <input type="text" name="whatsapp" value="{{ auth()->user()->whatsapp ?? '' }}" required class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF]">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="font-black text-xs uppercase text-slate-400 tracking-widest">Upload Foto Utama</label>
                    <input type="file" name="image" required class="w-full p-4 bg-slate-50 rounded-3xl border-none text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#0095FF] file:text-white file:font-black">
                </div>
            </div>

            <hr class="mb-10 border-slate-100">
            <div x-show="type === 'kos'" x-transition x-cloak class="p-8 bg-blue-50/50 rounded-[40px] space-y-6 mb-10 border border-blue-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-black text-[10px] uppercase text-blue-400 tracking-widest ml-2">Kategori Penghuni</label>
                        <select name="gender" class="w-full p-4 rounded-2xl border-none shadow-sm text-slate-500 font-bold">
                            <option value="Putra">Khusus Putra</option>
                            <option value="Putri">Khusus Putri</option>
                            <option value="Campur">Campur</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="font-black text-slate-700 text-sm italic">Fasilitas Kos:</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach(['WiFi', 'AC', 'KM Dalam', 'Parkir', 'Dapur', 'CCTV'] as $f)
                        <label class="flex items-center gap-2 font-bold text-slate-600 text-xs cursor-pointer group">
                            <input type="checkbox" name="features[]" value="{{ $f }}" class="rounded text-[#0095FF] focus:ring-[#0095FF]"> 
                            <span class="group-hover:text-[#0095FF] transition">{{ $f }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div x-show="type === 'katering'" x-transition x-cloak class="space-y-8 mb-10">
                <div class="p-8 bg-emerald-50/50 rounded-[40px] border border-emerald-100 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="font-black text-[10px] uppercase text-emerald-400 tracking-widest ml-2">Subtitle</label>
                            <input type="text" name="subtitle" placeholder="Contoh: Sehat & Bergizi" class="w-full p-4 rounded-2xl border-none shadow-sm text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="font-black text-[10px] uppercase text-emerald-400 tracking-widest ml-2">Jadwal Kirim</label>
                            <input type="text" name="schedule" placeholder="Contoh: Setiap Hari" class="w-full p-4 rounded-2xl border-none shadow-sm text-sm">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <p class="font-black text-slate-700 text-sm italic">Keunggulan Katering:</p>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach(['Gratis Ongkir', 'Halal', 'Tanpa MSG', 'Menu Berubah Tiap Hari'] as $ex)
                            <label class="flex items-center gap-2 font-bold text-slate-600 text-xs cursor-pointer group">
                                <input type="checkbox" name="features[]" value="{{ $ex }}" class="rounded text-emerald-500"> 
                                <span class="group-hover:text-emerald-600 transition">{{ $ex }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-white rounded-[40px] border-2 border-slate-50 space-y-4 shadow-sm">
                    <label class="font-black text-slate-700 text-sm italic ml-2 flex items-center gap-2">
                        <i data-lucide="utensils" class="w-4 h-4 text-emerald-500"></i> Contoh Menu Mingguan:
                    </label>
                    <div class="space-y-3">
                        <template x-for="(menu, index) in menus" :key="index">
                            <div class="flex gap-2">
                                <input type="text" name="extra_info[]" x-model="menus[index]" placeholder="Ayam Bakar + Nasi + Sayur..." class="flex-1 p-4 rounded-2xl bg-slate-50 border-none text-sm">
                                <button type="button" @click="menus.splice(index, 1)" x-show="menus.length > 1" class="p-4 bg-red-50 text-red-500 rounded-2xl hover:bg-red-100">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="menus.push('')" class="flex items-center gap-2 text-[#0095FF] font-black text-xs uppercase mt-2 hover:opacity-70 transition ml-2">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Menu
                    </button>
                </div>
            </div>
            <div x-show="type === 'laundry'" x-transition x-cloak class="p-8 bg-indigo-50/50 rounded-[40px] space-y-8 mb-10 border border-indigo-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Subtitle Layanan</label>
                        <input type="text" name="subtitle" placeholder="Contoh: Bersih & Wangi" class="w-full p-4 rounded-2xl border-none shadow-sm text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Jadwal Operasional</label>
                        <input type="text" name="schedule" placeholder="Contoh: Senin - Minggu (08.00-20.00)" class="w-full p-4 rounded-2xl border-none shadow-sm text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Kapasitas (Kg/Bulan)</label>
                        <input type="text" name="capacity" placeholder="Contoh: Max 15kg" class="w-full p-4 rounded-2xl border-none shadow-sm text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Jadwal Jemput</label>
                        <input type="text" name="frequency" placeholder="Contoh: 2x seminggu" class="w-full p-4 rounded-2xl border-none shadow-sm text-sm">
                    </div>
                </div>

                <div class="space-y-4">
                    <p class="font-black text-slate-700 text-sm italic">Layanan Laundry:</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach(['Cuci Setrika', 'Cuci Kering', 'Express 4 Jam', 'Cuci Sepatu', 'Bedcover', 'Parfum Premium'] as $l)
                        <label class="flex items-center gap-2 font-bold text-slate-600 text-[11px] cursor-pointer group">
                            <input type="checkbox" name="features[]" value="{{ $l }}" class="rounded text-indigo-500"> 
                            <span class="group-hover:text-indigo-600 transition">{{ $l }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
            <button type="submit" class="w-full py-6 bg-[#0095FF] text-white rounded-[32px] font-black text-2xl shadow-xl shadow-blue-100 hover:scale-[1.02] active:scale-95 transition transform italic">
                Daftarkan Bisnis Sekarang 🚀
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        this.$watch('type', () => {
            setTimeout(() => { lucide.createIcons(); }, 10);
        });
    });
</script>
@endsection