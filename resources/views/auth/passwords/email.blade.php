@extends('layouts.app')

@section('content')

<h2>Réinitialiser le mot de passe</h2>

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="">
    <label for="email" class="">Courriel</label>

        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

            <button type="submit" class="btn btn-primary">
                Envoyer le lien
            </button>

</form>

@endsection
