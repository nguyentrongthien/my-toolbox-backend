<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Models\ChecklistItem;
use App\Domains\Checklist\Requests\ChecklistItemRequest;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateChecklistItemAction
{
    use AsAction;

    /**
     * Handle the action call.
     *
     * @param ChecklistItem $item
     * @param string $title
     * @param string|null $description
     * @return ChecklistItem
     */
    public function handle(ChecklistItem $item, string $title, string $description = null): ChecklistItem
    {
        $item->fill([
            'title' => $title,
            'description' => $description,
        ]);

        $item->save();

        return $item;
    }

    public function asController(Checklist $checklist, ChecklistItem $item, ChecklistItemRequest $request): JsonResponse
    {
        if ($request->user()->cannot('update', $checklist) && $checklist->id !== $item->checklist_id)
            abort(403);

        $this->handle($item, ...$request->all());

        $checklist->load('items');

        return response()->json(['checklist' => $checklist]);
    }

}
