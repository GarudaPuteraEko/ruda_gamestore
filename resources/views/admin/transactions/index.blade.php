@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-8">Manajemen Transaksi</h1>

        @if($transactions->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center text-blue-700">
                Tidak ada transaksi ditemukan.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($transactions as $transaction)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                        <!-- Header Card -->
                        <div class="bg-blue-200 p-4">
                            <h2 class="text-lg font-semibold text-blue-800">Transaksi #{{ $transaction->id }}</h2>
                            <p class="text-sm text-blue-600">Pembeli: {{ $transaction->user->name }}</p>
                        </div>
                        <!-- Gambar -->
                        <div class="relative p-4">
                            @if ($transaction->game->image)
                                <img src="{{ Storage::url($transaction->game->image) }}" alt="{{ $transaction->game->title }}" class="w-full h-32 object-cover rounded transition-transform duration-300 hover:scale-110">
                            @else
                                <div class="w-full h-32 bg-blue-200 flex items-center justify-center text-blue-600">
                                    No Image
                                </div>
                            @endif
                            <span class="absolute top-2 left-2 bg-blue-700 text-white text-xs font-medium px-2 py-1 rounded">
                                {{ $transaction->game->category->name ?? '-' }}
                            </span>
                        </div>
                        <!-- Konten -->
                        <div class="p-4">
                            <h3 class="text-md font-bold text-blue-800 mb-2">{{ $transaction->game->title }}</h3>
                            <p class="text-sm text-blue-700 mb-2">Harga: Rp {{ number_format($transaction->game->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-blue-600 mb-2">Pembuat: {{ $transaction->game->user ? $transaction->game->user->name : 'Admin' }}</p>
                            <p class="text-sm text-blue-700 font-medium mb-4">Status: {{ ucfirst($transaction->status) }}</p>
                            <!-- Aksi -->
                            <div class="flex space-x-2">
                                @if ($transaction->status == 'pending')
                                    <form action="{{ route('admin.transactions.approve', $transaction->id) }}" method="POST" class="flex-1" id="approve-form-{{ $transaction->id }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium approve-btn"
                                                data-id="{{ $transaction->id }}">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.transactions.cancel', $transaction->id) }}" method="POST" class="flex-1" id="cancel-form-{{ $transaction->id }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium cancel-btn"
                                                data-id="{{ $transaction->id }}">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="w-full text-center text-gray-500 text-sm">Sudah {{ $transaction->status }}</span>
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
        console.log('Transactions index scripts loaded - DOM fully loaded');

        const approveButtons = document.querySelectorAll('.approve-btn');
        const cancelButtons = document.querySelectorAll('.cancel-btn');
        console.log('Approve buttons found:', approveButtons);
        console.log('Cancel buttons found:', cancelButtons);

        approveButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Approve button clicked for transaction ID:', this.dataset.id);
                const form = document.getElementById(`approve-form-${this.dataset.id}`);

                if (form) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Transaksi akan disetujui!",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, setujui!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('Confirmed approval, submitting form for ID:', this.dataset.id);
                            form.submit();
                        } else {
                            console.log('Approval cancelled for ID:', this.dataset.id);
                        }
                    });
                } else {
                    console.error('Approve form not found for ID:', this.dataset.id);
                }
            });
        });

        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Cancel button clicked for transaction ID:', this.dataset.id);
                const form = document.getElementById(`cancel-form-${this.dataset.id}`);

                if (form) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Transaksi akan dibatalkan!",
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
                    console.error('Cancel form not found for ID:', this.dataset.id);
                }
            });
        });
    });
</script>
@endpush
@endsection