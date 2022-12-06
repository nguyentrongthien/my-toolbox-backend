<?php

namespace App\Domains\User\Requests;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiLoginRequest extends LoginRequest
{

    /**
     * Issue an access token based on the provided credentials.
     *
     * @return array
     *
     * @throws ValidationException
     */
    public function generateToken(): array
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->email)->first();

        if (! $user || ! Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        return [
            'token' => $user->createToken(Str::lower($this->input('email')).'|'.$this->ip())->plainTextToken,
            'user_id' => $user->id,
            'username' => $user->name
        ];
    }
}
