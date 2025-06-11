<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
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

    public function create()
    {
        $categories = Category::all();
        return view('admin.games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            // Log input mentah
            Log::info('Admin store game raw input', [
                'input' => $request->all(),
                'files' => $request->file(),
                'admin_id' => Auth::id(),
            ]);

            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'file' => 'required|mimes:zip,html|max:51200', // max 50MB
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
                'price' => 'required|integer|min:0',
            ]);

            // Log data tervalidasi
            Log::info('Validated data', ['data' => $validated]);

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
                'user_id' => Auth::id(), // Gunakan ID admin jika user_id not nullable
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->description,
                'game_file' => $filePath,
                'image' => $imagePath,
                'price' => $request->price,
            ]);
            Log::info('Game record created', ['id' => $game->id, 'title' => $game->title]);

            return redirect()->route('admin.games.index')->with('success', 'Game berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in admin store game', [
                'errors' => $e->errors(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating game by admin', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return redirect()->back()->with('error', 'Gagal menambahkan game: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $game = Game::with('user')->findOrFail($id);
            if (!$game->user || $game->user->role !== 'admin') {
                Log::warning('Admin attempted to edit non-admin game', ['id' => $id, 'user_id' => $game->user_id]);
                return redirect()->route('admin.games.index')->with('error', 'Anda hanya dapat mengedit game milik admin.');
            }
            $categories = Category::all();
            return view('admin.games.edit', compact('game', 'categories'));
        } catch (\Exception $e) {
            Log::error('Error accessing edit game page', ['id' => $id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.games.index')->with('error', 'Gagal mengakses halaman edit: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $game = Game::with('user')->findOrFail($id);
            if (!$game->user || $game->user->role !== 'admin') {
                Log::warning('Admin attempted to update non-admin game', ['id' => $id, 'user_id' => $game->user_id]);
                return redirect()->route('admin.games.index')->with('error', 'Anda hanya dapat mengedit game milik admin.');
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'file' => 'nullable|mimes:zip,html|max:51200',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'price' => 'required|integer|min:0',
            ]);

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'price' => $request->price,
            ];

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

            return redirect()->route('admin.games.index')->with('success', 'Game berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating game by admin', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.games.edit', $id)->with('error', 'Gagal memperbarui game: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $game = Game::findOrFail($id);
            Log::info('Attempting to delete game by admin', ['id' => $game->id, 'title' => $game->title]);

            // Hapus folder ekstraksi
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

            $game->delete();
            Log::info('Game deleted successfully', ['id' => $game->id]);

            return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting game by admin', ['id' => $id, 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('admin.games.index')->with('error', 'Gagal menghapus game: ' . $e->getMessage());
        }
    }
}
