<?php

namespace App\Services;

use App\Models\Character;
use App\Models\Planet;
use App\Models\SyncLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataSyncService
{
    /**
     * Sync a resource type (fetch all and sync).
     *
     * @param string $resourceType The resource type (people, planets)
     * @return int Number of items synced
     */
    public function syncResource(string $resourceType): int
    {
        $swapiService = new SwapiService();
        $count = 0;

        Log::info('Starting resource sync', ['resource_type' => $resourceType]);

        try {
            foreach ($swapiService->getAllResources($resourceType) as $item) {
                try {
                    if ($resourceType === 'people') {
                        $this->syncCharacter($item);
                    } elseif ($resourceType === 'planets') {
                        $this->syncPlanet($item);
                    }

                    $count++;
                } catch (\Exception $e) {
                    Log::error('Failed to sync item', [
                        'resource_type' => $resourceType,
                        'item' => $item,
                        'exception' => $e->getMessage(),
                    ]);
                }
            }

            Log::info('Resource sync completed', [
                'resource_type' => $resourceType,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error('Resource sync failed', [
                'resource_type' => $resourceType,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }

        return $count;
    }

    /**
     * Sync a character from SWAPI data.
     *
     * @param array $data The character data from SWAPI
     * @return Character The synced character model
     */
    public function syncCharacter(array $data): Character
    {
        $swapiId = $this->extractSwapiId($data['url']);

        return DB::transaction(function () use ($data, $swapiId) {
            $character = Character::updateOrCreate(
                ['swapi_id' => $swapiId],
                [
                    'name' => $data['name'] ?? null,
                    'height' => $data['height'] ?? null,
                    'mass' => $data['mass'] ?? null,
                    'hair_color' => $data['hair_color'] ?? null,
                    'skin_color' => $data['skin_color'] ?? null,
                    'eye_color' => $data['eye_color'] ?? null,
                    'birth_year' => $data['birth_year'] ?? null,
                    'gender' => $data['gender'] ?? null,
                    // Note: homeworld_id will be set later by RelationshipMapper
                ]
            );

            // Log sync operation
            SyncLog::create([
                'resource_type' => 'people',
                'resource_id' => $swapiId,
                'status' => 'success',
                'synced_at' => now(),
            ]);

            Log::info('Character synced', [
                'swapi_id' => $swapiId,
                'name' => $character->name,
            ]);

            return $character;
        });
    }

    /**
     * Sync a planet from SWAPI data.
     *
     * @param array $data The planet data from SWAPI
     * @return Planet The synced planet model
     */
    public function syncPlanet(array $data): Planet
    {
        $swapiId = $this->extractSwapiId($data['url']);

        return DB::transaction(function () use ($data, $swapiId) {
            $planet = Planet::updateOrCreate(
                ['swapi_id' => $swapiId],
                [
                    'name' => $data['name'] ?? null,
                    'rotation_period' => $data['rotation_period'] ?? null,
                    'orbital_period' => $data['orbital_period'] ?? null,
                    'diameter' => $data['diameter'] ?? null,
                    'climate' => $data['climate'] ?? null,
                    'gravity' => $data['gravity'] ?? null,
                    'terrain' => $data['terrain'] ?? null,
                    'surface_water' => $data['surface_water'] ?? null,
                    'population' => $data['population'] ?? null,
                ]
            );

            // Log sync operation
            SyncLog::create([
                'resource_type' => 'planets',
                'resource_id' => $swapiId,
                'status' => 'success',
                'synced_at' => now(),
            ]);

            Log::info('Planet synced', [
                'swapi_id' => $swapiId,
                'name' => $planet->name,
            ]);

            return $planet;
        });
    }

    /**
     * Extract SWAPI ID from a URL.
     *
     * @param string $url The SWAPI URL
     * @return string The extracted ID
     */
    private function extractSwapiId(string $url): string
    {
        // Extract ID from URL like "https://swapi.dev/api/people/1/"
        $parts = explode('/', rtrim($url, '/'));
        return end($parts);
    }
}

