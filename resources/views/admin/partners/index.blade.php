@extends('layouts.admin', ['title' => 'Kelola Partner'])

@section('content')
<header class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-black">Kelola Partner</h1>
        <p class="text-slate-500 font-medium">Manajemen partner strategis AmikomEventHub.</p>
    </div>
    <a href="{{ route('admin.partners.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
        + Tambah Partner Baru
    </a>
</header>

<div class="mb-8">
    <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-4 max-w-md">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama partner..." class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 shadow-sm">
        <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-xl font-bold hover:bg-slate-900 transition">Cari</button>
    </form>
</div>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">No</th>
                    <th class="px-8 py-4">Logo</th>
                    <th class="px-8 py-4">Nama Partner</th>
                    <th class="px-8 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($partners as $index => $partner)
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-8 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6">
                        <img src="{{ $partner->logo_url }}" class="w-12 h-12 rounded-xl object-contain bg-slate-50 p-1 border">
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-800">{{ $partner->name }}</p>
                    </td>
                    <td class="px-8 py-6 flex gap-2">
                        <a href="{{ route('admin.partners.edit', $partner->id) }}" class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>
                        <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Hapus partner ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400">Belum ada partner.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
