@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Daftar Transaksi Saya</h1>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($transactions->isEmpty())
        <p>Kamu belum melakukan pembelian game apapun.</p>
        <a href="{{ route('user.games.index') }}" class="text-blue-600 hover:underline">Lihat Game</a>
    @else
        <table class="min-w-full border border-gray-300 mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Gambar</th>
                    <th class="border p-2 text-left">Nama Game</th>
                    <th class="border p-2 text-left">Deskripsi</th>
                    <th class="border p-2 text-left">Kategori</th>
                    <th class="border p-2 text-left">Harga</th>
                    <th class="border p-2 text-left">Pembuat</th>
                    <th class="border p-2 text-left">Status</th>
                    <th class="border p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4">
                            @if ($transaction->game->image)
                                <img src="{{ Storage::url($transaction->game->image) }}" alt="{{ $transaction->game->title }}" class="h-16 w-16 object-cover rounded">
                            @else
                                <span class="text-gray-500 text-sm">No Image</span>
                            @endif
                        </td>
                        <td class="border p-2">{{ $transaction->game->title }}</td>
                        <td class="px-6 py-4 text-gray-700 text-sm max-w-xs whitespace-normal">
                            {{ Str::limit($transaction->game->description, 100, '...') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $transaction->game->category->name ?? '-' }}</td>
                        <td class="border p-2 text-left">Rp {{ number_format($transaction->game->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $transaction->game->user ? $transaction->game->user->name : 'Admin' }}
                        </td>
                        <td class="border p-2">{{ ucfirst($transaction->status) }}</td>
                        <td class="border p-2 text-center">
                            @if($transaction->status === 'success')
                                <a href="{{ route('user.games.play', $transaction->game->id) }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                    Mainkan Game
                                </a>
                            @elseif($transaction->status === 'pending')
                                <form action="{{ route('user.transactions.cancel', $transaction->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        Batalkan
                                    </button>
                                </form>
                            @elseif($transaction->status === 'canceled')
                                <span class="text-red-500 italic">Transaksi dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
