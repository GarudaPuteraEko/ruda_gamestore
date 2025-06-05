<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('game')
            ->where('user_id', Auth::id())
            ->get();

        return view('transactions.index', compact('transactions'));
    }

    public function store(Request $request, $gameId)
    {
        $game = Game::findOrFail($gameId);

        if ($game->user_id == Auth::id()) {
            return back()->with('error', 'Tidak bisa membeli game sendiri.');
        }

        // Cek apakah user sudah membeli game ini sebelumnya
        $existing = Transaction::where('user_id', Auth::id())
            ->where('game_id', $game->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'Kamu sudah membeli game ini.');
        }

        // Buat transaksi dengan menyertakan game_id
        Transaction::create([
            'user_id' => Auth::id(),
            'game_id' => $gameId, // <== ini yang sebelumnya tidak ada
            'status' => 'pending',
        ]);

        return redirect()->route('transactions.index')->with('success', 'Pembelian berhasil dikirim, menunggu approval admin.');
    }


    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'status' => 'in:approved,canceled',
        ]);

        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Transaksi diperbarui.');
    }

    public function cancel($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())->findOrFail($id);

        if ($transaction->status === 'pending') {
            $transaction->update(['status' => 'canceled']);
            return back()->with('success', 'Transaksi berhasil dibatalkan.');
        }

        return back()->with('error', 'Transaksi tidak dapat dibatalkan.');
    }

}
