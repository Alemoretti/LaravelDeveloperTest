<?php

namespace App\Contracts;

use Generator;

interface SwapiServiceInterface
{
    /**
     * Get a paginated resource from SWAPI.
     */
    public function getResource(string $resource, int $page = 1): array;

    /**
     * Get a specific resource by ID.
     */
    public function getResourceById(string $resource, string $id): array;

    /**
     * Get all resources of a type (iterates through all pages).
     */
    public function getAllResources(string $resource): Generator;
}
