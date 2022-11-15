<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Models\ChecklistItem;
use App\Domains\Checklist\Requests\ChecklistItemRequest;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class AddItemToChecklistAction
{
    use AsAction;

    /**
     * Handle the action call.
     *
     * @param Checklist $checklist
     * @param string $title
     * @param string|null $description
     * @return Checklist
     */
    public function handle(Checklist $checklist, string $title, string $description = null): Checklist
    {
        ChecklistItem::create([
            'title' => $title,
            'description' => $description,
            'checklist_id' => $checklist->id
        ]);

        $checklist->load('items');

        return $checklist;
    }

    public function asController(Checklist $checklist, ChecklistItemRequest $request): JsonResponse
    {
        if ($request->user()->cannot('update', $checklist))
            abort(403);

        $modified_checklist = $this->handle($checklist, ...$request->all());
        return response()->json(['checklist' => $modified_checklist]);
    }
}
