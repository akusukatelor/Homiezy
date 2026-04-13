@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] flex items-center justify-center pt-32 pb-24 px-6 font-sans">
    <div class="max-w-2xl w-full bg-white rounded-[50px] shadow-2xl shadow-blue-100 p-10 md:p-16 relative z-10 border border-slate-100">
        
        <div class="text-center mb-12">
            <h2 class="text-4xl font-black text-slate-800 tracking-tight leading-none">Ayo Bergabung</h2>
            <p class="text-slate-400 font-medium mt-4">Satu akun untuk semua kebutuhan mahasiswa.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div class="md:col-span-2 space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Full Name</label>
                <input type="text" name="name" required placeholder="Alfin Ilham..." class="w-full px-8 py-4 bg-slate-50 border-2 border-transparent focus:border-[#0095FF] focus:bg-white rounded-[24px] outline-none transition font-semibold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">WhatsApp</label>
                <input type="text" name="whatsapp" required placeholder="0812..." class="w-full px-8 py-4 bg-slate-50 border-2 border-transparent focus:border-[#0095FF] focus:bg-white rounded-[24px] outline-none transition font-semibold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Email</label>
                <input type="email" name="email" required placeholder="email@campus.id" class="w-full px-8 py-4 bg-slate-50 border-2 border-transparent focus:border-[#0095FF] focus:bg-white rounded-[24px] outline-none transition font-semibold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Password</label>
                <input type="password" name="password" required class="w-full px-8 py-4 bg-slate-50 border-2 border-transparent focus:border-[#0095FF] focus:bg-white rounded-[24px] outline-none transition font-semibold">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Confirm</label>
                <input type="password" name="password_confirmation" required class="w-full px-8 py-4 bg-slate-50 border-2 border-transparent focus:border-[#0095FF] focus:bg-white rounded-[24px] outline-none transition font-semibold">
            </div>

            <div class="md:col-span-2 pt-6">
                <button type="submit" class="w-full py-5 bg-[#0095FF] text-white rounded-[24px] font-black text-xl shadow-xl shadow-blue-200 hover:bg-blue-600 transition transform hover:scale-[1.01] active:scale-95">Daftar Sekarang</button>
            </div>
        </form>

        <p class="text-center mt-12 text-slate-400 text-sm font-bold">Sudah terdaftar? <a href="{{ route('login') }}" class="text-[#0095FF] hover:underline">Masuk ke Homiezy</a></p>
    </div>
</div>
@endsection