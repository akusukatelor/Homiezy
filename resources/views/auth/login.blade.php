@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#F8FAFC] flex items-center justify-center pt-32 pb-20 px-6 font-sans ">
    <div class="max-w-md w-full bg-white rounded-[50px] shadow-2xl shadow-blue-100 p-10 md:p-14 relative z-10 border border-slate-50" x-data="{ showPass: false }">
        
        <div class="text-center mb-10">
            <img src="{{ asset('images/logo-homiezy.png') }}" class="h-12 mx-auto mb-6">
            <p class="text-slate-400 font-medium text-sm">Masuk untuk kelola paket katering & laundry kamu.</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Email</label>
                <input type="email" name="email" required class="w-full px-8 py-4 bg-slate-50 border-2 border-transparent focus:border-[#0095FF] focus:bg-white rounded-3xl outline-none transition font-semibold text-slate-700">
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-4">Password</label>
                <div class="relative">
                    <input :type="showPass ? 'text' : 'password'" name="password" required class="w-full px-8 py-4 bg-slate-50 border-2 border-transparent focus:border-[#0095FF] focus:bg-white rounded-3xl outline-none transition font-semibold text-slate-700">
                    <button type="button" @click="showPass = !showPass" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-[#0095FF]"><i data-lucide="eye" class="w-5 h-5"></i></button>
                </div>
            </div>

            <button type="submit" class="w-full py-5 bg-[#0095FF] text-white rounded-[24px] font-black text-xl shadow-xl shadow-blue-200 hover:bg-blue-600 transition transform hover:scale-[1.02] active:scale-95">Masuk Sekarang</button>
        </form>

        <div class="relative my-8 text-center">
            <hr class="border-slate-100">
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Atau</span>
        </div>

        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-4 py-4 border-2 border-slate-100 rounded-[24px] font-bold text-slate-600 hover:bg-slate-50 transition active:scale-95">
            <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5">
            <span>Sign in with Google</span>
        </a>

        <p class="text-center mt-10 text-slate-400 text-sm font-bold ">Belum bergabung? <a href="{{ route('register') }}" class="text-[#0095FF] hover:underline">Buat Akun Homiezy</a></p>
    </div>
</div>
@endsection