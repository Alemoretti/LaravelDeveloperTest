<?php

namespace App\Actions;

use App\Contracts\PlanetRepositoryInterface;
use App\DataTransferObjects\PlanetDto;
use App\Enums\SyncStatus;
use App\Models\Planet;
use App\Models\SyncLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncPlanetAction
{
    public function __construct(
        protected PlanetRepositoryInterface $planetRepository
    ) {}

    /**
     * Execute the action to sync a planet.
     */
    public function execute(PlanetDto $dto): Planet
    {
        return DB::transaction(function () use ($dto) {
            $planet = $this->planetRepository->updateOrCreate(
                $dto->swapiId,
                $dto->toArray()
            );

            // Log sync operation
            SyncLog::create([
                'resource_type' => 'planets',
                'resource_id' => $dto->swapiId,
                'status' => SyncStatus::SUCCESS->value,
                'synced_at' => now(),
            ]);

            Log::info('Planet synced', [
                'swapi_id' => $dto->swapiId,
                'name' => $planet->name,
            ]);

            return $planet;
        });
    }
}
