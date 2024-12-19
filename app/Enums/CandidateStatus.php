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
    case PENDING_REGISTRATION = 'pending-registration';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::COMPLETED_LESSONS => 'Aulas Assistidas',
            self::ACCEPTED => 'Aceito',
            self::REFUSED_BY_CRIMINAL_HISTORY => 'Recusado por Histórico Criminal',
            self::REFUSED_ON_TEST => 'Recusado no Teste',
            self::PENDING_REGISTRATION => 'Cadastro Pendente',
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
            self::PENDING_REGISTRATION => 'warning',
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
            self::PENDING_REGISTRATION => 'heroicon-s-arrow-path',
        };
    }

    public static function getStatusMessage(string $status): string
    {
        return match ($status) {
            self::ACTIVE => 'Candidato está ativo e pode prosseguir.',
            self::COMPLETED_LESSONS => 'Candidato completou as lições.',
            self::ACCEPTED => 'Candidato foi aceito.',
            self::PENDING_REGISTRATION => 'Sua conta ainda está pendente, verifique a sua caixa de email.',
            self::REFUSED_BY_CRIMINAL_HISTORY => 'Seu cadastro não foi aceito no processo automático, para saber mais, entre em contato com um suporte..',
            default => 'Status desconhecido.',
        };
    }

    public static function isNotOneOf(array $statuses, string $status): bool
    {
        $statusValues = array_map(fn($statusObject) => $statusObject->value, $statuses);
        return !in_array($status, $statusValues);
    }
}
