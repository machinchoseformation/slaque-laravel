@extends('layouts.app')

@section('content')
    <h2>Réinitialiser le mot de passe</h2>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="">
            <label for="email" class="">Courriel</label>

            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   name="email" value="{{ $email ?? old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="">
            <label for="password" class="">Mot de passe</label>

            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                   name="password" required>

            @if ($errors->has('password'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>

        <div class="">
            <label for="password-confirm" class="">Confirmez</label>

            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Réinitialiser
        </button>
    </form>

@endsection
