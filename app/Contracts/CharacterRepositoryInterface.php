<?php

namespace App\Contracts;

use App\Models\Character;
use Illuminate\Database\Eloquent\Collection;

interface CharacterRepositoryInterface
{
    /**
     * Find a character by SWAPI ID.
     */
    public function findBySwapiId(string $swapiId): ?Character;

    /**
     * Update or create a character.
     */
    public function updateOrCreate(string $swapiId, array $data): Character;

    /**
     * Get all characters with optional filters.
     */
    public function all(array $filters = []): Collection;

    /**
     * Find a character by ID.
     */
    public function find(int $id): ?Character;

    /**
     * Delete a character.
     */
    public function delete(int $id): bool;
}
