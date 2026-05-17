@extends('layouts.admin', ['title' => 'Edit Partner'])

@section('content')
<header class="mb-10">
    <h1 class="text-3xl font-black">Edit Partner</h1>
    <p class="text-slate-500 font-medium">Perbarui informasi partner pendukung event Anda.</p>
</header>

<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 max-w-2xl">
    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
            <input type="text" name="name" value="{{ $partner->name }}" required
                   class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">URL Logo Partner</label>
            <div class="flex gap-4 items-center mb-3">
                <img src="{{ $partner->logo_url }}" class="w-16 h-16 rounded-xl object-contain bg-slate-50 border p-2 shadow-sm" alt="Logo {{ $partner->name }}">
            </div>
            <input type="url" name="logo_url" value="{{ $partner->logo_url }}" required
                   class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
        </div>

        <div class="flex gap-4 mt-8">
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.partners.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition text-center">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
