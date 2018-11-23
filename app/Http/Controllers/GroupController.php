<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        return view('groups.list');
    }

    public function show($id)
    {
        $group = Group::find($id);

        if (!$group){
            abort(404, 'Groupe non trouvé !');
        }

        if (Gate::denies('read-group-messages', $group)) {
            abort(403, "Non non non, pas votre groupe.");
        }

        return view('groups.show', ['group' => $group]);
    }

    public function showCreateForm()
    {
        return view('groups.create');
    }

    public function createOneOnOne(Request $request)
    {
        $validatedData = $request->validate([
            'other_user_id' => 'required',
        ]);

        $otherUserId = $validatedData['other_user_id'];
        $otherUser = User::find($otherUserId);

        $group = Group::create([
            'name' => Auth::id() . "_" . $otherUserId,
            'creator_id' => Auth::user()->id,
            'is_one_on_one' => true,
            'other_user_id' => $otherUserId,
        ]);

        $group->participants()->attach(Auth::user());
        $group->participants()->attach($otherUser);

        return Redirect::route('group_show', ['id' => $group->id])
            ->with('message', 'Discussion avec ' . $otherUser->name . ' créée!');
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:groups|max:255',
        ]);

        $group = Group::create([
            'name' => $validatedData['name'],
            'creator_id' => Auth::user()->id,
        ]);

        $group->participants()->attach(Auth::user());

        return Redirect::route('group_show', ['id' => $group->id])
            ->with('message', 'Groupe créé!');
    }
}
