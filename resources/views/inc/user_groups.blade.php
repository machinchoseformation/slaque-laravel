@foreach (Auth::user()->groups as $group)
    @if (!$group->is_one_on_one)
        <article>
            <a href="{{route('group_show', ['id' => 1])}}">{{$group->name}}</a>
        </article>
    @endif
@endforeach