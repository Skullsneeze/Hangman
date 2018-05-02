<!doctype html>
@extends('layouts.app')
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Hangman</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        span.light {
            color: #3c3c3c;
            font-weight: 200;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
        @auth
            <a href="{{ url('/home') }}">Home</a>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            Hangman
        </div>
        <div>
        @auth
            <a href="{{ route('play') }}" class="btn btn-primary">
                Start new game
            </a>
            <a href="{{ route('load') }}" class="btn btn-primary">
                Load a game
            </a>
        @else
            <p>
                To play a game, please <a href="{{ route('register') }}">create an account</a>
                <br>
                <span class="light">Or if you already have an account, <a href="{{ route('login') }}">login!</a></span>
            </p>
        @endauth
        </div>
    </div>
</div>
</body>
</html>
