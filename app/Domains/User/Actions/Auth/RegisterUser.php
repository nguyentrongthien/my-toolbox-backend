<?php

namespace App\Domains\User\Actions\Auth;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Validation\Rules;

class RegisterUser
{
    use AsAction;

    public string $commandSignature = 'users:register';
    public string $commandDescription = 'Register a new user.';
    public bool $commandHidden = true;

    /**
     * Handle the action call.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function handle(string $name, string $email, string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }

    public function asCommand(Command $command): void
    {
        $name = $this->getValidatedConsoleInput(
            'username', $command, ['required', 'string', 'min:6']);

        $email = $this->getValidatedConsoleInput(
            'email', $command, ['required', 'string', 'email', 'max:255', 'unique:users']);

        $password = $this->getValidatedConsoleInput(
            'password', $command, ['required', 'min:3', Rules\Password::defaults()]);

        $user = $this->handle($name, $email, $password);

        $command->info("User has been registered.");
        $command->info($user);
    }

    /**
     * Prompt for user input and validate said input according to provided rules.
     *
     * @param string $fieldName
     * @param Command $command
     * @param array $rules
     * @return mixed
     */
    private function getValidatedConsoleInput(string $fieldName, Command $command, array $rules): mixed
    {
        $value = $command->ask("Enter value for field [{$fieldName}]");

        $validator = Validator::make([$fieldName => $value], [
            $fieldName => $rules,
        ]);

        while ($validator->fails()) {
            $value = $command->ask($validator->getMessageBag()->get($fieldName)[0] . " Please try again");
            $validator->setData([$fieldName => $value]);
        }

        return $value;
    }
}
