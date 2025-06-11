@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Transaksi</h1>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Pembeli</th>
                <th class="p-2 border">Gambar</th>
                <th class="p-2 border">Game</th>
                <th class="p-2 border">Kategori</th>
                <th class="p-2 border">Harga</th>
                <th class="p-2 border">Pembuat</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td class="p-2 border">{{ $transaction->user->name }}</td>
                <td class="px-6 py-4">
                    @if ($transaction->game->image)
                        <img src="{{ Storage::url($transaction->game->image) }}" alt="{{ $transaction->game->title }}" class="h-16 w-16 object-cover rounded">
                    @else
                        <span class="text-gray-500 text-sm">No Image</span>
                    @endif
                </td>
                <td class="p-2 border">{{ $transaction->game->title }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $transaction->game->category->name ?? '-' }}</td>
                <td class="border p-2 text-left">Rp {{ number_format($transaction->game->price, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $transaction->game->user ? $transaction->game->user->name : 'Admin' }}
                </td>
                <td class="p-2 border">{{ ucfirst($transaction->status) }}</td>
                <td class="p-2 border text-center">
                    @if ($transaction->status == 'pending')
                        <form action="{{ route('admin.transactions.approve', $transaction->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded">Approve</button>
                        </form>
                        <form action="{{ route('admin.transactions.cancel', $transaction->id) }}" method="POST" class="inline ml-2">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Cancel</button>
                        </form>
                    @else
                        <span class="text-gray-500">Sudah {{ $transaction->status }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
