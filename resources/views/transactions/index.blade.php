@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-8">Daftar Transaksi Saya</h1>

        <!-- Pesan Sukses -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-blue-200 text-blue-700 rounded-lg shadow-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($transactions->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center text-blue-700">
                Kamu belum melakukan pembelian game apapun.
                <a href="{{ route('user.games.index') }}" class="text-blue-600 hover:underline ml-1">Lihat Game</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($transactions as $transaction)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:scale-105 hover:rotate-1 hover:shadow-lg">
                        <!-- Gambar -->
                        <div class="relative">
                            @if ($transaction->game->image)
                                <img src="{{ Storage::url($transaction->game->image) }}" alt="{{ $transaction->game->title }}" class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110">
                            @else
                                <div class="w-full h-48 bg-blue-200 flex items-center justify-center text-blue-600">
                                    No Image
                                </div>
                            @endif
                            <!-- Badge Status -->
                            <span class="absolute top-2 left-2 bg-{{ $transaction->status === 'success' ? 'green' : ($transaction->status === 'pending' ? 'yellow' : 'red') }}-500 text-white text-xs font-medium px-2 py-1 rounded">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                        <!-- Konten -->
                        <div class="p-4">
                            <h2 class="text-lg font-bold text-blue-800 mb-2">{{ $transaction->game->title }}</h2>
                            <p class="text-sm text-blue-700 mb-3 line-clamp-3">
                                {{ Str::limit($transaction->game->description, 100, '...') }}
                            </p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-blue-800 font-semibold">Rp {{ number_format($transaction->game->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-blue-600">by {{ $transaction->game->user ? $transaction->game->user->name : 'Admin' }}</span>
                            </div>
                            <!-- Aksi -->
                            <div class="flex space-x-2">
                                @if($transaction->status === 'success')
                                    <a href="{{ route('user.games.play', $transaction->game->id) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                                        Mainkan Game
                                    </a>
                                @elseif($transaction->status === 'pending')
                                    <form action="{{ route('user.transactions.cancel', $transaction->id) }}" method="POST" class="flex-1" id="cancel-form-{{ $transaction->id }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium cancel-btn"
                                                data-id="{{ $transaction->id }}">
                                            Batalkan
                                        </button>
                                    </form>
                                @elseif($transaction->status === 'canceled')
                                    <span class="text-red-500 italic text-center w-full block">Transaksi dibatalkan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Transactions scripts loaded - DOM fully loaded');

        const cancelButtons = document.querySelectorAll('.cancel-btn');
        console.log('Cancel buttons found:', cancelButtons);

        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Cancel button clicked for transaction ID:', this.dataset.id);
                const form = document.getElementById(`cancel-form-${this.dataset.id}`);

                if (form) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Transaksi ini akan dibatalkan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, batalkan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('Confirmed cancellation, submitting form for ID:', this.dataset.id);
                            form.submit();
                        } else {
                            console.log('Cancellation cancelled for ID:', this.dataset.id);
                        }
                    });
                } else {
                    console.error('Form not found for ID:', this.dataset.id);
                }
            });
        });
    });
</script>
@endpush
@endsection