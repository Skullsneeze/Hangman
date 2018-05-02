@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Your games</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{ route('play') }}" class="btn btn-primary">
                        Start new game
                    </a>
                    <a href="{{ route('load') }}" class="btn btn-primary {{ $gameCount ? '' : ' disabled' }}">
                        Load a game
                        <span class="badge badge-light badge-pill">{{ $gameCount }}</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
