<?php

namespace App\Domains\Checklist\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    protected $table = 'checklist_items';

    protected $guarded = [];

    /**
     * Relationship with Checklist
     *
     * @return BelongsTo
     */
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(
            'App\Domains\Checklist\Models\Checklist',
            'checklist_id',
            'id'
        );
    }
}
