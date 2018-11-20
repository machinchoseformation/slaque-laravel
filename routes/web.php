<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', 'HomeController@index')->name('home');
Route::get('/contact', 'MainController@contact');

//Auth::routes();

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