<ul class="users-list">
    <li class="user-you online" data-user-id="{{ Auth::user()->id }}"><span class="connection-status"></span>{{ Auth::user()->name }}</li>
    @foreach($group->participants as $user)
        @if (Auth::user()->id != $user->id)
            <li class="user-btn" data-user-id="{{ $user->id }}"><span class="connection-status"></span><a href="{{ route('group_one_on_one_create', ['other_user_id' => $user->id])  }}">{{$user->name}}</a></li>
        @endif
    @endforeach
</ul>