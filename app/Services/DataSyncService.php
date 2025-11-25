<?php

namespace App\Services;

use App\Actions\MapCharacterHomeworldAction;
use App\Actions\SyncCharacterAction;
use App\Actions\SyncPlanetAction;
use App\Contracts\DataSyncServiceInterface;
use App\Contracts\SwapiServiceInterface;
use App\DataTransferObjects\CharacterDto;
use App\DataTransferObjects\PlanetDto;
use App\Enums\ResourceType;
use App\Events\ResourceSynced;
use App\Events\ResourceSyncFailed;
use App\Models\Character;
use App\Models\Planet;
use Illuminate\Support\Facades\Log;

class DataSyncService implements DataSyncServiceInterface
{
    public function __construct(
        protected SwapiServiceInterface $swapiService,
        protected SyncCharacterAction $syncCharacterAction,
        protected SyncPlanetAction $syncPlanetAction,
        protected MapCharacterHomeworldAction $mapHomeworldAction
    ) {}

    /**
     * Sync a resource type (fetch all and sync).
     *
     * @param  string  $resourceType  The resource type (people, planets)
     * @return int Number of items synced
     */
    public function syncResource(string $resourceType): int
    {
        $count = 0;
        $startTime = microtime(true);
        $resourceTypeEnum = ResourceType::from($resourceType);

        Log::info('Starting resource sync', ['resource_type' => $resourceType]);

        try {
            foreach ($this->swapiService->getAllResources($resourceType) as $dto) {
                try {
                    if ($resourceType === ResourceType::PEOPLE->value && $dto instanceof CharacterDto) {
                        $character = $this->syncCharacter($dto);
                        // Map homeworld relationship
                        $this->mapHomeworldAction->execute($character, $dto);
                    } elseif ($resourceType === ResourceType::PLANETS->value && $dto instanceof PlanetDto) {
                        $this->syncPlanet($dto);
                    }

                    $count++;
                } catch (\Exception $e) {
                    Log::error('Failed to sync item', [
                        'resource_type' => $resourceType,
                        'dto' => $dto,
                        'exception' => $e->getMessage(),
                    ]);
                }
            }

            $duration = microtime(true) - $startTime;

            // Dispatch success event
            event(new ResourceSynced($resourceTypeEnum, $count, $duration));
        } catch (\Exception $e) {
            // Dispatch failure event
            event(new ResourceSyncFailed($resourceTypeEnum, $e));

            throw $e;
        }

        return $count;
    }

    /**
     * Sync a character from SWAPI DTO.
     *
     * @param  CharacterDto  $dto  The character DTO
     * @return Character The synced character model
     */
    public function syncCharacter(CharacterDto $dto): Character
    {
        return $this->syncCharacterAction->execute($dto);
    }

    /**
     * Sync a planet from SWAPI DTO.
     *
     * @param  PlanetDto  $dto  The planet DTO
     * @return Planet The synced planet model
     */
    public function syncPlanet(PlanetDto $dto): Planet
    {
        return $this->syncPlanetAction->execute($dto);
    }
}
