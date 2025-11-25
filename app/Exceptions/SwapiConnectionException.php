<?php

namespace App\Exceptions;

class SwapiConnectionException extends SwapiException
{
    public static function failedToConnect(string $url, string $reason): self
    {
        return new self("Failed to connect to SWAPI at {$url}: {$reason}");
    }

    public static function timeout(string $url, int $timeout): self
    {
        return new self("SWAPI request to {$url} timed out after {$timeout} seconds");
    }
}
