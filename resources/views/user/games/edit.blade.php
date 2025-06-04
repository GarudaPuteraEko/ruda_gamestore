@extends('layouts.app')

@section('content')
<div class="container max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Game</h1>

    <form action="{{ route('user.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block font-medium">Judul Game</label>
            <input type="text" name="title" value="{{ old('title', $game->title) }}" required
                   class="w-full border px-3 py-2 rounded @error('title') border-red-500 @enderror">
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium">Deskripsi</label>
            <textarea name="description" class="w-full border px-3 py-2 rounded">{{ old('description', $game->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Harga (Rp)</label>
            <input type="number" name="price" value="{{ old('price', $game->price) }}" required min="0"
                   class="w-full border px-3 py-2 rounded @error('price') border-red-500 @enderror">
            @error('price') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-medium">Kategori</label>
            <select name="category_id" required class="w-full border px-3 py-2 rounded">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $game->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Ganti File Game (ZIP)</label>
            <input type="file" name="file" accept=".zip" class="w-full border px-3 py-2 rounded">
            <p class="text-sm text-gray-600">Kosongkan jika tidak ingin mengganti file.</p>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
