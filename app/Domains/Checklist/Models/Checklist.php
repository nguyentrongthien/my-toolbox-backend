<?php

namespace App\Domains\Checklist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Checklist extends Model
{
    protected $table = 'checklists';

    protected $guarded = [];

    /**
     * Relationship with ChecklistItem
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(
            'App\Domains\Checklist\Models\ChecklistItem',
            'checklist_id',
            'id'
        );
    }

    /**
     * Relationship with User
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            'App\Models\User',
            'user_id',
            'id'
        );
    }
}
