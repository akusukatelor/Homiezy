@extends('layouts.admin-super')

@section('page_title', 'Tambah Paket Baru')

@section('admin_content')
<div class="space-y-8" x-data="{ tipe: 'kenyang' }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 italic uppercase tracking-tighter">
                Tambah Paket Baru 📦
            </h1>
            <p class="text-slate-400 font-bold mt-1">
                Buat paket bundling layanan untuk mahasiswa
            </p>
        </div>
        <a href="{{ route('superadmin.layanan', ['type' => 'paket']) }}"
           class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition italic">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('superadmin.paket.store') }}" method="POST"
          enctype="multipart/form-data"
          class="bg-white p-12 rounded-[50px] shadow-sm border border-slate-100 space-y-10">
        @csrf

        {{-- Tipe Paket --}}
        <div class="space-y-4">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                Tipe Paket
            </label>
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    'kenyang' => ['label' => 'Paket Kenyang', 'desc' => 'Kos + Catering', 'icon' => 'utensils', 'color' => 'emerald'],
                    'bersih'  => ['label' => 'Paket Bersih',  'desc' => 'Kos + Laundry',  'icon' => 'shirt',    'color' => 'blue'],
                    'lengkap' => ['label' => 'Paket Lengkap', 'desc' => 'Kos + Catering + Laundry', 'icon' => 'package', 'color' => 'orange'],
                ] as $key => $item)
                <label class="cursor-pointer">
                    <input type="radio" name="tipe_paket" value="{{ $key }}"
                           x-on:change="tipe = '{{ $key }}'"
                           {{ old('tipe_paket', 'kenyang') === $key ? 'checked' : '' }}
                           class="hidden">
                    <div :class="tipe === '{{ $key }}'
                            ? 'border-{{ $item['color'] }}-500 bg-{{ $item['color'] }}-50'
                            : 'border-slate-100 hover:border-slate-300'"
                         class="p-6 rounded-[28px] border-2 transition text-center">
                        <i data-lucide="{{ $item['icon'] }}"
                           class="w-8 h-8 mx-auto mb-3 text-{{ $item['color'] }}-500"></i>
                        <p class="font-black text-slate-800 italic">{{ $item['label'] }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                            {{ $item['desc'] }}
                        </p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Nama & Diskon --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">
                    Nama Paket
                </label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Contoh: Paket Kenyang Spesial"
                       required
                       class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-orange-400 font-bold text-slate-800 italic">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">
                    Diskon (%)
                </label>
                <input type="number" name="diskon"
                       value="{{ old('diskon', 10) }}"
                       min="0" max="100" required
                       class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-orange-400 font-bold text-slate-800 italic text-xl">
            </div>
        </div>

        {{-- Pilih Layanan --}}
        <div class="p-8 bg-slate-50/50 rounded-[40px] space-y-6 border border-slate-100">
            <h3 class="font-black text-slate-800 italic uppercase tracking-tighter">
                Pilih Layanan yang Digabung
            </h3>

            {{-- Kos (selalu ada) --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest ml-2">
                    🏠 Kos (Wajib)
                </label>
                <select name="kos_id" required
                        class="w-full p-5 bg-white rounded-3xl border-none shadow-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Pilih Kos --</option>
                    @foreach($kosList as $kos)
                    <option value="{{ $kos->id }}" {{ old('kos_id') == $kos->id ? 'selected' : '' }}>
                        {{ $kos->name }} — Rp{{ number_format($kos->price) }}/bln
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Catering --}}
            <div class="space-y-2" x-show="tipe === 'kenyang' || tipe === 'lengkap'">
                <label class="text-[10px] font-black text-emerald-400 uppercase tracking-widest ml-2">
                    🍱 Catering
                    <span x-text="tipe === 'kenyang' || tipe === 'lengkap' ? '(Wajib)' : '(Opsional)'"></span>
                </label>
                <select name="catering_id"
                        class="w-full p-5 bg-white rounded-3xl border-none shadow-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-400">
                    <option value="">-- Pilih Catering --</option>
                    @foreach($cateringList as $catering)
                    <option value="{{ $catering->id }}" {{ old('catering_id') == $catering->id ? 'selected' : '' }}>
                        {{ $catering->name }} — Rp{{ number_format($catering->price) }}/bln
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Laundry --}}
            <div class="space-y-2" x-show="tipe === 'bersih' || tipe === 'lengkap'">
                <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest ml-2">
                    👕 Laundry
                    <span x-text="tipe === 'bersih' || tipe === 'lengkap' ? '(Wajib)' : '(Opsional)'"></span>
                </label>
                <select name="laundry_id"
                        class="w-full p-5 bg-white rounded-3xl border-none shadow-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-400">
                    <option value="">-- Pilih Laundry --</option>
                    @foreach($laundryList as $laundry)
                    <option value="{{ $laundry->id }}" {{ old('laundry_id') == $laundry->id ? 'selected' : '' }}>
                        {{ $laundry->name }} — Rp{{ number_format($laundry->price) }}/bln
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- WhatsApp & Foto --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">
                    WhatsApp Bisnis
                </label>
                <input type="text" name="whatsapp"
                       value="{{ old('whatsapp') }}"
                       placeholder="08xxxxxxxxxx" required
                       class="w-full p-5 bg-slate-50 rounded-3xl border-none focus:ring-2 focus:ring-orange-400 font-bold text-slate-800 italic">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2">
                    Foto Paket (Opsional)
                </label>
                <input type="file" name="image"
                       accept="image/*"
                       class="w-full p-4 bg-slate-50 rounded-3xl border-none text-sm
                              file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                              file:bg-orange-500 file:text-white file:text-[10px] file:uppercase file:font-black">
                <p class="text-[9px] text-slate-400 mt-1 font-bold italic uppercase tracking-wider ml-2">
                    *Kosongkan untuk pakai foto default
                </p>
            </div>
        </div>

        {{-- Error --}}
        @if($errors->any())
        <div class="p-6 bg-red-50 rounded-[20px] border border-red-100">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                <li class="text-red-500 font-bold text-sm italic flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    {{ $error }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Submit --}}
        <button type="submit"
                class="w-full py-6 bg-[#0F172A] text-white rounded-[32px] font-black text-xl
                       shadow-2xl hover:bg-slate-800 transition transform active:scale-95
                       italic uppercase tracking-tighter">
            Buat Paket Sekarang 📦
        </button>
    </form>
</div>
@endsection
