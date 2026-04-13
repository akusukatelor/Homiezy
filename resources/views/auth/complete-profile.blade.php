@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-[#F8FAFC] pt-20">
    <div class="max-w-md w-full bg-white p-10 rounded-[40px] shadow-2xl shadow-blue-100 border border-slate-50 italic">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-800 mb-2">Sedikit Lagi! 🚀</h2>
            <p class="text-slate-400 font-bold text-sm">Lengkapi nomor WhatsApp kamu untuk memudahkan koordinasi layanan.</p>
        </div>

        <form action="{{ url('/lengkapi-profil') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-4">Nomor WhatsApp</label>
                <div class="relative">
                    <i data-lucide="phone" class="absolute left-5 top-1/2 -translate-y-1/2 text-[#0095FF] w-5 h-5"></i>
                    <input type="text" name="whatsapp" required 
                           placeholder="Contoh: 08123456789"
                           class="w-full pl-14 pr-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-[#0095FF] font-semibold text-slate-800">
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-[#0095FF] text-white rounded-2xl font-black text-lg shadow-xl shadow-blue-200 hover:bg-blue-600 transition transform hover:scale-[1.02] active:scale-95">
                Simpan & Lanjut ke Dashboard
            </button>
        </form>
    </div>
</div>
@endsection