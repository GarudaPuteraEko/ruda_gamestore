@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Pesan Sukses atau Error -->
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

        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-8">Daftar Game</h1>

        <!-- Form Pencarian -->
        <div class="mb-6 bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.games.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-blue-700 mb-1">Cari Game</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan judul game..." 
                           class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900">
                </div>
                <div class="flex-1">
                    <label for="category_id" class="block text-sm font-medium text-blue-700 mb-1">Kategori</label>
                    <select name="category_id" id="category_id" 
                            class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900">
                        <option value="">Semua Kategori</option>
                        @if($categories->isNotEmpty())
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>Tidak ada kategori tersedia</option>
                        @endif
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-900 transition font-medium shadow-md">
                        Cari
                    </button>
                </div>
            </form>
        </div>

        @if($games->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center text-blue-700">
                Tidak ada game ditemukan.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($games as $game)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all duration-300 transform hover:scale-105 hover:rotate-1 hover:shadow-lg">
                        <!-- Gambar -->
                        <div class="relative">
                            @if ($game->image)
                                <img src="{{ Storage::url($game->image) }}" alt="{{ $game->title }}" class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110">
                            @else
                                <div class="w-full h-48 bg-blue-200 flex items-center justify-center text-blue-600">
                                    No Image
                                </div>
                            @endif
                            <!-- Badge Kategori -->
                            <span class="absolute top-2 left-2 bg-blue-700 text-white text-xs font-medium px-2 py-1 rounded">
                                {{ $game->category->name ?? '-' }}
                            </span>
                        </div>
                        <!-- Konten -->
                        <div class="p-4">
                            <h2 class="text-lg font-bold text-blue-800 mb-2">{{ $game->title }}</h2>
                            <p class="text-sm text-blue-700 mb-3 line-clamp-3">
                                {{ Str::limit($game->description, 100, '...') }}
                            </p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-blue-800 font-semibold">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                                <span class="text-xs text-blue-600">by {{ $game->user ? $game->user->name : 'Admin' }}</span>
                            </div>
                            <!-- Aksi -->
                            <div class="flex space-x-2">
                                @if($game->user && $game->user->role === 'admin')
                                    <a href="{{ route('admin.games.edit', $game->id) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
                                        Edit
                                    </a>
                                @endif
                                <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" class="flex-1" id="delete-form-{{ $game->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium delete-btn"
                                            data-id="{{ $game->id }}">
                                        Hapus
                                    </button>
                                </form>
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
        console.log('Admin games scripts loaded - DOM fully loaded');

        const deleteButtons = document.querySelectorAll('.delete-btn');
        console.log('Delete buttons found:', deleteButtons);

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Delete button clicked for game ID:', this.dataset.id);
                const form = document.getElementById(`delete-form-${this.dataset.id}`);

                if (form) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Game akan dihapus secara permanen!",
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
    });
</script>
@endpush
@endsection