<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'game_id',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

}
