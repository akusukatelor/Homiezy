@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans">
    <div class="container mx-auto px-6">
        
        {{-- Header Dashboard --}}
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Dashboard Saya</h1>
            <p class="text-slate-500 font-medium italic">Kelola pengeluaran dan pantau semua transaksimu di SIMAMAT</p>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-[#0095FF] p-8 rounded-[32px] text-white shadow-xl shadow-blue-200">
                <p class="text-sm font-medium opacity-80 mb-6">Total Pengeluaran Bulan Ini</p>
                <h3 class="text-3xl font-bold mb-4">Rp{{ number_format($stats['total'], 0, ',', '.') }}</h3>
                <p class="text-xs font-medium flex items-center gap-2 italic">
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
            <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex items-center gap-3 mb-6 text-[#0095FF]">
                    <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5"></i>
                    <span class="font-bold text-slate-800 uppercase text-xs tracking-widest">{{ $cat['label'] }}</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">Rp{{ number_format($stats[$cat['key']], 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 font-medium italic">pengeluaran aktif</p>
            </div>
            @endforeach
        </div>

        {{-- Row Grafik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="md:col-span-2 bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <h4 class="font-bold text-lg mb-10 italic">Grafik Pengeluaran 6 Bulan Terakhir</h4>
                <div class="h-[350px]">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <h4 class="font-bold text-lg mb-10 italic">Distribusi Pengeluaran</h4>
                <div class="h-[250px] mb-8">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="space-y-4">
                    @foreach($categories as $cat)
                        @php 
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

        {{-- Row Pesanan & Langganan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 italic">
            
            {{-- Bagian Pesanan Terbaru (Dynamic Tracking) --}}
            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-10">
                    <i data-lucide="package" class="w-6 h-6 text-slate-800"></i>
                    <h4 class="font-bold text-xl uppercase tracking-tighter">Status Pesanan Terbaru</h4>
                </div>
                
                <div class="space-y-10">
    @forelse($orders as $order)
        @php
            // Deteksi apakah ini paket bundling atau pesanan satuan
            $isBundling = $order->type === 'bundling';
            
            // Jika bundling, ambil aturan paketnya. Jika bukan, layanan yang muncul hanya tipe layanannya sendiri
            $allowedCategories = $isBundling 
                                 ? ($packageSettings[$order->package_type] ?? ['kos', 'katering', 'laundry']) 
                                 : [$order->type]; 
        @endphp

        <div class="relative overflow-hidden bg-white rounded-[45px] border border-slate-100 shadow-xl shadow-slate-200/50 group transition-all duration-500 hover:-translate-y-1">
            {{-- Aksesoris Dekoratif (Garis Samping berubah warna berdasarkan tipe) --}}
            <div class="absolute top-0 left-0 w-2 h-full {{ $isBundling ? 'bg-[#0095FF]' : 'bg-indigo-500' }}"></div>

            {{-- 1. HEADER AREA --}}
            <div class="p-8 pb-6 flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-5">
                    {{-- Ikon Header (Box untuk bundling, Ikon spesifik untuk satuan) --}}
                    <div class="w-14 h-14 bg-gradient-to-br {{ $isBundling ? 'from-blue-50 to-blue-100 text-[#0095FF]' : 'from-indigo-50 to-indigo-100 text-indigo-500' }} rounded-[22px] flex items-center justify-center shadow-inner">
                        <i data-lucide="{{ $isBundling ? 'package' : ($order->type == 'kos' ? 'home' : ($order->type == 'katering' ? 'utensils' : 'shirt')) }}" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            {{-- Badge Tipe Order --}}
                            <span class="px-3 py-1 {{ $isBundling ? 'bg-blue-600' : 'bg-indigo-600' }} text-white text-[9px] font-black uppercase tracking-[0.15em] rounded-lg italic shadow-md">
                                {{ $isBundling ? str_replace('-', ' ', $order->package_type) : 'Layanan Satuan' }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold tracking-widest">{{ $order->order_number }}</span>
                        </div>
                        <h5 class="text-xl font-black text-slate-800 italic uppercase leading-none">
                            {{ $isBundling ? 'Paket Bundling' : 'Layanan ' . ucfirst($order->type) }}
                        </h5>
                        @if(!$isBundling)
                            <p class="text-[10px] text-slate-500 font-bold mt-1.5 uppercase tracking-widest">{{ $order->name }}</p>
                        @endif
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="flex flex-col items-end">
                    <span class="px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] border-2 italic flex items-center gap-2 shadow-sm
                        {{ $order->status == 'Pending' ? 'bg-orange-50 text-orange-600 border-orange-100' : 
                        ($order->status == 'Success' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-red-50 text-red-500 border-red-100') }}">
                        @if($order->status == 'Pending')
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                            </span>
                            Menunggu Pembayaran
                        @elseif($order->status == 'Success')
                            <i data-lucide="shield-check" class="w-4 h-4"></i> Aktif
                        @else
                            <i data-lucide="alert-circle" class="w-4 h-4"></i> Dibatalkan
                        @endif
                    </span>
                    <p class="text-[9px] text-slate-400 font-bold mt-2 italic flex items-center gap-1">
                        <i data-lucide="clock" class="w-3 h-3"></i> {{ $order->created_at->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>

            {{-- 2. SERVICES GRID (Item-item dalam paket / satuan) --}}
           <div class="px-8 py-6 bg-slate-50/50 border-y border-slate-100/80">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @if($isBundling)
            {{-- Logika untuk Paket Bundling --}}
            @foreach(['kos', 'katering', 'laundry'] as $cat)
    @php 
        // 1. Tentukan nama kolom (slug)
        $idField = ($cat == 'katering' ? 'catering' : $cat) . '_id';
        $priceField = ($cat == 'katering' ? 'catering' : $cat) . '_price';

        // 2. AMBIL NILAINYA (Ini baris yang tadi kurang)
        $servicePrice = $order->$priceField;

        // 3. Tentukan status aktif ikon
        $isServiceActive = ($order->$idField != null) || ($servicePrice > 0);
    @endphp

    <div class="relative p-5 rounded-[30px] border transition-all duration-300 
        {{ $isServiceActive ? 'bg-white border-slate-100 shadow-sm hover:shadow-md' : 'bg-slate-50/50 border-dashed border-slate-200 opacity-60' }}">
        
        <div class="flex flex-col h-full justify-between">
            <div class="flex justify-between items-start mb-6">
                <div class="p-3.5 rounded-2xl {{ $isServiceActive ? 'bg-[#F0F7FF] text-[#0095FF]' : 'bg-slate-100 text-slate-400' }}">
                    <i data-lucide="{{ $cat == 'kos' ? 'home' : ($cat == 'katering' ? 'utensils' : 'shirt') }}" class="w-5 h-5"></i>
                </div>

                @if($order->status === 'Pending')
                    <a href="{{ route('order.edit_item', [$order->id, $cat]) }}" class="text-[#0095FF] hover:bg-blue-50 p-2 rounded-xl transition duration-300">
                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </a>
                @else
                    <span class="text-slate-300 p-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </span>
                @endif
            </div>

            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] mb-1 italic">
                    Layanan {{ $cat }}
                </p>
                <h6 class="font-black {{ $isServiceActive ? 'text-slate-800' : 'text-slate-400' }} text-lg italic">
                    {{-- Sekarang $servicePrice sudah terdefinisi --}}
                    {{ $servicePrice > 0 ? 'Rp' . number_format($servicePrice, 0, ',', '.') : 'Belum Dipilih' }}
                </h6>
            </div>
        </div>
    </div>
@endforeach
        @else
            {{-- Logika untuk Layanan Satuan (Ditambahkan Tombol Edit) --}}
            <div class="md:col-span-3 bg-white p-5 rounded-[30px] border border-slate-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="p-4 rounded-2xl bg-indigo-50 text-indigo-500">
                        <i data-lucide="{{ $order->type == 'kos' ? 'home' : ($order->type == 'katering' ? 'utensils' : 'shirt') }}" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Nama Vendor</p>
                        <h6 class="font-black text-slate-800 text-base italic">{{ $order->name }}</h6>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Harga Sewa</p>
                        <h6 class="font-black text-slate-800 text-lg italic">Rp{{ number_format($order->price, 0, ',', '.') }}</h6>
                    </div>
                    {{-- Tombol Edit untuk Satuan --}}
                    @if($order->status == 'Pending')
                        <a href="{{ route('order.edit_item', [$order->id, $order->type]) }}" 
                           class="text-[#0095FF] border border-blue-100 bg-white p-3 rounded-xl hover:bg-blue-50 transition shadow-sm">
                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

            {{-- 3. FOOTER AREA (Total & Actions) --}}
            <div class="p-8 flex flex-wrap justify-between items-center gap-6">
                <div class="flex items-center gap-8">
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1 italic">Metode Bayar</p>
                        <div class="flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs font-bold text-slate-700 italic">Transfer Bank</span>
                        </div>
                    </div>
                    <div class="w-px h-10 bg-slate-100"></div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-1 italic text-right">Total Tagihan</p>
                        <p class="text-3xl font-black text-slate-900 italic leading-none">Rp{{ number_format($order->price, 0, ',', '.') }}<span class="text-[10px] text-slate-400 ml-1">/Bln</span></p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @if($order->status == 'Pending')
                        <form action="{{ route('order.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin membatalkan order ini?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="group/btn px-6 py-3 bg-red-50 text-red-500 rounded-2xl text-[10px] font-black uppercase tracking-widest italic hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center gap-2">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Batal
                            </button>
                        </form>
                    @endif
                    
                </div>
            </div>
        </div>
    @empty
        <div class="py-24 text-center bg-white rounded-[50px] border-2 border-dashed border-slate-100">
            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                <i data-lucide="shopping-bag" class="w-10 h-10"></i>
            </div>
            <h4 class="text-xl font-black text-slate-800 italic uppercase mb-2">Belum Ada Transaksi</h4>
            <p class="text-slate-400 text-sm italic mb-8">Pilih layanan satuan atau hemat dengan paket bundling.</p>
            <a href="{{ route('home') }}" class="bg-[#0095FF] text-white px-10 py-4 rounded-2xl font-black uppercase italic text-xs tracking-widest shadow-xl shadow-blue-100 hover:scale-105 transition">Cari Layanan</a>
        </div>
    @endforelse
</div>
            </div>

            {{-- Langganan Aktif --}}
            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-10">
                    <h4 class="font-bold text-xl uppercase tracking-tighter">Langganan Aktif</h4>
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
                        <div class="flex justify-between text-xs font-medium">
                            <span class="text-slate-400 uppercase">Mulai:</span>
                            <span class="text-slate-800 font-bold">{{ $activeSub->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-xs font-medium">
                            <span class="text-slate-400 uppercase">Tagihan berikutnya:</span>
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
                    <p class="text-slate-400 text-sm font-medium italic">Kamu belum memiliki langganan aktif.</p>
                    <a href="{{ route('home') }}" class="text-[#0095FF] font-black text-xs mt-4 inline-block underline italic">Cari Layanan Sekarang</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT GRAFIK --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = @json($chartMonths);
    const chartData = @json($chartData);

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
                plugins: { legend: { position: 'bottom', labels: { font: { weight: 'bold', family: 'sans-serif' } } } }
            }
        });
    }

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