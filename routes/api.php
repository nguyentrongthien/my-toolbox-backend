<?php

use App\Domains\Checklist\Actions\AddItemToChecklistAction;
use App\Domains\Checklist\Actions\CreateNewChecklistAction;
use App\Domains\Checklist\Actions\DeleteChecklistAction;
use App\Domains\Checklist\Actions\DeleteChecklistItemAction;
use App\Domains\Checklist\Actions\UpdateChecklistAction;
use App\Domains\Checklist\Actions\UpdateChecklistItemAction;
use App\Domains\User\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/v1')->group(function() {
    Broadcast::routes(['middleware' => ['auth:sanctum']]);

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {

        Route::get('/user', function (Request $request) {
            return $request->user()->only('name');
        });

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/checklists', CreateNewChecklistAction::class);
        Route::put('/checklists/{checklist}', UpdateChecklistAction::class);
        Route::delete('/checklists/{checklist}', DeleteChecklistAction::class);
        Route::post('/checklists/{checklist}/items', AddItemToChecklistAction::class);
        Route::put('/checklists/{checklist}/items/{item}', UpdateChecklistItemAction::class);
        Route::delete('/checklists/{checklist}/items/{item}', DeleteChecklistItemAction::class);

    });

});
