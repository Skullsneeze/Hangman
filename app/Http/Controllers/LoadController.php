<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Resources\GameResource;

use Illuminate\Http\Response;

class LoadController extends Controller
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
     * @return Response
     */
    public function index()
    {
        $games = GameResource::collection(Game::all()->where('user_id', auth()->user()->id));
        return view('load', ['games' => $games]);
    }
}
