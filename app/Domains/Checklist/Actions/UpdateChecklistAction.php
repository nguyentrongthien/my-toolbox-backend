<?php

namespace App\Domains\Checklist\Actions;

use App\Domains\Checklist\Models\Checklist;
use App\Domains\Checklist\Requests\ChecklistRequest;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateChecklistAction
{
    use AsAction;

    public string $commandSignature = 'checklists:update {id?}';
    public string $commandDescription = 'Update a checklist with the given id.';
    public bool $commandHidden = true;

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

    public function asCommand(Command $command): void
    {
        $id = $command->argument('id');
        if (!$id) {
            $ids = Checklist::all()->pluck('id')->all();
            $id = $command->choice("Select checklist id, default id =", $ids, 0, 1);
        }

        $checklist = Checklist::find($id);

        $name = $command->ask("Enter a new name (old name will be if input is null)");
        $subdomain = $command->ask("Enter a new subdomain (leave empty for null)");
        $image = $command->ask("Enter a new image (leave empty for null)");

        $command->info("Old Checklist");
        $command->info($checklist);

        $modified_checklist = $this->handle(
            $checklist,
            $name ?: $checklist->name,
            $subdomain,
            $image,
        );

        $command->info("Updated Checklist");
        $command->info($modified_checklist);
    }
}
