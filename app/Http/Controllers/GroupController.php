<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id)
    {
        $group = Group::find($id);
        return view('groups.show', ['group' => $group]);
    }

    public function showCreateForm()
    {
        return view('groups.create');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:groups|max:255',
        ]);

        $data = Group::create([
            'name' => $validatedData['name'],
            'creator_id' => Auth::user()->id
        ]);

        return Redirect::route('group_show', ['id' => $data->id])
            ->with('message', 'Groupe créé!');
    }
}