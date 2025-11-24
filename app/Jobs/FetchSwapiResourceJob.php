<?php

namespace App\Jobs;

use App\Services\SwapiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchSwapiResourceJob implements ShouldQueue
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
    public $backoff = 60;

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
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param string $resourceType The resource type (people, planets)
     * @param int $page The page number
     */
    public function __construct(
        public string $resourceType,
        public int $page = 1
    ) {
    }

    /**
     * Get the unique ID for the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return $this->resourceType . '-page-' . $this->page;
    }

    /**
     * Execute the job.
     */
    public function handle(SwapiService $swapiService): void
    {
        Log::info('Fetching SWAPI resource page', [
            'resource_type' => $this->resourceType,
            'page' => $this->page,
        ]);

        try {
            $data = $swapiService->getResource($this->resourceType, $this->page);

            // Dispatch a job for each item in the results
            foreach ($data['results'] as $item) {
                ProcessSwapiResourceJob::dispatch($this->resourceType, $item);
            }

            Log::info('Dispatched ProcessSwapiResourceJob for all items', [
                'resource_type' => $this->resourceType,
                'page' => $this->page,
                'count' => count($data['results']),
            ]);

            // If there's a next page, dispatch another FetchSwapiResourceJob
            if (!empty($data['next'])) {
                self::dispatch($this->resourceType, $this->page + 1);

                Log::info('Dispatched FetchSwapiResourceJob for next page', [
                    'resource_type' => $this->resourceType,
                    'next_page' => $this->page + 1,
                ]);
            } else {
                Log::info('All pages fetched for resource', [
                    'resource_type' => $this->resourceType,
                    'last_page' => $this->page,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch SWAPI resource page', [
                'resource_type' => $this->resourceType,
                'page' => $this->page,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
