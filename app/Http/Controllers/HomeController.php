<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Resources\GameResource;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = GameResource::collection(Game::all()->where('user_id', auth()->user()->id));
        return view('home', ['gameCount' => $games->count()]);
    }
}
