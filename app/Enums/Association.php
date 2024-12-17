<?php

namespace App\Enums;


enum Association: string
{
    case Solidy = 'solidy';
    case Nova = 'nova';
    case Motoclub = 'motoclub';

    public static function getSelectOptions(): array
    {
        return [
            self::Solidy->value => self::Solidy->name,
            self::Nova->value => self::Nova->name,
            // self::Motoclub->value => self::Motoclub->name
        ];
    }

    public function getDatabaseConnection(): string
    {
        return match ($this) {
            self::Solidy => 'ileva_solidy',
            self::Nova => 'ileva_nova',
            self::Motoclub => 'ileva_motoclub',
        };
    }

    public function getApiToken(): string
    {
        return match ($this) {
            self::Solidy => config('ileva_solidy.token'),
            self::Nova => config('ileva_nova.token'),
            self::Motoclub => config('ileva_motoclub.token'),
        };
    }
}
