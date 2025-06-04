@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Daftar Kategori</h2>

    <a href="{{ route('categories.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-6 inline-block">
        + Tambah Kategori
    </a>

    @foreach($categories as $category)
        <div class="flex justify-between items-center border-b py-2">
            <div>{{ $category->name }}</div>
            <div>
                <a href="{{ route('categories.edit', $category) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button onclick="return confirm('Yakin hapus?')" class="text-red-500 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection

