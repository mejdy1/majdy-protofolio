<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ServiceTier: string implements HasLabel, HasColor
{
    case Essential = 'essential';
    case Standard = 'standard';
    case Premium = 'premium';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Essential => 'gray',
            self::Standard => 'primary',
            self::Premium => 'success',
        };
    }
}
