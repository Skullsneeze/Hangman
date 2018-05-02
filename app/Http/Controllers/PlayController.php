<?php

namespace App\Http\Controllers;

use App\Game;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PlayController extends Controller
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
     * @param Game|null $game
     * @return Response
     */
    public function index(Game $game = null)
    {
        // Create a new game if needed
        if (null === $game) {
            $game = Game::create([
                'user_id' => \Auth::user()->id,
                'status' => Game::STATUS_ACTIVE,
                'solution' => '',
                'guessed_letters' => '',
            ]);
            $game->setSolution();
        }

        return view('play', ['game' => $game]);
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @param Game $game
     * @param string $char
     * @return Response
     */
    public function update(Request $request, Game $game, string $char)
    {
        // Make sure the user owns the game we're updating
        if ($request->user()->id !== $game->user_id) {
            return redirect('/play/' . $game->id)->with('status', 'Unable to save the game. It belongs to aother user.');
        }

        // Check if the game is already won
        if ($game->status === game::STATUS_WON) {
            return redirect('/play/' . $game->id)->with('status', 'Looks like you already won this game! Congratulations!');
        }

        if ($game->status === game::STATUS_HANGED) {
            return redirect('/play/' . $game->id)->with('status', 'Looks like this game was already lost! Better luck next time!');
        }

        // Make sure the given character is a letter
        if (!ctype_alpha($char)) {
            return redirect('/play/' . $game->id)->with('status', 'Something went wrong while updating your game!');
        }

        // Make sure the given character is not already guessed
        $guessedLetters = \array_flip($game->getGuessedLetters());
        if (isset($guessedLetters[\strtoupper($char)])) {
            return redirect('/play/' . $game->id)->with('status', 'Something went wrong while updating your game!');
        }

        if ($game->processUpdateRequest($char) === false) {
            return redirect('/play/' . $game->id)->with('status', 'Something went wrong while updating your game!');
        }

        $status = 'There is no "'. $char .'" in the word.';
        if ($game->isLetterInSolution($char)) {
            $status = 'Right on! "'. $char .'" was in the word';
        }

        return redirect('/play/' . $game->id)->with('status', $status);
    }
}
