@extends('layouts.app')

@section('content')
<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Transaksi</h1>

    <table class="w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">User</th>
                <th class="p-2 border">Game</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
            <tr>
                <td class="p-2 border">{{ $transaction->user->name }}</td>
                <td class="p-2 border">
                    <ul>
                        @foreach ($transaction->items as $item)
                            <li>{{ $item->game->title }}</li>
                        @endforeach
                    </ul>
                </td>
                <td class="p-2 border">{{ $transaction->status }}</td>
                <td class="p-2 border">
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
