<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    /**
     * Status for the game
     */
    const STATUS_ACTIVE = 1;
    const STATUS_WON    = 2;
    const STATUS_HANGED = 3;

    /**
     * Max failed attempts to solve the game
     */
    const MAX_TRIES = 11;

    /**
     * Source file for solutions
     * @var string
     */
    private $solutionSource = 'words.json';

    /**
     * @inheritdoc
     */
    protected $fillable = ['user_id', 'status', 'solution', 'guessed_letters'];

    /**
     * Relation between a game and it's user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate and set a solution for the game
     *
     * @return string
     */
    public function getAttemptsLeft()
    {
        $guessedLetters = $this->getGuessedLetters();
        if (empty($guessedLetters)) {
            return (string) self::MAX_TRIES;
        }

        $wrongGuesses = 0;
        foreach ($guessedLetters as $guessedLetter) {
            if (!$this->isLetterInSolution($guessedLetter)) {
                $wrongGuesses++;
            }
        }

        return (string) self::MAX_TRIES - $wrongGuesses;
    }

    /**
     * Return a list of letters [letter => status]
     *
     * @return int[]
     */
    public function getLetters()
    {

        $availableLetters = \array_fill_keys(\range('A', 'Z'), 1);
        $guessedLetters = $this->getGuessedLetters();

        if (empty($guessedLetters)) {
            return $availableLetters;
        }

        // Disable letters that are already guessed
        foreach ($guessedLetters as $guessedLetter) {
            $guessedLetter = \strtoupper($guessedLetter);
            if (isset($availableLetters[$guessedLetter])) {
                $availableLetters[$guessedLetter] = 0;
            }
        }

        return $availableLetters;
    }

    /**
     * Generate and set a solution for the game
     */
    public function setSolution()
    {
        $words = \json_decode(\file_get_contents($this->solutionSource));
        $solution = (string) \array_random($words);
        $this->update(['solution' => $solution]);
    }

    /**
     * Process the update request.
     *
     * @param string $letter
     * @return bool
     */
    public function processUpdateRequest(string $letter)
    {
        $this->addGuessedLetter($letter);

        // Check if the given character exists in the solution
        if ($this->isLetterInSolution($letter)) {
            if ($this->isSolutionFound()) {
                $this->status = self::STATUS_WON;
            }
            return $this->save();
        }

        // Check if there are any attempts left
        if ($this->getAttemptsLeft() <= 0) {
            $this->status = self::STATUS_HANGED;
        }

        return $this->save();
    }

    /**
     * Add a character to the guessed letters
     *
     * @param string $letter
     * @return void
     */
    private function addGuessedLetter(string $letter)
    {
        $guessedLetters = $this->getGuessedLetters();
        $guessedLetters[] = \strtoupper($letter);

        $this->guessed_letters = \implode(', ', $guessedLetters);
    }

    /**
     * Check if the given letter exists in the solution
     *
     * @param string $letter
     * @return bool
     */
    public function isLetterInSolution($letter)
    {
        return strpos($this->solution, \strtolower($letter)) !== false;
    }

    /**
     * Check if the solution to the game has been found
     *
     * @return bool
     */
    public function isGameOver()
    {
        return $this->status === self::STATUS_HANGED;
    }

    /**
     * Check if the solution to the game has been found
     *
     * @return bool
     */
    public function isSolutionFound()
    {
        // Check if status is already set
        if ($this->status === self::STATUS_WON) {
            return true;
        }

        // Get the letters needed for the solution
        $solutionLetters = \array_flip(\str_split($this->solution));
        $solutionLetters = \array_change_key_case($solutionLetters, CASE_UPPER);

        // Get the letters that have been guessed
        $guessedLetters = \array_flip($this->getGuessedLetters());

        // Count how many matches we have
        $matchedLetters = \array_intersect_key($solutionLetters, $guessedLetters);

        return \count($matchedLetters) === \count($solutionLetters);
    }

    /**
     * Get the letters that have been guessed so far
     *
     * @return string[]
     */
    public function getGuessedLetters()
    {
        if (!$this->guessed_letters) {
            return [];
        }

        return $guessedLetters = \explode(', ', $this->guessed_letters);
    }

    /**
     * Get the solution with only the guessed letters visible
     *
     * @return string
     */
    public function getMaskedSolution()
    {
        $guessedLetters = \array_flip($this->getGuessedLetters());
        $solutionLetters = \str_split($this->solution);
        $maskedOutput = '';

        // Generate a string showing only matched letters
        foreach ($solutionLetters as $letter) {
            if (!isset($guessedLetters[\strtoupper($letter)])) {
                $maskedOutput .= ' . ';
                continue;
            }

            $maskedOutput .= $letter;
        }

        return $maskedOutput;
    }

    /**
     * Get a class based on the status of the game
     *
     * @return string
     */
    public function getStatusClass()
    {
        switch ($this->status) {
            case self::STATUS_WON:
                return 'list-group-item-success';
                break;
            case self::STATUS_HANGED:
                return 'list-group-item-danger';
                break;
            case self::STATUS_ACTIVE:
            default:
                return '';
                break;
        }
    }
}
