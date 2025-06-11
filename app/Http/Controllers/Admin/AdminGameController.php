<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminGameController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori untuk dropdown
        $categories = Category::all();

        // Query dasar untuk games
        $query = Game::query();

        // Filter berdasarkan pencarian judul
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Ambil data games
        $games = $query->get();
        return view('admin.games.index', compact('games', 'categories'));
    }

    public function destroy($id)
    {
        try {
            // Cari game berdasarkan ID
            $game = Game::findOrFail($id);
            Log::info('Attempting to delete game by admin', ['id' => $game->id, 'title' => $game->title]);

            // Hapus folder ekstraksi (games/<game_id>)
            $folderPath = "games/{$game->id}";
            if (Storage::disk('public')->exists($folderPath)) {
                Log::info('Deleting folder', ['path' => $folderPath]);
                Storage::disk('public')->deleteDirectory($folderPath);
            } else {
                Log::info('Folder not found', ['path' => $folderPath]);
            }

            // Hapus file zip
            if ($game->game_file && Storage::disk('public')->exists($game->game_file)) {
                Log::info('Deleting game file', ['path' => $game->game_file]);
                Storage::disk('public')->delete($game->game_file);
            } else {
                Log::info('Game file not found', ['path' => $game->game_file]);
            }

            // Hapus file gambar
            if ($game->image && Storage::disk('public')->exists($game->image)) {
                Log::info('Deleting image', ['path' => $game->image]);
                Storage::disk('public')->delete($game->image);
            } else {
                Log::info('Image not found', ['path' => $game->image]);
            }

            // Hapus record game dari database
            $game->delete();
            Log::info('Game deleted successfully', ['id' => $game->id]);

            return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting game by admin', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.games.index')->with('error', 'Gagal menghapus game: ' . $e->getMessage());
        }
    }
}
