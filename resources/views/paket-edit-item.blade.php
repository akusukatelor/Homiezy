@extends('layouts.app')

@section('content')
<div class="bg-[#F8FAFC] min-h-screen pt-32 pb-20" x-data="{ selectedPrice: 0, selectedId: '' }">
    <div class="container mx-auto px-6 max-w-4xl">
        <h3 class="text-3xl font-black text-[#0095FF] mb-10 italic uppercase">{{ $packageName }}</h3>
        
        <form action="{{ route('order.update_item', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="category" value="{{ $category }}">
            <input type="hidden" name="service_id" x-model="selectedId">
            <input type="hidden" name="new_price" x-model="selectedPrice">

           <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    @forelse($availableServices as $service)
        <div @click="selectedId = '{{ $service->id }}'; selectedPrice = {{ $service->price }}"
             :class="selectedId == '{{ $service->id }}' ? 'border-[#0095FF] bg-blue-50' : 'border-slate-100 bg-white'"
             class="p-6 rounded-[32px] border-2 cursor-pointer transition hover:shadow-lg">
            <h5 class="font-bold text-slate-800">{{ $service->name }}</h5>
            <p class="text-[#0095FF] font-black text-xl italic">Rp{{ number_format($service->price, 0, ',', '.') }}</p>
        </div>
    @empty
        <div class="col-span-full p-10 text-center bg-white rounded-[32px] border-2 border-dashed">
            <p class="text-slate-400 font-bold">Wah, vendor {{ $category }} belum ada yang terdaftar nih!</p>
        </div>
    @endforelse
</div>

            <button type="submit" x-show="selectedId" class="w-full py-6 bg-[#0095FF] text-white rounded-[32px] font-black text-xl italic uppercase shadow-xl transition transform active:scale-95">
                Konfirmasi Perubahan ✅
            </button>
        </form>
    </div>
</div>
@endsection