<?php

namespace App\Http\Controllers\Admin;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminGameController extends Controller
{
    public function index()
    {
        $games = Game::with('category')->get();
        return view('admin.games.index', compact('games'));
    }
}
