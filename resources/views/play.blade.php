@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <h2 class="title">
                {{ $game->getAttemptsLeft() }} Attempts left
            </h2>
            <div>
                <h4>{{ \ucfirst($game->getMaskedSolution()) }}</h4>
            </div>
        </div>
        <div class="col-md-6">
            @if (session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
            @endif
            <div class="hangman-drawing">
                <div class="attempt-11 {{ ($game->getAttemptsLeft() - 1) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-10 {{ ($game->getAttemptsLeft() - 2) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-9 {{ ($game->getAttemptsLeft() - 3) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-8 {{ ($game->getAttemptsLeft() - 4) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-7 {{ ($game->getAttemptsLeft() - 5) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-6 {{ ($game->getAttemptsLeft() - 6) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-5 {{ ($game->getAttemptsLeft() - 7) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-4 {{ ($game->getAttemptsLeft() - 8) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-3 {{ ($game->getAttemptsLeft() - 9) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-2 {{ ($game->getAttemptsLeft() - 10) < 0 ? '' : 'd-none' }}"></div>
                <div class="attempt-1 {{ ($game->getAttemptsLeft() - 11) < 0 ? '' : 'd-none' }}"></div>
            </div>
            <hr>
            <div class="letter-picker">
            @if ($game->status === $game::STATUS_ACTIVE)
                @foreach ($game->getLetters() as $letter => $active)
                <a class="btn btn-outline-dark{{ $active ? '' : ' disabled'}}" href="{{ route('guess', ['game' => $game->id, 'char' => $letter]) }}">
                    {{ $letter }}
                </a>
                @endforeach
            @endif
            @if ($game->status === $game::STATUS_WON)
                <h3>Awesome! You found the solution!</h3>
                <a href="{{ route('play') }}" class="btn btn-primary">
                    Want to try again?
                </a>
            @endif
            @if ($game->status === $game::STATUS_HANGED)
                <h3>Bummer! Looks like our buddy didn't make it</h3>
                <p class="font-weight-light font-italic">The solution was: "{{ $game->solution }}"</p>
                <a href="{{ route('play') }}" class="btn btn-primary">
                    Want to try again?
                </a>
            @endif
            </div>
        </div>
        <div class="col-md-3">
            <h2>Used letters</h2>
            <p class="used-letters">{{ $game->guessed_letters }}</p>
        </div>
    </div>
</div>
@endsection
