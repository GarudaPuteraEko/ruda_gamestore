@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
    <!-- Pesan Sukses atau Error -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Daftar Game</h1>
        <div class="space-x-2">
            <a href="{{ route('categories.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Kategori
            </a>
            <a href="{{ route('admin.transactions.index') }}" class="inline-block px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                Halaman Approval
            </a>
            <a href="{{ route('admin.games.create') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                + Tambah Game
            </a>
        </div>
    </div>
    
    <!-- Form Pencarian -->
    <div class="mb-6">
        <form action="{{ route('admin.games.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Game</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Masukkan judul game..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex-1">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
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
                <button type="submit" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Cari</button>
            </div>
        </form>
    </div>
    
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gambar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Game</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembuat</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($games as $game)
                    <tr>
                        <td class="px-6 py-4">
                            @if ($game->image)
                                <img src="{{ Storage::url($game->image) }}" alt="{{ $game->title }}" class="h-16 w-16 object-cover rounded">
                            @else
                                <span class="text-gray-500 text-sm">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-800">{{ $game->title }}</td>
                        <td class="px-6 py-4 text-gray-700 text-sm max-w-xs whitespace-normal">
                            {{ Str::limit($game->description, 100, '...') }}
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $game->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($game->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $game->user ? $game->user->name : 'Admin' }}
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            @if($game->user && $game->user->role === 'admin')
                                <a href="{{ route('admin.games.edit', $game->id) }}" class="inline-block bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700 transition">Edit</a>
                            @endif
                            <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus game ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada game ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection