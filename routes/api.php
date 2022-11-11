<?php

use App\Domains\Checklist\Actions\CreateNewChecklistAction;
use App\Domains\Checklist\Actions\UpdateChecklistAction;
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

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() {

        Route::get('/user', function (Request $request) {
            return $request->user()->only('name');
        });

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/checklists', CreateNewChecklistAction::class);
        Route::put('/checklists/{checklist}', UpdateChecklistAction::class);

    });

});
