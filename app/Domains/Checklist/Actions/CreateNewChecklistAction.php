<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Requests\ChecklistRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNewChecklistAction
{
    use AsAction;

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
}
