@extends('layouts.app')

@section('title', 'Inviter dans le groupe')

@section('content')
    <div>
        <h2>Déjà dans le groupe {{$group->name}}</h2>
        @include('inc.group_users')
    </div>

    <h2>Invitez des copains !</h2>

    <form id="user-search-form" action="{{ route('user_search') }}">
        @csrf
        <input type="hidden" name="groupId" value="{{$group->id}}">
        <input type="search" id="user-search-input" name="username" placeholder="jean-charles, rose">
        <button>Rechercher</button>
    </form>

    <ul id="user-search-result"></ul>

    <a href="{{ route('group_show', ['id' => $group->id])  }}" title="Retour à la conversation">Retour à la conversation</a>
@endsection

@section('js')
    <script>var groupId = {{$group->id}};</script>
    <script src="{{asset('js/user_search.js')}}" defer></script>
@endsection