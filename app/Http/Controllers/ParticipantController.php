<?php

namespace App\Http\Controllers;

use App\Api\JsonResponse;
use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ParticipantController extends Controller
{
    public function participantPing(Request $request)
    {
        $groupId = $request->input('groupId');

        $user = Auth::user();
        $user->online = true;
        $user->last_ping_at = new \DateTime();
        $user->save();

        //si un user n'a pas pingé depuis 3 secondes, on le passe en offline
        //pauvre serveur
        User::
            where('online', '=', true)
            ->whereHas('groups', function($query) use ($groupId) {
                $query->where('group_id', '=', $groupId);
            })
            ->where('last_ping_at', '<=', date('Y-m-d H:i:s', strtotime("- 3 seconds")))
            ->update(['online' => false]);

        //récupère les users connectés du groupe
        $onlineUsers = User::
            whereHas('groups', function($query) use ($groupId) {
                $query->where('group_id', '=', $groupId);
            })
            ->where('online', '=', true)
            ->get();

        $response = new JsonResponse($onlineUsers);
        return $response->send();
    }

    public function userSearch(Request $request)
    {
        $kw = $request->input('username');
        $groupId = $request->input('groupId');
        $group = Group::find($groupId);

        $users = User::where('name', 'LIKE', '%' . $kw . '%')->get();

        //utilisateurs qui ne sont pas encore dans le groupe
        $usersNotInGroup = $users->diff($group->participants);

        $response = new JsonResponse($usersNotInGroup);
        return $response->send();
    }

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
