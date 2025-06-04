<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Game;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Tampilkan isi keranjang user
    public function index()
    {
        $carts = Cart::with('game')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('carts'));
    }

    // Tambah game ke keranjang user
    public function add(Game $game)
    {
        $exists = Cart::where('user_id', Auth::id())
            ->where('game_id', $game->id)
            ->first();

        if (!$exists) {
            Cart::create([
                'user_id' => Auth::id(),
                'game_id' => $game->id,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Game berhasil ditambahkan ke keranjang.');
    }

    // Hapus game dari keranjang user
    public function remove(Game $game)
    {
        Cart::where('user_id', Auth::id())
            ->where('game_id', $game->id)
            ->delete();

        return redirect()->route('cart.index')->with('success', 'Game berhasil dihapus dari keranjang.');
    }

    // Checkout: buat transaksi untuk semua game di keranjang, lalu kosongkan keranjang
    public function checkout()
    {
        $userId = Auth::id();

        $carts = Cart::with('game')
            ->where('user_id', $userId)
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        DB::transaction(function () use ($carts, $userId) {
            foreach ($carts as $cart) {
                Transaction::create([
                    'user_id' => $userId,
                    'game_id' => $cart->game->id,
                    'status' => 'pending', // bisa diupdate nanti admin
                ]);
            }
            // Kosongkan keranjang user
            Cart::where('user_id', $userId)->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Checkout berhasil. Transaksi sedang diproses.');
    }
}
