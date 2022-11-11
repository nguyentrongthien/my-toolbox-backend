<?php

namespace App\Domains\Checklist\Models\Policies;

use App\Domains\Checklist\Models\Checklist;
use App\Models\User;

class ChecklistPolicy
{
    /**
     * Determine if the given checklist can be updated by the user.
     *
     * @param User $user
     * @param Checklist $checklist
     * @return bool
     */
    public function update(User $user, Checklist $checklist): bool
    {
        return $user->id === $checklist->user_id;
    }

}
