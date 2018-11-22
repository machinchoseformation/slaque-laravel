@extends('layouts.app')

@section('title', 'Inviter dans le groupe')

@section('content')
    <h2>Déjà dans le groupe {{$group->name}}</h2>
    <ul>
        @foreach($group->participants as $user)
            <li><a href="{{ route('participant_invite_user_to_group', ['groupId' => $group->id, 'userId' => $user->id])  }}">{{$user->name}}</a></li>
        @endforeach
    </ul>

    <h2>Invitez des copains !</h2>
    <ul>
    @foreach($usersNotInGroup as $user)
        <li><a href="{{ route('participant_invite_user_to_group', ['groupId' => $group->id, 'userId' => $user->id])  }}">{{$user->name}}</a></li>
    @endforeach
    </ul>

    <a href="{{ route('group_show', ['id' => $group->id])  }}" title="Retour à la conversation">Retour à la conversation</a>
@endsection