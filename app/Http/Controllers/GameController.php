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

       // Paginate dengan 9 item per halaman
        $games = $query->with(['user', 'category'])->paginate(9);

        // Kembalikan view dengan games dan categories
        return view('user.games.index', compact('games', 'categories'));
    }

    // Form tambah game baru
    public function create()
    {
        $categories = Category::all(); // Perbaiki, hapus \App\Models
        return view('user.games.create', compact('categories'));
    }

    // Simpan game baru
    public function store(Request $request)
    {
        try {
            Log::info('Store game raw input', [
                'input' => $request->all(),
                'files' => $request->file(),
                'user_id' => Auth::id(),
            ]);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'file' => 'required|mimes:zip,html|max:51200', // max 50MB
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
                'price' => 'required|integer|min:0',
            ]);

            // Simpan file game
            $file = $request->file('file');
            $fileName = 'game_' . time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('games', $fileName, 'public');
            Log::info('Game file uploaded', ['path' => $filePath]);

            // Simpan gambar (jika ada)
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageFileName = 'game_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('images', $imageFileName, 'public');
                Log::info('Image uploaded', ['path' => $imagePath]);
            }

            // Simpan data ke database
            $game = Game::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'game_file' => $filePath,
                'image' => $imagePath,
                'price' => $request->price,
            ]);
            Log::info('Game record created', ['id' => $game->id, 'title' => $game->title]);

            return redirect()->route('user.games.index')->with('success', 'Game berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error creating game', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('user.games.create')->with('error', 'Gagal menambahkan game: ' . $e->getMessage());
        }
    }

    // Form edit game milik user
    public function edit($id)
    {
        $game = Game::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $categories = Category::all(); // Perbaiki, hapus \App\Models
        return view('user.games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, $id)
    {
        try {
            $game = Game::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string', // Ubah ke nullable, konsisten dengan store
                'category_id' => 'required|exists:categories,id',
                'file' => 'nullable|mimes:zip,html|max:51200', // max 50MB
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
                'price' => 'required|integer|min:0', // Ubah ke integer, konsisten dengan store
            ]);

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'price' => $request->price,
            ];

            // Update file game jika ada
            if ($request->hasFile('file')) {
                if ($game->game_file && Storage::disk('public')->exists($game->game_file)) {
                    Log::info('Deleting old game file', ['path' => $game->game_file]);
                    Storage::disk('public')->delete($game->game_file);
                }
                $file = $request->file('file');
                $fileName = 'game_' . time() . '_' . $file->getClientOriginalName();
                $data['game_file'] = $file->storeAs('games', $fileName, 'public');
                Log::info('New game file uploaded', ['path' => $data['game_file']]);
            }

            // Update gambar jika ada
            if ($request->hasFile('image')) {
                if ($game->image && Storage::disk('public')->exists($game->image)) {
                    Log::info('Deleting old image', ['path' => $game->image]);
                    Storage::disk('public')->delete($game->image);
                }
                $image = $request->file('image');
                $imageFileName = 'game_' . time() . '.' . $image->getClientOriginalExtension();
                $data['image'] = $image->storeAs('images', $imageFileName, 'public');
                Log::info('New image uploaded', ['path' => $data['image']]);
            }

            $game->update($data);
            Log::info('Game updated successfully', ['id' => $game->id, 'title' => $game->title]);

            return redirect()->route('user.games.index')->with('success', 'Game berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating game', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('user.games.edit', $id)->with('error', 'Gagal memperbarui game: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $game = Game::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            Log::info('Attempting to delete game', ['id' => $game->id, 'title' => $game->title, 'user_id' => Auth::id()]);

            // Hapus folder ekstraksi
            $folderPath = "games/{$game->id}";
            if (Storage::disk('public')->exists($folderPath)) {
                Log::info('Deleting folder', ['path' => $folderPath]);
                Storage::disk('public')->deleteDirectory($folderPath);
            } else {
                Log::info('Folder not found', ['path' => $folderPath]);
            }

            // Hapus file game
            if ($game->game_file && Storage::disk('public')->exists($game->game_file)) {
                Log::info('Deleting file', ['path' => $game->game_file]);
                Storage::disk('public')->delete($game->game_file);
            } else {
                Log::info('File not found', ['path' => $game->game_file]);
            }

            // Hapus gambar
            if ($game->image && Storage::disk('public')->exists($game->image)) {
                Log::info('Deleting image', ['path' => $game->image]);
                Storage::disk('public')->delete($game->image);
            } else {
                Log::info('Image not found', ['path' => $game->image]);
            }

            $game->delete();
            Log::info('Game deleted successfully', ['id' => $game->id]);

            return redirect()->route('user.games.index')->with('success', 'Game berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting game', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('user.games.index')->with('error', 'Gagal menghapus game: ' . $e->getMessage());
        }
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
        Log::info('HTML File Path: ' . $htmlFilePath);

        // Kirim data game dan path file HTML ke view
        return view('user.games.play', [
            'game' => $game,
            'htmlFilePath' => Storage::url($htmlFilePath), // URL ke file .html
        ]);
    }
}
