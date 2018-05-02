<?php

namespace App\Http\Controllers;

use App\Game;
use App\Http\Resources\GameResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GameController extends Controller
{

    /**
     * GameController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Show users games
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return GameResource::collection(Game::all()->where('user_id', auth()->user()->id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return GameResource
     */
    public function store(Request $request)
    {
        /** @var Game $game */
        $game = Game::create([
            'user_id' => $request->user()->id,
            'status' => Game::STATUS_ACTIVE,
            'solution' => '',
            'guessed_letters' => '',
        ]);

        $game->setSolution();

        return new GameResource($game);
    }

    /**
     * Display the specified resource.
     *
     * @param Game $game
     * @return GameResource
     */
    public function show(Game $game)
    {
        if (auth()->user()->id !== $game->user_id) {
            return response()->json(['error' => 'You are not authorized to view this game.'], 403);
        }
        return new GameResource($game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Game $game
     * @param string $char
     * @return GameResource
     */
    public function update(Request $request, Game $game, string $char)
    {
        // Make sure the user owns the game we're updating
        if ($request->user()->id !== $game->user_id) {
            return response()->json(['error' => 'You are not authorized to update this game.'], 403);
        }

        // Make sure the user owns the game we're updating
        if ($game->status !== Game::STATUS_ACTIVE) {
            return response()->json(['error' => 'This game has been finished'], 400);
        }

        // Make sure the given character is a letter
        if (!ctype_alpha($char)) {
            return response()->json(['error' => 'Invalid character supplied (Only a-z characters are allowed)'], 400);
        }

        // Make sure the given character is not already guessed
        $guessedLetters = \array_flip($game->getGuessedLetters());
        if (isset($guessedLetters[\strtoupper($char)])) {
            return response()->json(['error' => 'The provided character is already guessed for this game'],400);
        }

        if ($game->processUpdateRequest($char) === false) {
            return response()->json(['error' => 'We were unable to update the game. Please try again.'], 500);
        }

        return new GameResource($game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Game $game
     * @return Response
     */
    public function destroy(Game $game)
    {
        $game->delete();
        return response()->json(null, 204);
    }
}
