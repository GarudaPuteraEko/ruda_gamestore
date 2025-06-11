@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-8">Tambah Game</h1>

        <!-- Pesan Error -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-200 text-red-800 rounded-lg shadow-md flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" id="game-create-form">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-blue-700 mb-1">Judul Game</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900 @error('title') border-red-500 @enderror">
                    @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-blue-700 mb-1">Deskripsi</label>
                    <textarea name="description" class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-blue-700 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900 @error('category_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-blue-700 mb-1">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1"
                           class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900 @error('price') border-red-500 @enderror">
                    @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-blue-700 mb-1">File Game (ZIP/HTML)</label>
                    <input type="file" name="file" accept=".zip,.html" required class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900 @error('file') border-red-500 @enderror">
                    <p class="text-sm text-blue-600 mt-1">Format: ZIP atau HTML. Maks: 50MB.</p>
                    @error('file') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-blue-700 mb-1">Gambar Game (Opsional)</label>
                    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif" class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900 @error('image') border-red-500 @enderror">
                    <p class="text-sm text-blue-600 mt-1">Format: JPG, JPEG, PNG, GIF. Maks: 2MB.</p>
                    @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" 
                        class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-900 transition font-medium shadow-md submit-btn"
                        data-id="submit">
                    Simpan
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Admin create scripts loaded - DOM fully loaded');

        const submitButton = document.querySelector('.submit-btn');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Submit button clicked');
                const form = document.getElementById('game-create-form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data game akan disimpan. Pastikan semua informasi sudah benar!",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Confirmed submission, submitting form');
                        form.submit();
                    } else {
                        console.log('Submission cancelled');
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection