<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/** api  */
Route::post('/group/{groupId}/message', "MessageController@create")
    ->name('message_create');

Route::get('/group/{groupId}/message/since', "MessageController@getSince")
    ->name('message_get_since');

Route::delete('/message', "MessageController@delete")
    ->name('message_delete');

Route::get('/participant/ping', 'ParticipantController@participantPing')
    ->name('participant_ping');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/contact', 'MainController@contact');

Route::get('/groupe/creation', 'GroupController@showCreateForm')->name('group_create');
Route::post('/groupe/creation', 'GroupController@create');
Route::get('/groupe/{id}', 'GroupController@show')
    ->where('id', '[0-9]+')
    ->name('group_show');


Route::get('/groupe/{groupId}/participants/invitations', 'ParticipantController@showInvite')
    ->where('groupId', '[0-9]+')
    ->name('participant_show_invite');

Route::get('/groupe/{groupId}/participants/invitations/{userId}', 'ParticipantController@inviteUserToGroup')
    ->where('groupId', '[0-9]+')
    ->where('userId', '[0-9]+')
    ->name('participant_invite_user_to_group');

// Authentication Routes...
$this->get('connexion', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('connexion', 'Auth\LoginController@login');
$this->post('deconnexion', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
if ($options['inscription'] ?? true) {
    $this->get('inscription', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('inscription', 'Auth\RegisterController@register');
}

// Password Reset Routes...
$this->get('mdp/changement', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('mdp/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
$this->get('mdp/changement/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('mdp/changement', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email Verification Routes...
if ($options['verify'] ?? false) {
    $this->emailVerification();
}

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
