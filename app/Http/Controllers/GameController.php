<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameController extends Controller
{
    // Tampilkan daftar game milik user (halaman index user)
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

        // Ambil data games dari query yang sudah difilter
        $games = $query->get();

        // Kembalikan view dengan games dan categories
        return view('user.games.index', compact('games', 'categories'));
    }

    // Form tambah game baru
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('user.games.create', compact('categories'));
    }

    // Simpan game baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'file' => 'required|mimes:zip,html|max:51200', // max 50MB
            'price' => 'required|integer|min:0',
        ]);

        // Simpan file ke storage/public/games
        $file = $request->file('file');
        $fileName = 'game_' . time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('games', $fileName, 'public');

        // Simpan data ke database
        Game::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'game_file' => $filePath,
            'price' => $request->price,
        ]);

        return redirect()->route('user.games.index')->with('success', 'Game berhasil ditambahkan!');
    }

    // Form edit game milik user
    public function edit($id)
    {
        $game = Game::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $categories = \App\Models\Category::all();
        return view('user.games.edit', compact('game', 'categories'));
    }

    // Update game milik user
    public function update(Request $request, $id)
    {
        $game = Game::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|file|mimes:zip|max:10240', // Maksimal 10MB
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ];

        // Jika user upload file baru
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($game->game_file && Storage::exists($game->game_file)) {
                Storage::delete($game->game_file);
            }

            // Simpan file baru
            $data['game_file'] = $request->file('file')->store('games');
        }

        $game->update($data);

        return redirect()->route('user.games.index')->with('success', 'Game berhasil diperbarui.');
    }

    // Hapus game milik user
    public function destroy($id)
    {
        try {
            // Cari game milik user yang login
            $game = Game::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            // Log untuk debug
            Log::info('Attempting to delete game', ['id' => $game->id, 'title' => $game->title, 'user_id' => Auth::id()]);

            // Hapus folder ekstraksi (games/<game_id>)
            $folderPath = "games/{$game->id}";
            if (Storage::disk('public')->exists($folderPath)) {
                Log::info('Deleting folder', ['path' => $folderPath]);
                Storage::disk('public')->deleteDirectory($folderPath);
            } else {
                Log::info('Folder not found', ['path' => $folderPath]);
            }

            // Hapus file zip
            $zipPath = $game->game_file ?? "games/game_{$game->id}.zip";
            if (Storage::disk('public')->exists($zipPath)) {
                Log::info('Deleting file', ['path' => $zipPath]);
                Storage::disk('public')->delete($zipPath);
            } else {
                Log::info('File not found', ['path' => $zipPath]);
            }

            // Hapus record game dari database
            $game->delete();
            Log::info('Game deleted successfully', ['id' => $game->id]);

            return redirect()->route('user.games.index')->with('success', 'Game berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting game', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('user.games.index')->with('error', 'Gagal menghapus game: ' . $e->getMessage());
        }
    }

    // Helper fungsi hapus folder rekursif
    protected function deleteFolder($folder)
    {
        foreach(glob($folder . '/*') as $file) {
            if (is_dir($file)) {
                $this->deleteFolder($file);
            } else {
                unlink($file);
            }
        }
        rmdir($folder);
    }

    // Halaman main game
    public function play($id)
    {
        $game = Game::findOrFail($id);

        // Admin boleh main game siapa saja
        if (Auth::user()->role !== 'admin') {
            // Cek apakah user sudah beli game ini dengan status success
            $transaction = Transaction::where('user_id', Auth::id())
                ->where('game_id', $id)
                ->where('status', 'success')
                ->first();

            if (!$transaction) {
                return redirect()->route('user.games.index')->with('error', 'Kamu belum membeli game ini.');
            }
        }

        // Path folder untuk ekstraksi (misalnya: storage/app/public/games/123)
        $extractPath = "games/{$game->id}";
        $htmlFilePath = "{$extractPath}/index.html";

        // Cek apakah file .html sudah ada (sudah diekstrak sebelumnya)
        if (!Storage::disk('public')->exists($htmlFilePath)) {
            // Pastikan file .zip ada
            if (!Storage::disk('public')->exists($game->game_file)) {
                return redirect()->route('user.games.index')->with('error', 'File game tidak ditemukan.');
            }

            // Ekstrak file .zip
            $zip = new ZipArchive;
            $zipPath = storage_path('app/public/' . $game->game_file);
            $extractTo = storage_path('app/public/' . $extractPath);

            if ($zip->open($zipPath) === true) {
                $zip->extractTo($extractTo);
                $zip->close();

                // Verifikasi apakah index.html ada setelah ekstraksi
                if (!Storage::disk('public')->exists($htmlFilePath)) {
                    // Cari file .html lain di folder ekstraksi (termasuk subfolder)
                    $files = Storage::disk('public')->allFiles($extractPath);
                    $htmlFile = null;
                    foreach ($files as $file) {
                        if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
                            $htmlFile = $file;
                            break;
                        }
                    }

                    if (!$htmlFile) {
                        // Hapus folder ekstraksi jika gagal
                        Storage::disk('public')->deleteDirectory($extractPath);
                        return redirect()->route('user.games.index')->with('error', 'File HTML tidak ditemukan di dalam .zip.');
                    }

                    $htmlFilePath = $htmlFile;
                }
            } else {
                return redirect()->route('user.games.index')->with('error', 'Gagal mengekstrak file .zip.');
            }
        }

        // Log untuk debugging
        \Log::info('HTML File Path: ' . $htmlFilePath);

        // Kirim data game dan path file HTML ke view
        return view('user.games.play', [
            'game' => $game,
            'htmlFilePath' => Storage::url($htmlFilePath), // URL ke file .html
        ]);
    }
}
