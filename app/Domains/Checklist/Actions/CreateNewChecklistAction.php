<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Requests\ChecklistRequest;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewChecklistAction
{
    use AsAction;

    public string $commandSignature = 'checklists:create {name} {subdomain?} {image?} {--user_id=}';
    public string $commandDescription = 'Create a checklist for a given user.';
    public bool $commandHidden = true;

    /**
     * Handle the action call.
     *
     * @param User $user
     * @param string $name
     * @param string|null $subdomain
     * @param string|null $image
     * @return Checklist
     */
    public function handle(User $user, string $name, string $subdomain = null, string $image = null): Checklist
    {
        return Checklist::create([
            'name' => $name,
            'subdomain' => $subdomain,
            'image' => $image,
            'user_id' => $user->id,
        ]);
    }

    public function asController(ChecklistRequest $request): JsonResponse
    {
        $checklist = $this->handle(
            $request->user(),
            ...$request->all()
        );

        return response()->json(['checklist' => $checklist]);
    }

    /**
     * Handle the action call as an Artisan command.
     * User can specify a user_id or select one from available list
     *
     * @param Command $command
     * @return void
     */
    public function asCommand(Command $command): void
    {
        $ids = User::all()->pluck('id')->all();
        $id = $command->option('user_id');

        if (!$id)
            $id = $command->choice("Select a user id for the checklist, default user_id =", $ids, 0, 1);

        $user = User::find($id);

        if (!$user) {
            $command->error('Invalid user_id: ' . $id);
            return;
        }

        $checklist = $this->handle(
            $user,
            $command->argument('name'),
            $command->argument('subdomain'),
            $command->argument('image'),
        );

        $command->info("Checklist created");
        $command->info($checklist);
    }
}
