<?php

namespace App\Enums;


enum Association: string
{
    case Solidy = 'solidy';
    case Nova = 'nova';

    public static function getSelectOptions(): array
    {
        return [
            self::Solidy->value => self::Solidy->name,
            self::Nova->value => self::Nova->name,
        ];
    }

    public function getDatabaseConnection(): string
    {
        return match ($this) {
            self::Solidy => 'ileva_solidy',
            self::Nova => 'ileva_nova',
        };
    }
}
