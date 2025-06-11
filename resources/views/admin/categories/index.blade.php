@extends('layouts.app')

@section('content')
<div class="bg-gradient-to-b from-blue-100 to-white min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Judul dan Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-blue-800">Daftar Kategori</h1>
            <a href="{{ route('categories.create') }}" 
               class="px-5 py-2 bg-blue-700 text-white rounded-lg hover:bg-blue-900 transition font-medium shadow-md">
                + Tambah Kategori
            </a>
        </div>

        @if($categories->isEmpty())
            <div class="bg-white p-6 rounded-lg shadow-md text-center text-blue-700">
                Tidak ada kategori ditemukan.
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @foreach($categories as $category)
                    <div class="flex justify-between items-center border-b border-blue-200 py-4 px-6 hover:bg-blue-50 transition">
                        <div class="text-lg text-blue-800">{{ $category->name }}</div>
                        <div class="space-x-4">
                            <a href="{{ route('categories.edit', $category) }}" 
                               class="text-yellow-600 hover:underline font-medium">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" id="delete-form-{{ $category->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:underline font-medium delete-btn"
                                        data-id="{{ $category->id }}">
                                    Hapus
                                </button>
                            </form>
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
        console.log('Categories index scripts loaded - DOM fully loaded');

        const deleteButtons = document.querySelectorAll('.delete-btn');
        console.log('Delete buttons found:', deleteButtons);

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Delete button clicked for category ID:', this.dataset.id);
                const form = document.getElementById(`delete-form-${this.dataset.id}`);

                if (form) {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Kategori akan dihapus secara permanen!",
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