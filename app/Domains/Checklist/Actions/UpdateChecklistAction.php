<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Requests\ChecklistRequest;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateChecklistAction
{
    use AsAction;

    /**
     * Handle the action call.
     *
     * @param Checklist $checklist
     * @param string $name
     * @param string|null $subdomain
     * @param string|null $image
     * @return Checklist
     */
    public function handle(Checklist $checklist, string $name, string $subdomain = null, string $image = null): Checklist
    {
        $checklist->fill([
            'name' => $name,
            'subdomain' => $subdomain,
            'image' => $image
        ]);

        $checklist->save();

        return $checklist;
    }

    public function asController(Checklist $checklist, ChecklistRequest $request): JsonResponse
    {
        if ($request->user()->cannot('update', $checklist))
            abort(403);

        $modified_checklist = $this->handle($checklist, ...$request->all());
        return response()->json(['checklist' => $modified_checklist]);
    }
}
