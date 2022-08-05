<?php

use App\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel(env('SCHOOL_ID').'.App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['backpack']]);

Broadcast::channel(env('SCHOOL_ID').'.App.StudentCredential.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['api', 'student']]);

Broadcast::channel(env('SCHOOL_ID').'.App.ParentCredential.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
}, ['guards' => ['parent']]);