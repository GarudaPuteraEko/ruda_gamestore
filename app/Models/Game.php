<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'game_file',
        'price',
    ];

    public function user() {
    return $this->belongsTo(User::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function carts() {
        return $this->hasMany(Cart::class);
    }
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

}
