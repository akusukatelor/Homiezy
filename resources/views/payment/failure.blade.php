@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#F8FAFC]">
    <div class="bg-white rounded-[40px] p-12 max-w-md w-full text-center shadow-sm border border-slate-100">
        <div class="w-20 h-20 bg-red-50 rounded-[24px] flex items-center justify-center mx-auto mb-6">
            <i data-lucide="x-circle" class="w-10 h-10 text-red-500"></i>
        </div>
        <h1 class="text-2xl font-black text-slate-800 italic uppercase tracking-tighter mb-2">
            Pembayaran Gagal 😔
        </h1>
        <p class="text-slate-400 font-bold mb-8">
            Pembayaran tidak berhasil diproses. Silakan coba lagi atau hubungi kami.
        </p>
        <div class="flex gap-3 justify-center">
            <a href="{{ route('dashboard') }}"
               class="px-6 py-4 bg-slate-100 text-slate-600 rounded-[20px] font-black text-sm uppercase tracking-widest hover:bg-slate-200 transition italic">
                Kembali
            </a>
            <a href="{{ route('home') }}"
               class="px-6 py-4 bg-[#0095FF] text-white rounded-[20px] font-black text-sm uppercase tracking-widest hover:bg-blue-600 transition italic">
                Coba Lagi
            </a>
        </div>
    </div>
</div>
@endsection
