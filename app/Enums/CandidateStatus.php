<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum CandidateStatus: string implements HasLabel, HasColor, HasIcon
{
    case ACTIVE = 'active';
    case COMPLETED_LESSONS = 'completed-lessons';
    case ACCEPTED = 'accepted';
    case REFUSED_BY_CRIMINAL_HISTORY = 'refused-by-criminal-history';
    case REFUSED_ON_TEST = 'refused-on-test';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::COMPLETED_LESSONS => 'Aulas Assistidas',
            self::ACCEPTED => 'Aceito',
            self::REFUSED_BY_CRIMINAL_HISTORY => 'Recusado por Histórico Criminal',
            self::REFUSED_ON_TEST => 'Recusado no Teste',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'primary',
            self::COMPLETED_LESSONS => 'warning',
            self::ACCEPTED => 'success',
            self::REFUSED_BY_CRIMINAL_HISTORY => 'danger',
            self::REFUSED_ON_TEST => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-s-check-circle',
            self::COMPLETED_LESSONS => 'heroicon-s-check-circle',
            self::ACCEPTED => 'heroicon-s-check-circle',
            self::REFUSED_BY_CRIMINAL_HISTORY => 'heroicon-s-x-circle',
            self::REFUSED_ON_TEST => 'heroicon-s-x-circle',
        };
    }
}
