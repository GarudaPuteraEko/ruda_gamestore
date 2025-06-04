<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'status'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function game() {
        return $this->belongsTo(Game::class);
    }
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

}
