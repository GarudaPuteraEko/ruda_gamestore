<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    // Tampilkan semua transaksi
    public function index()
    {
        $transactions = Transaction::with('user', 'items.game')->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    // Approve transaksi
    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'success']);
        return back()->with('success', 'Transaksi berhasil diapprove.');
    }

    // Cancel transaksi
    public function cancel($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->update(['status' => 'cancel']);
        return back()->with('success', 'Transaksi dibatalkan.');
    }
}
