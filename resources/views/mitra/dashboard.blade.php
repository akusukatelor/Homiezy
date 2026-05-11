@extends('layouts.admin-mitra')

@section('admin_content')

<div class="space-y-10">

    
    <div x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
        <h1 class="text-3xl font-black text-slate-800 italic mb-8 uppercase tracking-tighter">Ringkasan Bisnis 📈</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Pendapatan</p>
                <h3 class="text-3xl font-black text-slate-800 italic">Rp{{ number_format($stats['earnings']) }}</h3>
            </div>
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Perlu Konfirmasi</p>
                <h3 class="text-3xl font-black text-orange-500 italic">{{ $stats['pending'] }} Pesanan</h3>
            </div>
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Transaksi</p>
                <h3 class="text-3xl font-black text-slate-800 italic">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>

   
    <div x-show="tab === 'orders'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
        <h1 class="text-3xl font-black text-slate-800 italic mb-8 uppercase tracking-tighter">Pesanan Masuk 🛒</h1>
        
        <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="px-10 py-6">Mahasiswa</th>
                        <th>Layanan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($incomingOrders as $order)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-10 py-8 font-black text-slate-800">
                            {{ $order->user->name }}
                            <p class="text-[10px] font-bold text-emerald-500 italic uppercase tracking-wider">{{ $order->user->whatsapp }}</p>
                        </td>
                        <td class="font-bold text-slate-600 text-sm italic">{{ $order->name }}</td>
                        <td>
                            <span class="px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border 
    {{ $order->status == 'Pending' ? 'bg-orange-50 text-orange-600 border-orange-100' : 
       ($order->status == 'Success' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 
       'bg-red-50 text-red-500 border-red-100') }}">
    {{ $order->status }}
</span>
                        </td>
                        <td class="text-center">
                            @if($order->status === 'Pending')
                                <form action="{{ route('order.confirm', $order->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-[#0095FF] text-white px-5 py-2 rounded-xl text-[10px] font-black hover:scale-105 transition italic shadow-lg shadow-blue-100">LUNASKAN</button>
                                </form>
                            @else
                                <i data-lucide="check-check" class="w-5 h-5 text-emerald-500 mx-auto"></i>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="p-20 text-center font-bold text-slate-300 italic uppercase">Belum ada pesanan mahasiswa</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<div x-show="tab === 'manage'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" 
     x-data="{ 
        type: '{{ $service->type }}', 
        {{-- Mengambil data menu katering jika ada, jika tidak default satu baris kosong --}}
        menus: {{ json_encode($service->extra_info ?? ['']) }} 
     }">
    
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 italic uppercase tracking-tighter">Pengaturan Layanan ⚙️</h1>
            <p class="text-slate-400 font-bold mt-1">Sesuaikan informasi layanan Anda agar mahasiswa mendapatkan data terbaru.</p>
        </div>
    </div>

    <form action="{{ route('mitra.update', $service->id) }}" method="POST" enctype="multipart/form-data" 
          class="bg-white p-12 rounded-[50px] shadow-sm border border-slate-100 space-y-10">
        @csrf
        @method('PUT')

        {{-- INFO DASAR --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Tipe Bisnis (Tetap)</label>
                <input type="text" value="{{ strtoupper($service->type) }}" disabled class="w-full p-5 bg-slate-100 rounded-3xl border-none font-black text-slate-400 italic">
                <input type="hidden" name="type" value="{{ $service->type }}">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Nama Bisnis</label>
                <input type="text" name="name" value="{{ $service->name }}" required class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF] font-bold text-slate-800 italic">
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Lokasi Workshop / Alamat</label>
                <input type="text" name="location" value="{{ $service->location }}" required class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF] font-bold text-slate-800 italic">
            </div>

            <div class="space-y-2 md:col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Patokan & Info Jarak</label>
                <div class="relative">
                    <i data-lucide="map-pin" class="absolute left-5 top-1/2 -translate-y-1/2 text-[#0095FF] w-5 h-5"></i>
                    <input type="text" name="distance" value="{{ $service->distance_info }}" required class="w-full pl-14 pr-6 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-[#0095FF] font-bold text-slate-800 italic">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Harga Utama (Rp)</label>
                <input type="number" name="price" value="{{ $service->price }}" required class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF] font-bold text-slate-800 italic text-xl">
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">WhatsApp Bisnis</label>
                <input type="text" name="whatsapp" value="{{ $service->whatsapp }}" required class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-[#0095FF] font-bold text-slate-800 italic">
            </div>
        </div>

        {{-- UPLOAD FOTO --}}
        <div class="space-y-3">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">Foto Layanan</label>
            <div class="flex items-center gap-8 p-6 bg-slate-50 rounded-[32px] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 rounded-2xl overflow-hidden shadow-md border-4 border-white">
                    <img src="{{ asset('storage/' . $service->image) }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <input type="file" name="image" class="text-xs font-black text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-[#0095FF] file:text-white file:text-[10px] file:uppercase">
                    <p class="text-[9px] text-slate-400 mt-2 font-bold italic uppercase tracking-wider">*Kosongkan jika tidak ingin mengganti gambar</p>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        {{-- DATA SPESIFIK KOS --}}
        <div x-show="type === 'kos'" x-transition x-cloak class="p-8 bg-blue-50/50 rounded-[40px] space-y-8 border border-blue-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-blue-400 tracking-widest ml-2">Kategori Penghuni</label>
                    <select name="gender" class="w-full p-4 rounded-2xl border-none shadow-sm text-slate-600 font-black italic uppercase text-xs">
                        <option value="Putra" {{ $service->gender == 'Putra' ? 'selected' : '' }}>Khusus Putra</option>
                        <option value="Putri" {{ $service->gender == 'Putri' ? 'selected' : '' }}>Khusus Putri</option>
                        <option value="Campur" {{ $service->gender == 'Campur' ? 'selected' : '' }}>Campur</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-blue-400 tracking-widest ml-2">Ukuran Kamar (Meter)</label>
                    <input type="text" name="room_size" value="{{ $service->room_size }}" placeholder="Contoh: 3x3" class="w-full p-4 rounded-2xl border-none shadow-sm text-sm font-bold italic">
                </div>
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-blue-400 tracking-widest ml-2">Status Listrik</label>
                    <select name="electricity" class="w-full p-4 rounded-2xl border-none shadow-sm text-slate-600 font-black italic uppercase text-xs">
                        <option value="Include" {{ $service->electricity == 'Include' ? 'selected' : '' }}>Include Listrik</option>
                        <option value="Exclude" {{ $service->electricity == 'Exclude' ? 'selected' : '' }}>Token/Exclude</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-blue-400 tracking-widest ml-2">Status Air</label>
                    <select name="water" class="w-full p-4 rounded-2xl border-none shadow-sm text-slate-600 font-black italic uppercase text-xs">
                        <option value="Include" {{ $service->water == 'Include' ? 'selected' : '' }}>Include Air</option>
                        <option value="Exclude" {{ $service->water == 'Exclude' ? 'selected' : '' }}>Bayar Iuran</option>
                    </select>
                </div>
            </div>
            
            <div class="space-y-4">
                <p class="font-black text-slate-700 text-xs uppercase tracking-widest ml-2">Fasilitas Kos:</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php $currentFeatures = $service->features ?? []; @endphp
                    @foreach(['WiFi', 'AC', 'KM Dalam', 'Parkir', 'Dapur', 'CCTV', 'Lemari', 'Kasur'] as $f)
                    <label class="flex items-center gap-2 font-bold text-slate-600 text-xs cursor-pointer group">
                        <input type="checkbox" name="features[]" value="{{ $f }}" {{ in_array($f, $currentFeatures) ? 'checked' : '' }} class="rounded text-[#0095FF] focus:ring-[#0095FF]"> 
                        <span class="group-hover:text-[#0095FF] transition italic">{{ $f }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- DATA SPESIFIK KATERING --}}
        <div x-show="type === 'katering'" x-transition x-cloak class="space-y-8">
            <div class="p-8 bg-emerald-50/50 rounded-[40px] border border-emerald-100 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-black text-[10px] uppercase text-emerald-400 tracking-widest ml-2">Subtitle</label>
                        <input type="text" name="subtitle" value="{{ $service->subtitle }}" class="w-full p-4 rounded-2xl border-none shadow-sm font-bold italic">
                    </div>
                    <div class="space-y-2">
                        <label class="font-black text-[10px] uppercase text-emerald-400 tracking-widest ml-2">Jadwal Kirim</label>
                        <input type="text" name="schedule" value="{{ $service->schedule }}" class="w-full p-4 rounded-2xl border-none shadow-sm font-bold italic">
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="font-black text-slate-700 text-[10px] uppercase tracking-widest ml-2">Keunggulan:</p>
                    <div class="grid grid-cols-2 gap-4">
                        @php $currentCateringFeat = $service->features ?? []; @endphp
                        @foreach(['Gratis Ongkir', 'Halal', 'Tanpa MSG', 'Menu Berubah Tiap Hari'] as $ex)
                        <label class="flex items-center gap-2 font-bold text-slate-600 text-xs cursor-pointer group">
                            <input type="checkbox" name="features[]" value="{{ $ex }}" {{ in_array($ex, $currentCateringFeat) ? 'checked' : '' }} class="rounded text-emerald-500"> 
                            <span class="group-hover:text-emerald-600 transition italic">{{ $ex }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Dinamis Menu List --}}
            <div class="p-8 bg-white rounded-[40px] border-2 border-slate-50 space-y-4 shadow-sm">
                <label class="font-black text-slate-700 text-xs uppercase tracking-widest ml-2 flex items-center gap-2">
                    <i data-lucide="utensils" class="w-4 h-4 text-emerald-500"></i> Edit Daftar Menu:
                </label>
                <div class="space-y-3">
                    <template x-for="(menu, index) in menus" :key="index">
                        <div class="flex gap-2">
                            <input type="text" name="extra_info[]" x-model="menus[index]" class="flex-1 p-4 rounded-2xl bg-slate-50 border-none text-sm font-bold italic">
                            <button type="button" @click="menus.splice(index, 1)" x-show="menus.length > 1" class="p-4 bg-red-50 text-red-500 rounded-2xl hover:bg-red-100 transition">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </template>
                </div>
                <button type="button" @click="menus.push('')" class="flex items-center gap-2 text-[#0095FF] font-black text-[10px] uppercase mt-2 hover:opacity-70 transition ml-2 italic">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Baris Menu
                </button>
            </div>
        </div>

        {{-- DATA SPESIFIK LAUNDRY --}}
        <div x-show="type === 'laundry'" x-transition x-cloak class="p-8 bg-indigo-50/50 rounded-[40px] space-y-8 border border-indigo-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Subtitle Layanan</label>
                    <input type="text" name="subtitle" value="{{ $service->subtitle }}" class="w-full p-4 rounded-2xl border-none shadow-sm font-bold italic">
                </div>
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Jadwal Operasional</label>
                    <input type="text" name="schedule" value="{{ $service->schedule }}" class="w-full p-4 rounded-2xl border-none shadow-sm font-bold italic">
                </div>
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Kapasitas Maks</label>
                    <input type="text" name="capacity" value="{{ $service->capacity ?? '' }}" class="w-full p-4 rounded-2xl border-none shadow-sm font-bold italic">
                </div>
                <div class="space-y-2">
                    <label class="font-black text-[10px] uppercase text-indigo-400 tracking-widest ml-2">Jadwal Jemput</label>
                    <input type="text" name="frequency" value="{{ $service->frequency }}" class="w-full p-4 rounded-2xl border-none shadow-sm font-bold italic">
                </div>
            </div>

            <div class="space-y-4">
                <p class="font-black text-slate-700 text-[10px] uppercase tracking-widest ml-2">Fitur Layanan:</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @php $currentLaundryFeat = $service->features ?? []; @endphp
                    @foreach(['Cuci Setrika', 'Cuci Kering', 'Express 4 Jam', 'Cuci Sepatu', 'Bedcover', 'Parfum Premium'] as $l)
                    <label class="flex items-center gap-2 font-bold text-slate-600 text-xs cursor-pointer group">
                        <input type="checkbox" name="features[]" value="{{ $l }}" {{ in_array($l, $currentLaundryFeat) ? 'checked' : '' }} class="rounded text-indigo-500"> 
                        <span class="group-hover:text-indigo-600 transition italic">{{ $l }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <button type="submit" class="w-full py-6 bg-[#0F172A] text-white rounded-[32px] font-black text-xl shadow-2xl hover:bg-slate-800 transition transform active:scale-95 italic uppercase tracking-tighter">
            Simpan Perubahan Bisnis 💾
        </button>
    </form>
</div>

</div>
@endsection