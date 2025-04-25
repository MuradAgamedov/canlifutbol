<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function live()
    {
        return view('pages.games.lives');
    }
}
