<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
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
        $game = Game::findOrFail($id);

        // Hapus folder ekstraksi (misalnya: games/123)
        $folderPath = "games/{$game->id}";
        if (Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->deleteDirectory($folderPath);
        }

        // Hapus file zip
        if ($game->game_file && Storage::disk('public')->exists($game->game_file)) {
            Storage::disk('public')->delete($game->game_file);
        }

        // Hapus record game dari database
        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus.');
    }
}
