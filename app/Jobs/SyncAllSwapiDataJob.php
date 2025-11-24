<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncAllSwapiDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * Sync planets first, then characters to ensure homeworld relationships can be linked.
     */
    public function handle(): void
    {
        Log::info('Starting full SWAPI data synchronization');

        try {
            // IMPORTANT: Sync planets first so that homeworld relationships can be linked
            Log::info('Starting planets synchronization');
            FetchSwapiResourceJob::dispatch('planets', 1);

            // Delay character sync to allow planets to finish
            // Using job chaining would be ideal, but for simplicity we'll dispatch after a delay
            Log::info('Planets sync dispatched, scheduling characters sync');

            // Dispatch characters sync (will start after planets are done)
            // In production, you might want to use job chaining or events
            FetchSwapiResourceJob::dispatch('people', 1)->delay(now()->addMinutes(2));

            Log::info('Full SWAPI data synchronization jobs dispatched', [
                'resources' => ['planets', 'people'],
                'note' => 'Planets will sync first, characters after 2 minutes',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to dispatch SWAPI synchronization jobs', [
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
