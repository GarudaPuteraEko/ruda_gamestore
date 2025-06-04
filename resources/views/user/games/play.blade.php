@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Mainkan: {{ $game->title }}</h1>

    <div class="bg-gray-100 p-4 rounded shadow">
        <p class="mb-2">Deskripsi: {{ $game->description }}</p>
        <p class="mb-4">Kategori: {{ $game->category->name }}</p>

        <iframe src="{{ Storage::url($game->file_path) }}" class="w-full h-[600px] border rounded"></iframe>

        <div class="mt-4">
            <a href="{{ route('user.games.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">← Kembali</a>
        </div>
    </div>
</div>
@endsection
