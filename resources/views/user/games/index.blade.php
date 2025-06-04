@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Game</h1>
        <div class="space-x-3">
            <a href="{{ route('transactions.index') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                Transaksi
            </a>
            <a href="{{ route('user.games.create') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                + Tambah Game
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Game</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($games as $game)
                    <tr>
                        <td class="px-6 py-4 text-gray-800 font-semibold">{{ $game->title }}</td>
                        <td class="px-6 py-4 text-gray-700 text-sm max-w-xs whitespace-normal">
                            {{ Str::limit($game->description, 100, '...') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $game->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($game->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            @if(auth()->id() == $game->user_id)
                                <a href="{{ route('user.games.edit', $game) }}" class="text-blue-500 hover:underline">Edit</a>
                                <form action="{{ route('user.games.destroy', $game) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus game ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                </form>
                            @else
                                <form action="{{ route('cart.add', $game) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition">
                                        Beli
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('cart.index') }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
            Lihat Keranjang
        </a>
    </div>
</div>
@endsection
