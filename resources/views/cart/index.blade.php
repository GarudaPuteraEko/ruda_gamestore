@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-8">Keranjang Belanja</h1>

        <!-- Pesan Sukses/Error -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-blue-200 text-blue-700 rounded-lg shadow-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-200 text-red-800 rounded-lg shadow-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($carts->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center text-blue-700">
                Keranjang kosong.
                <a href="{{ route('user.games.index') }}" class="text-blue-600 hover:underline ml-1">Lihat Game</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @foreach($carts as $cart)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:scale-105 hover:rotate-1 hover:shadow-lg">
                        <!-- Gambar -->
                        <div class="relative">
                            @if ($cart->game->image)
                                <img src="{{ Storage::url($cart->game->image) }}" alt="{{ $cart->game->title }}" class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110">
                            @else
                                <div class="w-full h-48 bg-blue-200 flex items-center justify-center text-blue-600">
                                    No Image
                                </div>
                            @endif
                            <!-- Badge Kategori -->
                            <span class="absolute top-2 left-2 bg-blue-700 text-white text-xs font-medium px-2 py-1 rounded">
                                {{ $cart->game->category->name ?? '-' }}
                            </span>
                        </div>
                        <!-- Konten -->
                        <div class="p-4">
                            <h2 class="text-lg font-bold text-blue-800 mb-2">{{ $cart->game->title }}</h2>
                            <p class="text-sm text-blue-700 mb-3 line-clamp-3">
                                {{ Str::limit($cart->game->description, 100, '...') }}
                            </p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-blue-800 font-semibold">Rp {{ number_format($cart->game->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-blue-600">by {{ $cart->game->user ? $cart->game->user->name : 'Admin' }}</span>
                            </div>
                            <!-- Aksi -->
                            <div class="flex space-x-2">
                                <form action="{{ route('cart.remove', $cart->game->id) }}" method="POST" class="flex-1" id="remove-form-{{ $cart->game->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium delete-btn"
                                            data-id="{{ $cart->game->id }}">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('cart.checkout') }}" method="POST" class="flex-1" id="checkout-form">
                @csrf
                <button type="submit" 
                        class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-900 transition font-medium shadow-md checkout-btn"
                        data-id="checkout">
                    Checkout
                </button>
            </form>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Cart scripts loaded - DOM fully loaded');

        const deleteButtons = document.querySelectorAll('.delete-btn');
        console.log('Delete buttons found:', deleteButtons);

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Delete button clicked for game ID:', this.dataset.id);
                const form = document.getElementById(`remove-form-${this.dataset.id}`);

                if (form) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Game akan dihapus dari keranjang!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log('Confirmed deletion, submitting form for ID:', this.dataset.id);
                            form.submit();
                        } else {
                            console.log('Deletion cancelled for ID:', this.dataset.id);
                        }
                    });
                } else {
                    console.error('Form not found for ID:', this.dataset.id);
                }
            });
        });

        const checkoutButton = document.querySelector('.checkout-btn');
        if (checkoutButton) {
            checkoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Checkout button clicked');
                const form = document.getElementById('checkout-form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Lanjutkan ke proses checkout?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Confirmed checkout, submitting form');
                        form.submit();
                    } else {
                        console.log('Checkout cancelled');
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection