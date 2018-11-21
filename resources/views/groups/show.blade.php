@extends('layouts.app')

@section('title', 'Groupe')

@section('js')
    <script>
        var deleteUrl = "{{ @route('message_delete')  }}";
    </script>
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
    <div id="chat-app">
        <div>
            <h2>Votre groupe {{$group->name}}</h2>
            <p>Créé par {{$group->creator->name}}</p>
            <a href="{{route('participant_show_invite', ['groupId' => $group->id])}}">Invitez des amis !</a>
        </div>


        <div class="chat">
            <h2>Discutez!</h2>

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