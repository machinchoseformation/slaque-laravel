@foreach (Auth::user()->groups as $group)
    @if (!$group->is_one_on_one)
        <article>
            <a href="{{route('group_show', ['id' => $group->id])}}">{{$group->name}}</a>
        </article>
    @endif
@endforeach