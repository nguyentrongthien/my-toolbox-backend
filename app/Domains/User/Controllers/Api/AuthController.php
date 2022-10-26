<?php

namespace App\Domains\User\Controllers\Api;

use App\Domains\User\Requests\ApiLoginRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    /**
     * Attempt to log in and create a session.
     *
     * @param ApiLoginRequest $request
     * @return JsonResponse
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        try {

            $token = $request->generateToken();

            return response()->json(['success' => 'User is now logged in', 'token' => $token]);

        } catch (ValidationException $e) {

            return response()->json(['error' => 'Invalid login details'], 401);

        }
    }

    /**
     * Log out and destroy an authenticated session.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->validate(['token' => ['required', 'string']]);

        $deleted = DB::table('personal_access_tokens')
            ->where('token', '=', $request->get('token'))
            ->delete();

        return $deleted > 0 ? response()->json(['success' => 'User is logged out']) :
            response()->json(['error' => 'Token not found'], 500);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        return response()->json(['success' => 'User is registered']);
    }
}
