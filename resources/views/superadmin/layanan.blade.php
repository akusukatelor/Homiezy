@extends('layouts.admin-super')

@section('page_title', 'Kelola User')
@section('admin_content')
<div class="space-y-8" x-data="{ confirmDelete: false, deleteUrl: '' }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 italic uppercase tracking-tighter">
                Kelola
                @if($type === 'kos') 🏠 Kos
                @elseif($type === 'katering') 🍱 Catering
                @else 👕 Laundry
                @endif
            </h1>
            <p class="text-slate-400 font-bold mt-1">{{ $layanan->total() }} listing terdaftar</p>
        </div>
        <a href="{{ route('superadmin.dashboard') }}"
           class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition italic">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-[20px] font-bold text-sm italic flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filter Tabs --}}
    <div class="flex gap-3">
        @foreach(['kos' => '🏠 Kos', 'katering' => '🍱 Catering', 'laundry' => '👕 Laundry'] as $key => $label)
        <a href="{{ route('superadmin.layanan', ['type' => $key, 'search' => $search]) }}"
           class="px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest transition
           {{ $type === $key
               ? 'bg-[#0F172A] text-white shadow-lg'
               : 'bg-white text-slate-400 border border-slate-100 hover:border-slate-300' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('superadmin.layanan') }}" class="flex gap-3">
        <input type="hidden" name="type" value="{{ $type }}">
        <div class="relative flex-1">
            <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 w-4 h-4"></i>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama layanan..."
                   class="w-full pl-12 pr-6 py-4 bg-white rounded-[20px] border border-slate-100 font-bold text-slate-700 italic focus:ring-2 focus:ring-[#0095FF] focus:border-transparent outline-none">
        </div>
        <button type="submit"
                class="px-6 py-4 bg-[#0095FF] text-white rounded-[20px] font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition italic">
            Cari
        </button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                    <th class="px-10 py-6">Layanan</th>
                    <th>Pemilik (Mitra)</th>
                    <th>Harga</th>
                    <th>Lokasi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($layanan as $item)
                <tr class="hover:bg-slate-50/50 transition">
                    {{-- Layanan --}}
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-lucide="image" class="w-5 h-5 text-slate-300"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-black text-slate-800 italic">{{ $item->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                                    {{ strtoupper($item->type) }}
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Mitra --}}
                    <td>
                        <p class="font-bold text-slate-700 italic text-sm">{{ $item->user->name ?? '-' }}</p>
                        <p class="text-[10px] font-bold text-slate-400">{{ $item->user->email ?? '' }}</p>
                    </td>

                    {{-- Harga --}}
                    <td>
                        <p class="font-black text-slate-800 italic">
                            Rp{{ number_format($item->price, 0, ',', '.') }}
                        </p>
                        <p class="text-[10px] font-bold text-slate-400 italic">/bulan</p>
                    </td>

                    {{-- Lokasi --}}
                    <td>
                        <p class="font-bold text-slate-600 italic text-sm max-w-[180px] truncate">
                            {{ $item->location ?? '-' }}
                        </p>
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center px-6">
                        <button
                            @click="confirmDelete = true; deleteUrl = '{{ route('superadmin.layanan.destroy', $item->id) }}'"
                            class="bg-red-50 text-red-500 px-5 py-2 rounded-xl text-[10px] font-black hover:bg-red-100 hover:scale-105 transition italic uppercase tracking-widest border border-red-100">
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-20 text-center font-bold text-slate-300 italic uppercase tracking-widest">
                        Tidak ada listing {{ $type }} ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($layanan->hasPages())
    <div class="flex justify-center">
        {{ $layanan->appends(['type' => $type, 'search' => $search])->links() }}
    </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    <div x-show="confirmDelete" x-cloak
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-[40px] p-10 max-w-sm w-full shadow-2xl"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="w-16 h-16 bg-red-50 rounded-[20px] flex items-center justify-center mx-auto mb-6">
                <i data-lucide="trash-2" class="w-7 h-7 text-red-500"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 italic text-center uppercase tracking-tighter mb-2">
                Hapus Layanan?
            </h3>
            <p class="text-slate-400 font-bold text-sm text-center mb-8">
                Tindakan ini tidak bisa dibatalkan. Listing akan dihapus permanen.
            </p>
            <div class="flex gap-3">
                <button @click="confirmDelete = false"
                        class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-[20px] font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition italic">
                    Batal
                </button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full py-4 bg-red-500 text-white rounded-[20px] font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition italic">
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
