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

        $user = User::where('email', $this->get('email'))->first();

        if (! $user || ! Hash::check($this->get('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $tokenName = $this->getTokenName();

        $user->tokens()->where('name', '=', $tokenName)->delete();

        return [
            'token' => $user->createToken($tokenName)->plainTextToken,
            'user_id' => $user->id,
            'username' => $user->name
        ];
    }

    /**
     * Issue an access token based on the provided credentials.
     *
     * @return string
     */
    private function getTokenName(): string
    {
        return Str::lower($this->input('email')) . '|' .
            $this->ip() .
            ($this->has('unique_machine_id') ? '|' . $this->get('unique_machine_id') : '');
    }
}
