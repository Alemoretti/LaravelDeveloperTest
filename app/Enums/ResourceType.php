<?php

namespace App\Enums;

enum ResourceType: string
{
    case PEOPLE = 'people';
    case PLANETS = 'planets';

    /**
     * Get all resource types as an array of values.
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get the human-readable name.
     */
    public function label(): string
    {
        return match ($this) {
            self::PEOPLE => 'Characters',
            self::PLANETS => 'Planets',
        };
    }

    /**
     * Get the singular form.
     */
    public function singular(): string
    {
        return match ($this) {
            self::PEOPLE => 'Character',
            self::PLANETS => 'Planet',
        };
    }
}
