<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class GetChecklistsAction
{
    use AsAction;

    /**
     * Handle the action call.
     *
     * @param User|null $user
     * @return array|Collection|\Illuminate\Support\Collection
     */
    public function handle(User $user = null): array|Collection|\Illuminate\Support\Collection
    {
        if ($user) return $user->checklists;

        return Checklist::all();
    }

    public function asController(Request $request): JsonResponse
    {
        if (!$request->user())
            abort(403);

        $checklists = $this->handle($request->user());

        return response()->json(['checklists' => $checklists]);
    }

}
