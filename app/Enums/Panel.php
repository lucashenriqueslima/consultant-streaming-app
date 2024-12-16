<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;


enum Panel: string implements HasLabel
{
    case Consultant = 'consultant';
    case Candidate = 'candidate';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Consultant => 'Consultor',
            self::Candidate => 'Candidato',
        };
    }
}
