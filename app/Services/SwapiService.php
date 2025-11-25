<?php

namespace App\Services;

use App\Contracts\SwapiServiceInterface;
use App\DataTransferObjects\CharacterDto;
use App\DataTransferObjects\PlanetDto;
use Generator;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SwapiService implements SwapiServiceInterface
{
    /**
     * Base URL for SWAPI.
     */
    private string $baseUrl;

    /**
     * Timeout for HTTP requests in seconds.
     */
    private int $timeout;

    /**
     * Number of retry attempts.
     */
    private int $retryAttempts;

    /**
     * Create a new SWAPI service instance.
     */
    public function __construct()
    {
        $this->baseUrl = config('services.swapi.base_url', 'https://swapi.dev/api/');
        $this->timeout = config('services.swapi.timeout', 30);
        $this->retryAttempts = config('services.swapi.retry_attempts', 3);
    }

    /**
     * Get a paginated resource from SWAPI.
     *
     * @param  string  $resource  The resource type (people, planets)
     * @param  int  $page  The page number
     * @return array The API response data
     *
     * @throws ConnectionException
     */
    public function getResource(string $resource, int $page = 1): array
    {
        $url = $this->baseUrl.$resource.'/?page='.$page;

        Log::info('Fetching SWAPI resource', [
            'resource' => $resource,
            'page' => $page,
            'url' => $url,
        ]);

        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryAttempts, 100, function ($exception, $request) {
                    Log::warning('SWAPI request failed, retrying', [
                        'url' => $request->url(),
                        'exception' => $exception->getMessage(),
                    ]);

                    return true;
                })
                ->get($url);

            if ($response->failed()) {
                Log::error('SWAPI request failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                $response->throw();
            }

            $data = $response->json();

            // Validate response structure
            if (! isset($data['results']) || ! is_array($data['results'])) {
                Log::error('Invalid SWAPI response structure', [
                    'url' => $url,
                    'response' => $data,
                ]);

                throw new \RuntimeException('Invalid SWAPI response structure');
            }

            Log::info('SWAPI resource fetched successfully', [
                'resource' => $resource,
                'page' => $page,
                'count' => count($data['results']),
            ]);

            return $data;
        } catch (ConnectionException $e) {
            Log::error('SWAPI connection error', [
                'url' => $url,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get a specific resource by ID.
     *
     * @param  string  $resource  The resource type (people, planets)
     * @param  string  $id  The resource ID
     * @return array The API response data
     *
     * @throws ConnectionException
     */
    public function getResourceById(string $resource, string $id): array
    {
        $url = $this->baseUrl.$resource.'/'.$id.'/';

        Log::info('Fetching SWAPI resource by ID', [
            'resource' => $resource,
            'id' => $id,
            'url' => $url,
        ]);

        try {
            $response = Http::timeout($this->timeout)
                ->retry($this->retryAttempts, 100)
                ->get($url);

            if ($response->failed()) {
                Log::error('SWAPI request failed', [
                    'url' => $url,
                    'status' => $response->status(),
                ]);

                $response->throw();
            }

            return $response->json();
        } catch (ConnectionException $e) {
            Log::error('SWAPI connection error', [
                'url' => $url,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get all resources of a type (iterates through all pages).
     *
     * @param  string  $resource  The resource type (people, planets)
     * @return Generator Yields CharacterDto or PlanetDto objects from all pages
     */
    public function getAllResources(string $resource): Generator
    {
        $page = 1;
        $hasMore = true;

        while ($hasMore) {
            try {
                $data = $this->getResource($resource, $page);

                foreach ($data['results'] as $item) {
                    try {
                        if ($resource === 'people') {
                            yield CharacterDto::fromArray($item);
                        } elseif ($resource === 'planets') {
                            yield PlanetDto::fromArray($item);
                        } else {
                            Log::warning('Unknown resource type', ['resource' => $resource]);
                        }
                    } catch (\InvalidArgumentException $e) {
                        Log::error('Failed to create DTO from API data', [
                            'resource' => $resource,
                            'item' => $item,
                            'exception' => $e->getMessage(),
                        ]);
                    }
                }

                $hasMore = ! empty($data['next']);
                $page++;

                // Add a small delay to be respectful to the API
                if ($hasMore) {
                    usleep(100000); // 100ms delay
                }
            } catch (\Exception $e) {
                Log::error('Error fetching all resources', [
                    'resource' => $resource,
                    'page' => $page,
                    'exception' => $e->getMessage(),
                ]);

                break;
            }
        }
    }
}
