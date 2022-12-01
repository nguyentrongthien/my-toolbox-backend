<?php

namespace App\Domains\Checklist\Traits;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;

trait ChecklistBroadcastsEvents
{
    use BroadcastsEvents;

    /**
     * Get the channels that model events should broadcast on.
     *
     * @param  string  $event
     * @return Channel[]|array
     */
    public function broadcastOn($event): array
    {
        return [new PrivateChannel('checklist.' . $this->id)];
    }

    /**
     * The model event's broadcast name.
     *
     * @param string $event
     * @return string|null
     */
    public function broadcastAs(string $event): ?string
    {
        return match ($event) {
            'created' => 'checklist.created',
            'updated' => 'checklist.updated',
            'restored' => 'checklist.restored',
            'deleted' => 'checklist.deleted',
            'trashed' => 'checklist.trashed',
            default => null,
        };
    }

    /**
     * Get the data to broadcast for the model.
     *
     * @param string $event
     * @return array
     */
    public function broadcastWith(string $event): array
    {
        return match ($event) {
            'deleted', 'trashed' => ['id' => $this->id],
            default => ['model' => $this],
        };
    }
}
