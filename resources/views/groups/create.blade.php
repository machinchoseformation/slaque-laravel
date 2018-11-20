@extends('layouts.app')

@section('title', 'Créer un groupe')

@section('content')
    <h2>Créer un groupe de discussion</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post">
        @csrf
        <label for="name">Nom du groupe</label>
        <input type="text" name="name" id="name" value="">

        <button>Go!</button>
    </form>
@endsection