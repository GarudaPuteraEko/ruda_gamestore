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
                    <th class="border p-2 text-left">Judul Game</th>
                    <th class="border p-2 text-left">Status</th>
                    <th class="border p-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td class="border p-2">{{ $transaction->game->title }}</td>
                        <td class="border p-2">{{ ucfirst($transaction->status) }}</td>
                        <td class="border p-2 text-center">
                            @if($transaction->status === 'approved')
                                <a href="{{ route('games.play', $transaction->game->id) }}" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                    Mainkan Game
                                </a>
                            @else
                                <span class="text-gray-500 italic">Menunggu persetujuan admin</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
