<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Models\ChecklistItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteChecklistItemAction
{
    use AsAction;

    /**
     * Handle the action call.
     *
     * @param ChecklistItem $item
     * @return void
     */
    public function handle(ChecklistItem $item): void
    {
        $item->delete();
    }

    public function asController(Checklist $checklist, ChecklistItem $item, Request $request): JsonResponse
    {
        if ($request->user()->cannot('update', $checklist) && $checklist->id !== $item->checklist_id)
            abort(403);

        $this->handle($item);

        $checklist->load('items');

        return response()->json(['checklist' => $checklist]);
    }
}
