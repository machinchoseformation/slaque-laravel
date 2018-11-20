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
    <h3>Les groupes que vous avez créés</h3>
    @foreach (Auth::user()->groupsCreated as $group)
        <article>
            <a href="{{route('group_show', ['id' => 1])}}">{{$group->name}}</a>
        </article>
    @endforeach

    <h3>Les groupes auxquels vous participez</h3>
    @foreach (Auth::user()->groups as $group)
        <article>
            <a href="{{route('group_show', ['id' => 1])}}">{{$group->name}}</a>
        </article>
    @endforeach
</section>

@endsection
