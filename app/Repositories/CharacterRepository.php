<?php

namespace App\Repositories;

use App\Contracts\CharacterRepositoryInterface;
use App\Models\Character;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CharacterRepository implements CharacterRepositoryInterface
{
    public function findBySwapiId(string $swapiId): ?Character
    {
        return Cache::remember(
            "character.swapi_id.{$swapiId}",
            now()->addHour(),
            fn () => Character::where('swapi_id', $swapiId)->first()
        );
    }

    public function updateOrCreate(string $swapiId, array $data): Character
    {
        $character = Character::updateOrCreate(
            ['swapi_id' => $swapiId],
            $data
        );

        // Clear cache
        Cache::forget("character.swapi_id.{$swapiId}");
        Cache::forget("character.{$character->id}");

        return $character;
    }

    public function all(array $filters = []): Collection
    {
        $query = Character::query();

        if (! empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        if (! empty($filters['homeworld_id'])) {
            $query->where('homeworld_id', $filters['homeworld_id']);
        }

        return $query->get();
    }

    public function find(int $id): ?Character
    {
        return Cache::remember(
            "character.{$id}",
            now()->addHour(),
            fn () => Character::find($id)
        );
    }

    public function delete(int $id): bool
    {
        $character = Character::find($id);

        if (! $character) {
            return false;
        }

        // Clear cache
        Cache::forget("character.{$id}");
        Cache::forget("character.swapi_id.{$character->swapi_id}");

        return $character->delete();
    }
}
