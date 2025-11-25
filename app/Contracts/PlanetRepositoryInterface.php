<?php

namespace App\Contracts;

use App\Models\Planet;
use Illuminate\Database\Eloquent\Collection;

interface PlanetRepositoryInterface
{
    /**
     * Find a planet by SWAPI ID.
     */
    public function findBySwapiId(string $swapiId): ?Planet;

    /**
     * Update or create a planet.
     */
    public function updateOrCreate(string $swapiId, array $data): Planet;

    /**
     * Get all planets with optional filters.
     */
    public function all(array $filters = []): Collection;

    /**
     * Find a planet by ID.
     */
    public function find(int $id): ?Planet;

    /**
     * Delete a planet.
     */
    public function delete(int $id): bool;
}
