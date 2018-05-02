@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Please select which game you would like to load') }}
                </div>

                <div class="card-body">
                    <div class="list-group">
                        @foreach($games as $game)
                        <a href="{{ route('play', ['game' => $game->id]) }}"
                           class="list-group-item list-group-item-action {{ $game->getStatusClass() }}">
                            Progress: {{ $game->getMaskedSolution() }}
                        </a>
                        @endforeach
                    </div>

                    <br>

                    <a href="{{ route('play') }}" class="btn btn-primary">
                        Or start a new game
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
