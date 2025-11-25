<?php

namespace App\Repositories;

use App\Contracts\PlanetRepositoryInterface;
use App\Models\Planet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class PlanetRepository implements PlanetRepositoryInterface
{
    public function findBySwapiId(string $swapiId): ?Planet
    {
        return Cache::remember(
            "planet.swapi_id.{$swapiId}",
            now()->addHour(),
            fn () => Planet::where('swapi_id', $swapiId)->first()
        );
    }

    public function updateOrCreate(string $swapiId, array $data): Planet
    {
        $planet = Planet::updateOrCreate(
            ['swapi_id' => $swapiId],
            $data
        );

        // Clear cache
        Cache::forget("planet.swapi_id.{$swapiId}");
        Cache::forget("planet.{$planet->id}");

        return $planet;
    }

    public function all(array $filters = []): Collection
    {
        $query = Planet::query();

        if (! empty($filters['climate'])) {
            $query->where('climate', 'like', "%{$filters['climate']}%");
        }

        if (! empty($filters['terrain'])) {
            $query->where('terrain', 'like', "%{$filters['terrain']}%");
        }

        return $query->get();
    }

    public function find(int $id): ?Planet
    {
        return Cache::remember(
            "planet.{$id}",
            now()->addHour(),
            fn () => Planet::find($id)
        );
    }

    public function delete(int $id): bool
    {
        $planet = Planet::find($id);

        if (! $planet) {
            return false;
        }

        // Clear cache
        Cache::forget("planet.{$id}");
        Cache::forget("planet.swapi_id.{$planet->swapi_id}");

        return $planet->delete();
    }
}
