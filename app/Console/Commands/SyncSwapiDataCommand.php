<?php

namespace App\Console\Commands;

use App\Services\DataSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncSwapiDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swapi:sync
                            {--resource= : The resource type to sync (people or planets)}
                            {--all : Sync all resources (planets first, then characters)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize data from SWAPI (Star Wars API) to local database';

    /**
     * Execute the console command.
     */
    public function handle(DataSyncService $dataSyncService): int
    {
        $this->info('🌟 Starting SWAPI Data Synchronization 🌟');
        $this->newLine();

        $resource = $this->option('resource');
        $all = $this->option('all');

        try {
            // Determine what to sync
            if ($resource) {
                // Sync specific resource
                $this->syncSpecificResource($dataSyncService, $resource);
            } elseif ($all || (!$resource && !$all)) {
                // Sync all (default behavior if no options provided)
                $this->syncAllResources($dataSyncService);
            }

            $this->newLine();
            $this->info('✅ Synchronization completed successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Synchronization failed: ' . $e->getMessage());
            Log::error('SWAPI sync command failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Sync a specific resource type.
     *
     * @param DataSyncService $dataSyncService
     * @param string $resource
     * @return void
     */
    private function syncSpecificResource(DataSyncService $dataSyncService, string $resource): void
    {
        // Validate resource type
        if (!in_array($resource, ['people', 'planets'])) {
            $this->error('Invalid resource type. Must be either "people" or "planets".');
            throw new \InvalidArgumentException('Invalid resource type');
        }

        $displayName = $resource === 'people' ? 'Characters' : 'Planets';
        $this->info("Syncing {$displayName}...");

        $bar = $this->output->createProgressBar();
        $bar->start();

        $count = $dataSyncService->syncResource($resource);

        $bar->finish();
        $this->newLine();
        $this->info("✓ Synced {$count} {$displayName}");
    }

    /**
     * Sync all resources (planets first, then characters).
     *
     * @param DataSyncService $dataSyncService
     * @return void
     */
    private function syncAllResources(DataSyncService $dataSyncService): void
    {
        $this->info('Syncing all resources...');
        $this->line('Note: Planets will be synced first, then characters to ensure homeworld relationships.');
        $this->newLine();

        // Sync planets first
        $this->info('[1/2] Syncing Planets...');
        $planetsBar = $this->output->createProgressBar();
        $planetsBar->start();

        $planetsCount = $dataSyncService->syncResource('planets');

        $planetsBar->finish();
        $this->newLine();
        $this->info("✓ Synced {$planetsCount} Planets");
        $this->newLine();

        // Sync characters
        $this->info('[2/2] Syncing Characters...');
        $charactersBar = $this->output->createProgressBar();
        $charactersBar->start();

        $charactersCount = $dataSyncService->syncResource('people');

        $charactersBar->finish();
        $this->newLine();
        $this->info("✓ Synced {$charactersCount} Characters");

        $this->newLine();
        $this->table(
            ['Resource', 'Count'],
            [
                ['Planets', $planetsCount],
                ['Characters', $charactersCount],
                ['Total', $planetsCount + $charactersCount],
            ]
        );
    }
}
