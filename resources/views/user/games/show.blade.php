@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-3xl">
    <h1 class="text-3xl font-bold mb-4">{{ $game->title }}</h1>
    <p class="mb-2">Kategori: {{ $game->category->name ?? '-' }}</p>
    <p class="mb-4">{{ $game->description }}</p>
    <p class="mb-4 font-semibold">Harga: Rp {{ number_format($game->price, 0, ',', '.') }}</p>

    @if(auth()->check())
    @if(auth()->id() === $game->user_id)
    <a href="{{ route('games.edit', $game->id) }}"
        class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 mr-2">Edit</a>
    <form action="{{ route('games.destroy', $game->id) }}" method="POST" class="inline"
        onsubmit="return confirm('Yakin ingin hapus game ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-600 px-4 py-2 rounded text-white hover:bg-red-700">Hapus</button>
    </form>
    @else
        @php
        $bought = \App\Models\Transaction::where('user_id', auth()->id())
        ->where('game_id', $game->id)
        ->where('status', 'success')->exists();
        @endphp

        @if($bought)
        <a href="{{ route('games.play', $game->id) }}" target="_blank"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Play</a>
        @else
        <form action="{{ route('cart.add', $game->id) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Beli</button>
        </form>
        @endif
    @endif
    @else
    <p><a href="{{ route('login') }}" class="text-blue-600 underline">Login</a> untuk membeli dan main game.</p>
    @endif
</div>
@endsection
