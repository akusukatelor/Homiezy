@extends('layouts.admin-super')

@section('admin_content')
@section('admin_content')
<div class="space-y-8" x-data="{ confirmDelete: false, deleteUrl: '', deleteName: '' }">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-slate-800 italic uppercase tracking-tighter">Kelola User 👥</h1>
            <p class="text-slate-400 font-bold mt-1">{{ $users->total() }} akun terdaftar</p>
        </div>
        <a href="{{ route('superadmin.dashboard') }}"
           class="flex items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-700 transition italic">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-6 py-4 rounded-[20px] font-bold text-sm italic flex items-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-100 text-red-500 px-6 py-4 rounded-[20px] font-bold text-sm italic flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4"></i> {{ session('error') }}
    </div>
    @endif

    {{-- Filter & Search --}}
    <form method="GET" action="{{ route('superadmin.users') }}" class="flex gap-3 flex-wrap">
        {{-- Role filter --}}
        <div class="flex gap-2">
            @foreach(['' => 'Semua', 'customer' => 'Customer', 'mitra' => 'Mitra'] as $key => $label)
            <a href="{{ route('superadmin.users', ['role' => $key, 'search' => $search]) }}"
               class="px-5 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition
               {{ $role === $key
                   ? 'bg-[#0F172A] text-white'
                   : 'bg-white text-slate-400 border border-slate-100 hover:border-slate-300' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px]">
            <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 w-4 h-4"></i>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Cari nama atau email..."
                   class="w-full pl-12 pr-6 py-3 bg-white rounded-[20px] border border-slate-100 font-bold text-slate-700 italic focus:ring-2 focus:ring-[#0095FF] outline-none">
        </div>
        <input type="hidden" name="role" value="{{ $role }}">
        <button type="submit"
                class="px-6 py-3 bg-[#0095FF] text-white rounded-[20px] font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition italic">
            Cari
        </button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">
                    <th class="px-10 py-6">User</th>
                    <th>Role</th>
                    <th>Google</th>
                    <th>Bergabung</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 transition {{ $user->id === auth()->id() ? 'opacity-50' : '' }}">
                    {{-- User info --}}
                    <td class="px-10 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center font-black text-slate-500 italic text-sm flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-black text-slate-800 italic">{{ $user->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Role --}}
                    <td>
                        <span class="px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border
                            {{ $user->role === 'mitra' ? 'bg-blue-50 text-[#0095FF] border-blue-100' :
                               ($user->role === 'superadmin' ? 'bg-orange-50 text-orange-500 border-orange-100' :
                               'bg-slate-50 text-slate-500 border-slate-100') }}">
                            {{ $user->role }}
                        </span>
                    </td>

                    {{-- Google --}}
                    <td>
                        @if($user->google_id)
                            <span class="flex items-center gap-1 text-[10px] font-black text-emerald-500 italic">
                                <i data-lucide="check" class="w-3 h-3"></i> Ya
                            </span>
                        @else
                            <span class="text-[10px] font-bold text-slate-300 italic">—</span>
                        @endif
                    </td>

                    {{-- Tanggal --}}
                    <td>
                        <p class="font-bold text-slate-600 italic text-sm">
                            {{ $user->created_at->format('d M Y') }}
                        </p>
                    </td>

                    {{-- Aksi --}}
                    <td class="text-center px-6">
                        @if($user->id !== auth()->id() && $user->role !== 'superadmin')
                        <button
                            @click="confirmDelete = true; deleteUrl = '{{ route('superadmin.users.destroy', $user->id) }}'; deleteName = '{{ addslashes($user->name) }}'"
                            class="bg-red-50 text-red-500 px-5 py-2 rounded-xl text-[10px] font-black hover:bg-red-100 hover:scale-105 transition italic uppercase tracking-widest border border-red-100">
                            Hapus
                        </button>
                        @else
                        <span class="text-[10px] font-bold text-slate-300 italic uppercase">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-20 text-center font-bold text-slate-300 italic uppercase tracking-widest">
                        Tidak ada user ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="flex justify-center">
        {{ $users->appends(['role' => $role, 'search' => $search])->links() }}
    </div>
    @endif

    {{-- Modal Konfirmasi Hapus --}}
    <div x-show="confirmDelete" x-cloak
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-[40px] p-10 max-w-sm w-full shadow-2xl"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="w-16 h-16 bg-red-50 rounded-[20px] flex items-center justify-center mx-auto mb-6">
                <i data-lucide="user-x" class="w-7 h-7 text-red-500"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 italic text-center uppercase tracking-tighter mb-2">
                Hapus User?
            </h3>
            <p class="text-slate-400 font-bold text-sm text-center mb-2">
                Akun <span class="text-slate-700 italic" x-text="deleteName"></span> akan dihapus permanen beserta semua datanya.
            </p>
            <p class="text-red-400 font-black text-[10px] text-center uppercase tracking-widest mb-8 italic">
                Tindakan ini tidak bisa dibatalkan!
            </p>
            <div class="flex gap-3">
                <button @click="confirmDelete = false"
                        class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-[20px] font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition italic">
                    Batal
                </button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full py-4 bg-red-500 text-white rounded-[20px] font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition italic">
                        Ya, Hapus!
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
