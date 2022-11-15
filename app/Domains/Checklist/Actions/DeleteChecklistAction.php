<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Models\ChecklistItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteChecklistAction
{
    use AsAction;

    /**
     * Handle the action call.
     *
     * @param Checklist $checklist
     * @return void
     */
    public function handle(Checklist $checklist): void
    {
        $checklist->delete();
    }

    public function asController(Checklist $checklist, Request $request): JsonResponse
    {
        if ($request->user()->cannot('delete', $checklist))
            abort(403);

        $this->handle($checklist);

        return response()->json(['checklist_id' => $checklist->id]);
    }

}
