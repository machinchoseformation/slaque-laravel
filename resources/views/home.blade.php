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
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aliquid amet architecto aspernatur atque eaque eius illo itaque iusto maiores minus necessitatibus neque nesciunt nobis omnis, quae, reiciendis reprehenderit tempora!</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam aliquid amet architecto aspernatur atque eaque eius illo itaque iusto maiores minus necessitatibus neque nesciunt nobis omnis, quae, reiciendis reprehenderit tempora!</p>
    <h3>Vos groupes</h3>
    @include('inc.user_groups')
</section>

@endsection
