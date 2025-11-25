<?php

namespace App\Exceptions;

class SwapiValidationException extends SwapiException
{
    public static function invalidResponse(string $reason): self
    {
        return new self("Invalid SWAPI response: {$reason}");
    }

    public static function missingRequiredField(string $field): self
    {
        return new self("Missing required field in SWAPI response: {$field}");
    }
}
