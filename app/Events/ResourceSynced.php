<?php

namespace App\Events;

use App\Enums\ResourceType;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResourceSynced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ResourceType $resourceType,
        public int $count,
        public float $duration
    ) {}
}
