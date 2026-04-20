@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans">
    <div class="container mx-auto px-6">
        
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Dashboard Saya</h1>
            <p class="text-slate-500 font-medium">Kelola pengeluaran dan pantau semua transaksimu di sini</p>
        </div>

        {{-- Statistik Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-[#0095FF] p-8 rounded-[32px] text-white shadow-xl shadow-blue-200">
                <p class="text-sm font-medium opacity-80 mb-6">Total Pengeluaran Bulan Ini</p>
                <h3 class="text-3xl font-bold mb-4">Rp{{ number_format($stats['total'], 0, ',', '.') }}</h3>
                <p class="text-xs font-medium flex items-center gap-2">
                    <i data-lucide="trending-down" class="w-4 h-4"></i> Data real-time database
                </p>
            </div>

            @php
                $categories = [
                    ['label' => 'Kos', 'key' => 'kos', 'icon' => 'home', 'color' => '#0095FF'],
                    ['label' => 'Katering', 'key' => 'katering', 'icon' => 'utensils', 'color' => '#0061AF'],
                    ['label' => 'Laundry', 'key' => 'laundry', 'icon' => 'shirt', 'color' => '#0F172A']
                ];
            @endphp

            @foreach($categories as $cat)
            <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6 text-[#0095FF]">
                    <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5"></i>
                    <span class="font-bold text-slate-800">{{ $cat['label'] }}</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">Rp{{ number_format($stats[$cat['key']], 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 font-medium italic">pengeluaran aktif</p>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="md:col-span-2 bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <h4 class="font-bold text-lg mb-10">Grafik Pengeluaran 6 Bulan Terakhir</h4>
                <div class="h-[350px]">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            {{-- Distribusi Pengeluaran (FIX: Persentase Dinamis) --}}
            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <h4 class="font-bold text-lg mb-10">Distribusi Pengeluaran</h4>
                <div class="h-[250px] mb-8">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="space-y-4">
                    @foreach($categories as $cat)
                        @php 
                            // Hitung persentase secara otomatis
                            $val = $stats[$cat['key']];
                            $percent = $stats['total'] > 0 ? round(($val / $stats['total']) * 100) : 0;
                        @endphp
                        <div class="flex justify-between items-center text-sm italic">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $cat['color'] }}"></div>
                                <span class="text-slate-500 font-medium">{{ $cat['label'] }} {{ $percent }}%</span>
                            </div>
                            <span class="font-bold text-slate-800 text-xs">Rp{{ number_format($val, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Baris Bawah: Pesanan & Langganan (Sudah Dinamis) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 italic">
            {{-- Pesanan Terbaru --}}
            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-10">
                    <i data-lucide="package" class="w-6 h-6 text-slate-800"></i>
                    <h4 class="font-bold text-xl">Status Pesanan Terbaru</h4>
                </div>
                <div class="space-y-6">
                    @forelse($orders as $order)
                    <div class="p-6 rounded-3xl border border-slate-50 bg-slate-50/30">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex gap-4">
                                <i data-lucide="{{ $order->type == 'laundry' ? 'shirt' : 'utensils' }}" class="w-5 h-5 text-slate-500"></i>
                                <div>
                                    <p class="text-xs text-slate-400 font-bold mb-1">{{ $order->order_number }}</p>
                                    <h5 class="font-bold text-sm text-slate-800">{{ $order->name }}</h5>
                                </div>
                            </div>
                            <span class="px-4 py-1 rounded-full text-[10px] font-black 
                                {{ $order->status == 'Diproses' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600' }}">
                                {{ $order->status }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 flex items-center gap-2 mb-3 italic">
                            <i data-lucide="clock" class="w-3 h-3"></i> {{ $order->created_at->format('d M Y') }}
                        </p>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                            <span class="text-xs text-slate-400 font-bold">Total:</span>
                            <span class="font-black text-slate-800">Rp{{ number_format($order->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                        <p class="text-center text-slate-400 py-10">Belum ada pesanan.</p>
                    @endforelse
                </div>
            </div>

            {{-- Langganan Aktif --}}
            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-10">
                    <h4 class="font-bold text-xl">Langganan Aktif</h4>
                    <button class="text-xs font-bold border border-slate-200 px-4 py-2 rounded-xl hover:bg-slate-50 transition">Kelola Semua</button>
                </div>
                @if($activeSub)
                <div class="p-8 rounded-[32px] border border-slate-100 bg-white shadow-sm italic">
                    <div class="flex gap-4 mb-6">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0095FF]">
                            <i data-lucide="{{ $activeSub->type == 'kos' ? 'home' : 'package' }}" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-800">{{ $activeSub->name }}</h5>
                            <p class="text-[#0095FF] font-black text-xl">Rp{{ number_format($activeSub->price, 0, ',', '.') }}<span class="text-xs font-medium">/bulan</span></p>
                        </div>
                    </div>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-xs italic font-medium">
                            <span class="text-slate-400">Mulai:</span>
                            <span class="text-slate-800 font-bold">{{ $activeSub->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-xs italic font-medium">
                            <span class="text-slate-400">Tagihan berikutnya:</span>
                            <span class="text-slate-800 font-bold">{{ $activeSub->created_at->addDays(30)->format('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center pt-6 border-t border-slate-50">
                        <div class="flex items-center gap-2 text-green-500 font-bold text-xs">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Auto-renewal aktif
                        </div>
                        <button class="text-slate-400 font-bold text-xs hover:text-slate-600 transition">Edit</button>
                    </div>
                </div>
                @else
                <div class="p-10 text-center border-2 border-dashed border-slate-100 rounded-[32px]">
                    <p class="text-slate-400 text-sm font-medium">Kamu belum memiliki langganan aktif.</p>
                    <a href="{{ route('home') }}" class="text-[#0095FF] font-black text-xs mt-4 inline-block underline italic">Cari Layanan Sekarang</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT GRAFIK --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script> {{-- PASTIKAN INI ADA --}}
<script>
    // Data dari Controller
    const months = @json($chartMonths);
    const chartData = @json($chartData);

    // Bar Chart
    const ctxBar = document.getElementById('barChart');
    if (ctxBar) {
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    { label: 'Katering', data: chartData.katering, backgroundColor: '#0061AF', borderRadius: 8 },
                    { label: 'Kos', data: chartData.kos, backgroundColor: '#0095FF', borderRadius: 8 },
                    { label: 'Laundry', data: chartData.laundry, backgroundColor: '#0F172A', borderRadius: 8 }
                ]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // Pie Chart
    const ctxPie = document.getElementById('pieChart');
    if (ctxPie) {
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Kos', 'Katering', 'Laundry'],
                datasets: [{ 
                    data: [{{ $stats['kos'] }}, {{ $stats['katering'] }}, {{ $stats['laundry'] }}], 
                    backgroundColor: ['#0095FF', '#0061AF', '#0F172A'], 
                    borderWidth: 0 
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } }, 
                cutout: '70%' 
            }
        });
    }
</script>
@endsection