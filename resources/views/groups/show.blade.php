@extends('layouts.app')

@section('title', 'Groupe')

@section('js')
    <script>
        var deleteUrl = "{{ @route('message_delete')  }}";
        var pingUrl = "{{ @route('participant_ping')  }}";
        var loadUserConversationUrl = "{{ @route('participant_ping')  }}";
        var groupId = "{{ $group->id  }}";
    </script>
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
    <div id="chat-app">
        <div class="sidebar">
            @if ($group->is_one_on_one)
                <h2>Conversation avec {{$group->otherUser->name}}</h2>
            @else
                <h2>Groupe {{$group->name}}</h2>
                <p class="small">Chef : {{$group->creator->name}}</p>
                <p class="small">Créé le {{ \Carbon\Carbon::parse($group->created_at)->format('d/m/Y')}}</p>
                <a href="{{route('participant_show_invite', ['groupId' => $group->id])}}">Invitez des amis !</a>
            @endif

            <h3>Utilisateurs</h3>
            @include('inc.group_users')

            <h2>Mes groupes</h2>
            @include('inc.user_groups')
        </div>

        <div class="chat">
            @if ($group->is_one_on_one)
                <h3 class="chat-title">Conversation avec {{$group->otherUser->name}}</h3>
            @else
            <h3 class="chat-title">Groupe {{$group->name}} : général</h3>
            @endif
            <div id="messages-list"></div>

            <form method="post" id="message-form"
                  action="{{route('message_create', ['groupId' => $group->id])}}">
                @csrf
                <input type="text" id="message-input" name="message" placeholder="Coucou!">
                <button id="message-btn">OK</button>
            </form>

            <a id="refresh-btn" href="{{route('message_get_since', ['groupId' => $group->id])}}">Rafraîchir</a>
        </div>
    </div>
@endsection