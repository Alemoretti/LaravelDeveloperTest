<?php

namespace App\Listeners;

use App\Events\ResourceSyncFailed;
use Illuminate\Support\Facades\Log;

class LogResourceSyncFailed
{
    public function handle(ResourceSyncFailed $event): void
    {
        Log::error('Resource sync failed', [
            'resource_type' => $event->resourceType->value,
            'exception' => $event->exception->getMessage(),
            'trace' => $event->exception->getTraceAsString(),
        ]);
    }
}
