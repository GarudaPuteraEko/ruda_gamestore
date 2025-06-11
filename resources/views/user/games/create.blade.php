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
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium">Deskripsi</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium">Kategori</label>
            <select name="category_id" required class="w-full border px-3 py-2 rounded @error('category_id') border-red-500 @enderror">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1"
                   class="w-full border px-3 py-2 rounded @error('price') border-red-500 @enderror">
            @error('price') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium">File Game (ZIP / HTML5)</label>
            <input type="file" name="file" accept=".zip,.html" required class="w-full border px-3 py-2 rounded @error('file') border-red-500 @enderror">
            @error('file') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium">Gambar Game (Opsional)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif" class="w-full border px-3 py-2 rounded @error('image') border-red-500 @enderror">
            <p class="text-sm text-gray-600">Format: JPG, JPEG, PNG, GIF. Maks: 2MB.</p>
            @error('image') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Simpan</button>
    </form>
</div>
@endsection