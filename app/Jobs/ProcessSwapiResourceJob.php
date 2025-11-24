<?php

namespace App\Jobs;

use App\Models\Character;
use App\Models\SyncLog;
use App\Services\DataSyncService;
use App\Services\RelationshipMapper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSwapiResourceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 30;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     *
     * @param string $resourceType The resource type (people, planets)
     * @param array $resourceData The resource data from SWAPI
     */
    public function __construct(
        public string $resourceType,
        public array $resourceData
    ) {
    }

    /**
     * Get the unique ID for the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        $swapiId = $this->extractSwapiId($this->resourceData['url'] ?? '');
        return $this->resourceType . '-' . $swapiId;
    }

    /**
     * Execute the job.
     */
    public function handle(DataSyncService $dataSyncService, RelationshipMapper $relationshipMapper): void
    {
        $swapiId = $this->extractSwapiId($this->resourceData['url'] ?? '');

        Log::info('Processing SWAPI resource', [
            'resource_type' => $this->resourceType,
            'swapi_id' => $swapiId,
            'name' => $this->resourceData['name'] ?? 'unknown',
        ]);

        // Check if already successfully synced (idempotency check)
        $existingLog = SyncLog::where('resource_type', $this->resourceType)
            ->where('resource_id', $swapiId)
            ->where('status', 'success')
            ->first();

        if ($existingLog) {
            Log::info('Resource already synced, skipping', [
                'resource_type' => $this->resourceType,
                'swapi_id' => $swapiId,
            ]);
            return;
        }

        try {
            // Sync the resource
            if ($this->resourceType === 'people') {
                $model = $dataSyncService->syncCharacter($this->resourceData);

                // Map homeworld relationship
                $relationshipMapper->mapCharacterHomeworld($model, $this->resourceData);

                Log::info('Character processed and relationships mapped', [
                    'character_id' => $model->id,
                    'name' => $model->name,
                ]);
            } elseif ($this->resourceType === 'planets') {
                $model = $dataSyncService->syncPlanet($this->resourceData);

                Log::info('Planet processed', [
                    'planet_id' => $model->id,
                    'name' => $model->name,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to process SWAPI resource', [
                'resource_type' => $this->resourceType,
                'swapi_id' => $swapiId,
                'exception' => $e->getMessage(),
            ]);

            // Log failed sync
            SyncLog::updateOrCreate(
                [
                    'resource_type' => $this->resourceType,
                    'resource_id' => $swapiId,
                ],
                [
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'synced_at' => now(),
                ]
            );

            throw $e;
        }
    }

    /**
     * Extract SWAPI ID from URL.
     *
     * @param string $url
     * @return string
     */
    private function extractSwapiId(string $url): string
    {
        $parts = explode('/', rtrim($url, '/'));
        return end($parts);
    }
}
