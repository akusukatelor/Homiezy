@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20 font-sans">
    <div class="container mx-auto px-6">
        
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Dashboard Saya</h1>
            <p class="text-slate-500 font-medium">Kelola pengeluaran dan pantau semua transaksimu di sini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-[#0095FF] p-8 rounded-[32px] text-white shadow-xl shadow-blue-200">
                <p class="text-sm font-medium opacity-80 mb-6">Total Pengeluaran Bulan Ini</p>
                <h3 class="text-3xl font-bold mb-4">Rp1.800.000</h3>
                <p class="text-xs font-medium flex items-center gap-2">
                    <i data-lucide="trending-down" class="w-4 h-4"></i> 5% lebih hemat dari bulan lalu
                </p>
            </div>

            @foreach([
                ['label' => 'Kos', 'price' => '1.200.000', 'icon' => 'home'],
                ['label' => 'Katering', 'price' => '450.000', 'icon' => 'utensils'],
                ['label' => 'Laundry', 'price' => '150.000', 'icon' => 'shirt']
            ] as $card)
            <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-6 text-[#0095FF]">
                    <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5"></i>
                    <span class="font-bold text-slate-800">{{ $card['label'] }}</span>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-1">Rp{{ $card['price'] }}</h3>
                <p class="text-xs text-slate-400 font-medium italic">per bulan</p>
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

            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <h4 class="font-bold text-lg mb-10">Distribusi Pengeluaran</h4>
                <div class="h-[250px] mb-8">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="space-y-4">
                    @foreach([['L' => 'Kos', 'P' => '67%', 'V' => '1.200.000', 'C' => '#0095FF'], ['L' => 'Katering', 'P' => '25%', 'V' => '450.000', 'C' => '#0061AF'], ['L' => 'Laundry', 'P' => '8%', 'V' => '150.000', 'C' => '#0F172A']] as $item)
                    <div class="flex justify-between items-center text-sm italic">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $item['C'] }}"></div>
                            <span class="text-slate-500 font-medium">{{ $item['L'] }} {{ $item['P'] }}</span>
                        </div>
                        <span class="font-bold text-slate-800 text-xs">Rp{{ $item['V'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 italic">
            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-3 mb-10">
                    <i data-lucide="package" class="w-6 h-6 text-slate-800"></i>
                    <h4 class="font-bold text-xl">Status Pesanan Terbaru</h4>
                </div>
                
                <div class="space-y-6">
                    @foreach([
                        ['id' => 'ORD001', 'name' => 'QuickWash Express - Cuci Setrika', 'status' => 'Diproses', 'date' => '2 Des 2025', 'price' => '45.000', 'color' => 'blue', 'desc' => 'Sedang dicuci, estimasi selesai 2 jam lagi'],
                        ['id' => 'ORD002', 'name' => 'Dapur Mama Rina - Paket Harian', 'status' => 'Diantar', 'date' => '2 Des 2025', 'price' => '25.000', 'color' => 'green', 'desc' => 'Makanan sudah diantar'],
                        ['id' => 'ORD003', 'name' => 'QuickWash Express - Express 3 Jam', 'status' => 'Selesai', 'date' => '1 Des 2025', 'price' => '60.000', 'color' => 'emerald', 'desc' => 'Sudah diambil']
                    ] as $order)
                    <div class="p-6 rounded-3xl border border-slate-50 bg-slate-50/30">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex gap-4">
                                <i data-lucide="{{ str_contains($order['name'], 'Quick') ? 'shirt' : 'utensils' }}" class="w-5 h-5 text-slate-500"></i>
                                <div>
                                    <p class="text-xs text-slate-400 font-bold mb-1">{{ $order['id'] }}</p>
                                    <h5 class="font-bold text-sm text-slate-800">{{ $order['name'] }}</h5>
                                </div>
                            </div>
                            <span class="px-4 py-1 rounded-full text-[10px] font-black {{ $order['color'] == 'blue' ? 'bg-blue-100 text-blue-600' : ($order['color'] == 'green' ? 'bg-green-100 text-green-600' : 'bg-emerald-100 text-emerald-600') }}">
                                {{ $order['status'] }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-400 flex items-center gap-2 mb-3 italic"><i data-lucide="clock" class="w-3 h-3"></i> {{ $order['date'] }}</p>
                        <p class="text-xs text-blue-500 font-bold mb-4 italic">{{ $order['desc'] }}</p>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                            <span class="text-xs text-slate-400 font-bold">Total:</span>
                            <span class="font-black text-slate-800">Rp{{ $order['price'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-10 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-10">
                    <h4 class="font-bold text-xl">Langganan Aktif</h4>
                    <button class="text-xs font-bold border border-slate-200 px-4 py-2 rounded-xl hover:bg-slate-50 transition">Kelola Semua</button>
                </div>

                <div class="p-8 rounded-[32px] border border-slate-100 bg-white shadow-sm italic">
                    <div class="flex gap-4 mb-6">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-[#0095FF]">
                            <i data-lucide="home" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-slate-800">Paket Standard Meal - Kos Putri Mawar</h5>
                            <p class="text-[#0095FF] font-black text-xl">Rp1.650.000<span class="text-xs font-medium">/bulan</span></p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-xs italic font-medium">
                            <span class="text-slate-400">Mulai:</span>
                            <span class="text-slate-800 font-bold">1 Jan 2025</span>
                        </div>
                        <div class="flex justify-between text-xs italic font-medium">
                            <span class="text-slate-400">Tagihan berikutnya:</span>
                            <span class="text-slate-800 font-bold">1 Jan 2026</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-6 border-t border-slate-50">
                        <div class="flex items-center gap-2 text-green-500 font-bold text-xs">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Auto-renewal aktif
                        </div>
                        <button class="text-slate-400 font-bold text-xs hover:text-slate-600 transition">Edit</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Inisialisasi Grafik Batang (Gambar 1)
    const ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [
                { label: 'Katering', data: [450000, 480000, 450000, 500000, 450000, 450000], backgroundColor: '#0061AF', borderRadius: 8 },
                { label: 'Kos', data: [1200000, 1200000, 1200000, 1200000, 1200000, 1200000], backgroundColor: '#0095FF', borderRadius: 8 },
                { label: 'Laundry', data: [150000, 120000, 150000, 140000, 150000, 150000], backgroundColor: '#0F172A', borderRadius: 8 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
    });

    // Inisialisasi Grafik Lingkaran (Gambar 1)
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ['Kos', 'Katering', 'Laundry'],
            datasets: [{ data: [67, 25, 8], backgroundColor: ['#0095FF', '#0061AF', '#0F172A'], borderWidth: 0 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, cutout: '70%' }
    });
</script>
@endsection