@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">{{ isset($category) ? 'Edit' : 'Tambah' }} Kategori</h2>

    <form method="POST" action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <input name="name" type="text"
               value="{{ old('name', $category->name ?? '') }}"
               class="border p-2 w-full mb-4" placeholder="Nama kategori">

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
            Simpan
        </button>
    </form>
</div>
@endsection
