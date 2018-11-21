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
            <h2>Groupe {{$group->name}}</h2>
            <p class="small">Chef : {{$group->creator->name}}</p>
            <p class="small">Créé le {{ \Carbon\Carbon::parse($group->created_at)->format('d/m/Y')}}</p>
            <h3>Utilisateurs</h3>
            <ul class="users-list">
                <li class="user-you online" data-user-id="{{ Auth::user()->id }}"><span class="connection-status"></span>{{ Auth::user()->name }}</li>
                @foreach($group->participants as $user)
                    @if (Auth::user()->id != $user->id)
                        <li class="user-btn" data-user-id="{{ $user->id }}"><span class="connection-status"></span><a href="{{ route('group_one_on_one_create', ['other_user_id' => $user->id])  }}">{{$user->name}}</a></li>
                    @endif
                @endforeach
            </ul>
            <a href="{{route('participant_show_invite', ['groupId' => $group->id])}}">Invitez des amis !</a>
        </div>


        <div class="chat">
            <h3 class="chat-title">Groupe {{$group->name}} : général</h3>
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