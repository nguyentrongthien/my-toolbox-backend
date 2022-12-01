<?php

use App\Domains\Checklist\Models\Checklist;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('checklist.{checklist}', function ($user, Checklist $checklist) {
    \Illuminate\Support\Facades\Log::info('checklist ' . $checklist);
    return (int) $user->id === (int) $checklist->user_id;
}, ['middleware' => ['auth:sanctum']]);
