@extends('layouts.app')

@section('title', 'Mainkan: ' . $game->title)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6">Mainkan: {{ $game->title }}</h1>

    <div class="bg-white p-6 rounded-lg shadow-lg">
        <div class="mb-4">
            <p class="text-lg text-gray-700 mb-2"><span class="font-semibold">Deskripsi:</span> {{ $game->description }}</p>
            <p class="text-lg text-gray-700"><span class="font-semibold">Kategori:</span> {{ $game->category->name }}</p>
        </div>

        <div class="relative">
            @if($htmlFilePath)
                <iframe id="gameIframe" src="{{ $htmlFilePath }}" class="w-full h-[600px] md:h-[700px] border-2 border-gray-300 rounded-lg shadow-md" title="Game: {{ $game->title }}"></iframe>
                <div class="absolute top-4 right-4 flex space-x-2">
                    <button onclick="toggleFullscreen()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                        </svg>
                        Layar Penuh
                    </button>
                </div>
            @else
                <p class="text-red-500 text-lg font-semibold">Gagal memuat game.</p>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ route('user.games.index') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200">← Kembali ke Daftar Game</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleFullscreen() {
    const iframe = document.getElementById('gameIframe');
    if (!document.fullscreenElement) {
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.mozRequestFullScreen) { // Firefox
            iframe.mozRequestFullScreen();
        } else if (iframe.webkitRequestFullscreen) { // Chrome, Safari, Opera
            iframe.webkitRequestFullscreen();
        } else if (iframe.msRequestFullscreen) { // IE/Edge
            iframe.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari, Opera
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
        }
    }
}
</script>
@endsection