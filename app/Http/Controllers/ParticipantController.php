<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ParticipantController extends Controller
{
    public function inviteUserToGroup($groupId, $userId)
    {
        $group = Group::find($groupId);
        $user = User::find($userId);

        if (!$group->participants->contains($user->id)){
            $group->participants()->attach($user);
        }

        return \redirect()->route('participant_show_invite', ['groupId' => $group->id]);
    }

    public function showInvite($groupId)
    {
        $allUsers = User::orderBy('created_at', 'ASC')->get();
        $group = Group::find($groupId);

        //utilisateurs qui ne sont pas encore dans le groupe
        $usersNotInGroup = $allUsers->diff($group->participants);

        return view('participants.show_invite', [
            'usersNotInGroup' => $usersNotInGroup,
            'group' => $group,
        ]);
    }
}
