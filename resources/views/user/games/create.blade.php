<!-- resources/views/user/games/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Tambah Game</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-medium">Judul Game</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   class="w-full border px-3 py-2 rounded @error('title') border-red-500 @enderror">
        </div>

        <div class="mb-4">
            <label class="block font-medium">Deskripsi</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Kategori</label>
            <select name="category_id" required class="w-full border px-3 py-2 rounded">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}" required min="0"
                   class="w-full border px-3 py-2 rounded @error('price') border-red-500 @enderror">
        </div>

        <div class="mb-4">
            <label class="block font-medium">File Game (ZIP / HTML5)</label>
            <input type="file" name="file" accept=".zip,.html" required class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
