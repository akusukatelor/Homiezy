@extends('layouts.admin-super')

@section('page_title', 'Dashboard 👑')
@section('admin_content')
<div class="space-y-10">

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-black text-slate-800 italic mb-2 uppercase tracking-tighter">Super Admin Panel 👑</h1>
        <p class="text-slate-400 font-bold">Kelola seluruh ekosistem Homiezy dari sini.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50 md:col-span-1">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total User</p>
            <h3 class="text-3xl font-black text-slate-800 italic">{{ $stats['total_user'] }}</h3>
            <p class="text-[10px] font-bold text-blue-400 uppercase mt-1 italic">Customer</p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Mitra</p>
            <h3 class="text-3xl font-black text-[#0095FF] italic">{{ $stats['total_mitra'] }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 italic">Terdaftar</p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Kos</p>
            <h3 class="text-3xl font-black text-slate-800 italic">{{ $stats['total_kos'] }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 italic">Listing</p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Catering</p>
            <h3 class="text-3xl font-black text-emerald-500 italic">{{ $stats['total_catering'] }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 italic">Listing</p>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Laundry</p>
            <h3 class="text-3xl font-black text-indigo-500 italic">{{ $stats['total_laundry'] }}</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 italic">Listing</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div>
        <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('superadmin.layanan', ['type' => 'kos']) }}"
               class="flex items-center gap-4 bg-white p-6 rounded-[28px] border border-slate-100 hover:border-[#0095FF] hover:shadow-md transition group">
                <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-100 transition">
                    <i data-lucide="home" class="w-5 h-5 text-[#0095FF]"></i>
                </div>
                <div>
                    <p class="font-black text-slate-800 italic">Kelola Kos</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lihat & hapus listing</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 ml-auto group-hover:text-[#0095FF] transition"></i>
            </a>

            <a href="{{ route('superadmin.layanan', ['type' => 'katering']) }}"
               class="flex items-center gap-4 bg-white p-6 rounded-[28px] border border-slate-100 hover:border-emerald-400 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center group-hover:bg-emerald-100 transition">
                    <i data-lucide="utensils" class="w-5 h-5 text-emerald-500"></i>
                </div>
                <div>
                    <p class="font-black text-slate-800 italic">Kelola Catering</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lihat & hapus listing</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 ml-auto group-hover:text-emerald-400 transition"></i>
            </a>

            <a href="{{ route('superadmin.layanan', ['type' => 'laundry']) }}"
               class="flex items-center gap-4 bg-white p-6 rounded-[28px] border border-slate-100 hover:border-indigo-400 hover:shadow-md transition group">
                <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center group-hover:bg-indigo-100 transition">
                    <i data-lucide="shirt" class="w-5 h-5 text-indigo-500"></i>
                </div>
                <div>
                    <p class="font-black text-slate-800 italic">Kelola Laundry</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lihat & hapus listing</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 ml-auto group-hover:text-indigo-400 transition"></i>
            </a>

            <a href="{{ route('superadmin.users') }}"
               class="flex items-center gap-4 bg-white p-6 rounded-[28px] border border-slate-100 hover:border-orange-400 hover:shadow-md transition group md:col-span-3">
                <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center group-hover:bg-orange-100 transition">
                    <i data-lucide="users" class="w-5 h-5 text-orange-500"></i>
                </div>
                <div>
                    <p class="font-black text-slate-800 italic">Kelola User & Mitra</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lihat & hapus akun</p>
                </div>
                <i data-lucide="arrow-right" class="w-4 h-4 text-slate-300 ml-auto group-hover:text-orange-400 transition"></i>
            </a>
        </div>
    </div>

</div>
@endsection
