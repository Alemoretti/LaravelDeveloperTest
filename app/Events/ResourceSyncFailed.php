<?php

namespace App\Events;

use App\Enums\ResourceType;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ResourceSyncFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ResourceType $resourceType,
        public Throwable $exception
    ) {}
}
