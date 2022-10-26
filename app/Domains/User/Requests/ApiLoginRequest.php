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
     * @return string
     *
     * @throws ValidationException
     */
    public function generateToken(): string
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('email', $this->email)->first();

        if (! $user || ! Hash::check($this->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken(Str::lower($this->input('email')).'|'.$this->ip())->plainTextToken;
    }
}
