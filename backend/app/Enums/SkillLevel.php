<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SkillLevel: string implements HasLabel, HasColor
{
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';
    case Expert = 'expert';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Beginner => 'gray',
            self::Intermediate => 'info',
            self::Advanced => 'primary',
            self::Expert => 'success',
        };
    }

    public function percentage(): int
    {
        return match ($this) {
            self::Beginner => 35,
            self::Intermediate => 60,
            self::Advanced => 82,
            self::Expert => 95,
        };
    }
}
