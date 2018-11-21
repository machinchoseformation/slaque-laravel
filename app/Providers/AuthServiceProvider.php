<?php

namespace App\Providers;

use App\Group;
use App\Message;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('read-group-messages', function ($user, Group $group) {
            //soit un participant, soit le créateur
            return ($group->participants->contains($user) || $group->creator->id == $user->id);
        });

        Gate::define('publish-message-in-group', function ($user, Group $group) {
            //soit un participant, soit le créateur
            return ($group->participants->contains($user) || $group->creator->id == $user->id);
        });

        Gate::define('delete-message-in-group', function ($user, Message $message) {
            //soit l'auteur du message, soit le créateur du groupe
            return ($message->creator->id == $user->id || $message->group->creator->id == $user->id);
        });
    }
}
