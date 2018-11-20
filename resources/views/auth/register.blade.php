@extends('layouts.app')

@section('content')

    <h2>Inscription</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="">
            <label for="name" class="">Pseudo</label>

            <input id="name" type="text" class="{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                   value="{{ old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>

        <div class="">
            <label for="email" class="">Courriel</label>

            <input id="email" type="email" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                   value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="">
            <label for="password" class="">Mot de passe</label>

            <input id="password" type="password" class="{{ $errors->has('password') ? ' is-invalid' : '' }}"
                   name="password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif

        </div>

        <div class="">
            <label for="password-confirm" class="">Confirmez</label>
            <input id="password-confirm" type="password" class="" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Inscription
        </button>
    </form>

@endsection
