@extends('layouts.app')

@section('content')

    <h2>Connexion</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="">
            <label for="email" class="">Courriel</label>

            <input id="email" type="text" class="{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                   value="{{ old('email') }}" required autofocus>

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
            <div class="">
                <input class="form-check-input" type="checkbox" name="remember"
                       id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    Se souvenir de moi
                </label>

            </div>
        </div>

        <div class=" mb-0">
            <button type="submit" class="btn btn-primary">
                Connexion
            </button>

            <a class="btn btn-link" href="{{ route('password.request') }}">
                Mot de passe oublié ?
            </a>
        </div>
    </form>

@endsection
