<?php

namespace App\Exceptions;

class ResourceNotFoundException extends SwapiException
{
    public static function resourceNotFound(string $resourceType, string $id): self
    {
        return new self("Resource '{$resourceType}' with ID '{$id}' not found");
    }
}
