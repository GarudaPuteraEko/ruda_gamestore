<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('transactionItems.game')
        ->where('user_id', Auth::id())
        ->get();

        return view('transactions.index', compact('transactions'));
    }

    public function store(Request $request, $gameId)
    {
        $game = Game::findOrFail($gameId);

        // Tidak boleh beli game sendiri
        if ($game->user_id == Auth::id()) {
            return back()->with('error', 'Tidak bisa membeli game sendiri.');
        }

        // Cek apakah user sudah punya transaksi aktif dengan game ini
        $existing = TransactionItem::whereHas('transaction', function($q) {
            $q->where('user_id', Auth::id());
        })->where('game_id', $game->id)->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah membeli game ini.');
        }

        // Buat transaksi baru dengan status pending
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'status' => 'pending',
        ]);

        // Tambahkan game ke transaction_items
        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'game_id' => $game->id,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Pembelian berhasil dikirim, menunggu approval admin.');
    }


    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $request->validate([
            'status' => 'in:success,canceled'
        ]);

        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Transaksi diperbarui.');
    }

}
