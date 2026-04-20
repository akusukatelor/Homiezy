@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans">
    <div class="container mx-auto px-6 max-w-6xl">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12" data-aos="fade-down">
            <div>
                <h1 class="text-4xl font-black text-slate-800 tracking-tight">Dashboard Mitra</h1>
                <p class="text-slate-400 font-bold mt-1">Pantau perkembangan bisnismu di Homiezy secara real-time.</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest">Sistem Online</span>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16" data-aos="fade-up" data-aos-delay="100">
            {{-- Card 1: Total Revenue --}}
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-100 group hover:border-[#0095FF] transition-all duration-500">
                <div class="w-14 h-14 bg-blue-50 text-[#0095FF] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="wallet" class="w-7 h-7"></i>
                </div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Total Pendapatan</p>
                <h3 class="text-3xl font-black text-slate-800">Rp {{ number_format($totalEarnings ?? 0, 0, ',', '.') }}</h3>
            </div>

            {{-- Card 2: Pending Orders --}}
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-100 group hover:border-yellow-400 transition-all duration-500">
                <div class="w-14 h-14 bg-yellow-50 text-yellow-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="clock" class="w-7 h-7"></i>
                </div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Pesanan Tertunda</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $incomingOrders->where('status', 'Pending')->count() }} <span class="text-sm text-slate-300">Order</span></h3>
            </div>

            {{-- Card 3: Completed Orders --}}
            <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-100 group hover:border-emerald-500 transition-all duration-500">
                <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="check-circle" class="w-7 h-7"></i>
                </div>
                <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Berhasil Lunas</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $incomingOrders->where('status', 'Success')->count() }} <span class="text-sm text-slate-300">Order</span></h3>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white rounded-[50px] shadow-2xl shadow-blue-100/50 border border-slate-50 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
            <div class="p-10 border-b border-slate-50 flex justify-between items-center bg-white">
                <h3 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                    <i data-lucide="list-ordered" class="text-[#0095FF]"></i> Riwayat Pesanan
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-[0.2em]">
                            <th class="p-8">No. Pesanan / Customer</th>
                            <th>Layanan & Produk</th>
                            <th>Status Pembayaran</th>
                            <th class="text-center">Aksi Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($incomingOrders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="p-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 font-black text-xs">
                                        #{{ substr($order->order_number, -3) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-slate-800">#{{ $order->order_number }}</p>
                                        <p class="text-[11px] font-bold text-[#0095FF] uppercase">{{ $order->user->name }}</p>
                                        @php
                                            $waCustomer = $order->user->whatsapp;
                                            if (str_starts_with($waCustomer, '0')) $waCustomer = '62' . substr($waCustomer, 1);
                                        @endphp
                                        <a href="https://wa.me/{{ $waCustomer }}" target="_blank" 
                                        class="flex items-center gap-1 bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-md hover:bg-emerald-100 transition border border-emerald-100">
                                            <i data-lucide="phone" class="w-3 h-3"></i>
                                            <span class="text-[9px] font-black">{{ $order->user->whatsapp }}</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="font-black text-slate-700">{{ $order->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Harga: Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                            </td>
                            <td>
                                @if($order->status == 'Pending')
                                    <span class="bg-orange-50 text-orange-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-orange-100">
                                        Menunggu
                                    </span>
                                @else
                                    <span class="bg-emerald-50 text-emerald-500 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                        Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="text-center p-8">
                                @if($order->status == 'Pending')
                                    <form action="{{ route('order.confirm', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-[#0095FF] text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-blue-200 hover:bg-blue-600 transition transform hover:scale-105 active:scale-95">
                                            Konfirmasi Lunas
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center justify-center gap-2 text-slate-300 font-black text-[10px] uppercase">
                                        <i data-lucide="check-check" class="w-4 h-4"></i> Selesai
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-24 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="w-20 h-20 bg-slate-50 rounded-[32px] flex items-center justify-center text-slate-200">
                                        <i data-lucide="inbox" class="w-10 h-10"></i>
                                    </div>
                                    <p class="text-slate-400 font-black">Belum ada pesanan masuk untuk saat ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection