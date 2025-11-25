<?php

namespace App\Contracts;

use App\DataTransferObjects\CharacterDto;
use App\DataTransferObjects\PlanetDto;
use App\Models\Character;
use App\Models\Planet;

interface DataSyncServiceInterface
{
    /**
     * Sync a resource type (fetch all and sync).
     */
    public function syncResource(string $resourceType): int;

    /**
     * Sync a character from SWAPI DTO.
     */
    public function syncCharacter(CharacterDto $dto): Character;

    /**
     * Sync a planet from SWAPI DTO.
     */
    public function syncPlanet(PlanetDto $dto): Planet;
}
