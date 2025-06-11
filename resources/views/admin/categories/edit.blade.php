@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Judul -->
        <h1 class="text-3xl md:text-4xl font-bold text-blue-800 mb-8">Edit Kategori</h1>

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
            <form method="POST" action="{{ route('categories.update', $category) }}" id="category-edit-form">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-blue-700 mb-1">Nama Kategori</label>
                    <input name="name" type="text" value="{{ old('name', $category->name ?? '') }}" required
                           class="w-full px-4 py-2 border border-blue-400 rounded-lg focus:ring-blue-600 focus:border-blue-600 bg-blue-200 text-blue-900 @error('name') border-red-500 @enderror"
                           placeholder="Nama kategori">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
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
        console.log('Categories edit scripts loaded - DOM fully loaded');

        const submitButton = document.querySelector('.submit-btn');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Submit button clicked');
                const form = document.getElementById('category-edit-form');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Perubahan pada kategori akan disimpan. Pastikan nama sudah benar!",
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