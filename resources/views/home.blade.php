@extends('layouts.app')

@section('content')

<h2>Accueil</h2>

<div>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
</div>

<section>
    <h3>Vos groupes</h3>
    @include('inc.user_groups')
</section>

@endsection
