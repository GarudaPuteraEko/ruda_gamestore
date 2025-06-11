@extends('layouts.app')

@section('content')
<div class="container max-w-xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
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

    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Judul Game</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                   class="w-full border px-3 py-2 rounded @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Kategori</label>
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
            <label class="block font-medium text-gray-700">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1"
                   class="w-full border px-3 py-2 rounded @error('price') border-red-500 @enderror">
            @error('price') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">File Game (ZIP/HTML)</label>
            <input type="file" name="file" accept=".zip,.html" required class="w-full border px-3 py-2 rounded @error('file') border-red-500 @enderror">
            <p class="text-sm text-gray-600">Format: ZIP atau HTML. Maks: 50MB.</p>
            @error('file') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium text-gray-700">Gambar Game (Opsional)</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif" class="w-full border px-3 py-2 rounded @error('image') border-red-500 @enderror">
            <p class="text-sm text-gray-600">Format: JPG, JPEG, PNG, GIF. Maks: 2MB.</p>
            @error('image') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
    </form>
</div>
@endsection