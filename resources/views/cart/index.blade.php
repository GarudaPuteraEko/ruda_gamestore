@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($carts->isEmpty())
        <p>Keranjang kosong.</p>
        <a href="{{ route('user.games.index') }}" class="text-blue-600 hover:underline">Lihat Game</a>
    @else
        <table class="min-w-full border border-gray-300 mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Gambar</th>
                    <th class="border p-2 text-left">Nama Game</th>
                    <th class="border p-2 text-left">Deskripsi</th>
                    <th class="border p-2 text-left">Kategori</th>
                    <th class="border p-2 text-right">Harga</th>
                    <th class="border p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carts as $cart)
                <tr>
                    <td class="px-6 py-4">
                        @if ($cart->game->image)
                            <img src="{{ Storage::url($cart->game->image) }}" alt="{{ $cart->game->title }}" class="h-16 w-16 object-cover rounded">
                        @else
                            <span class="text-gray-500 text-sm">No Image</span>
                        @endif
                    </td>
                    <td class="border p-2">{{ $cart->game->title }}</td>
                    <td class="px-6 py-4 text-gray-700 text-sm max-w-xs whitespace-normal">
                        {{ Str::limit($cart->game->description, 100, '...') }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $cart->game->category->name ?? '-' }}</td>
                    <td class="border p-2 text-right">Rp {{ number_format($cart->game->price, 0, ',', '.') }}</td>
                    <td class="border p-2 text-center">
                        <form action="{{ route('cart.remove', $cart->game->id) }}" method="POST" onsubmit="return confirm('Hapus game dari keranjang?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <form action="{{ route('cart.checkout') }}" method="POST" onsubmit="return confirm('Lanjutkan checkout?');">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Checkout
            </button>
        </form>
    @endif
</div>
@endsection
