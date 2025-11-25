<?php

namespace App\Listeners;

use App\Events\ResourceSynced;
use Illuminate\Support\Facades\Log;

class LogResourceSynced
{
    public function handle(ResourceSynced $event): void
    {
        Log::info('Resource sync completed', [
            'resource_type' => $event->resourceType->value,
            'count' => $event->count,
            'duration' => round($event->duration, 2).' seconds',
        ]);
    }
}
