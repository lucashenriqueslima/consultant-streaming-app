<?php
namespace App\Services\Ileva;

use Illuminate\Support\Facades\Http;

class Ileva extends Http
{
    protected const BASE_URL = '...';
    protected const ASSOCIATION_SOLIDY = 'solidy';
    protected const ASSOCIATION_NOVA = 'nova';

    public function dataRequestRegistrationConsultant($dadosCadastro) {
        return [
            'email' => $dadosCadastro['email']
            // ...
        ];
    }

    protected static function buildUrl($endpoint) {
        return self::BASE_URL . $endpoint;
    }

    protected function tokenByNameMembership($nomeAssociacao) {
        return match ($nomeAssociacao) {
            self::ASSOCIATION_SOLIDY => env('ILEVA_SOLIDY_TOKEN_API'),
            self::ASSOCIATION_NOVA => env('ILEVA_NOVA_TOKEN_API'),
            default => env('ILEVA_SOLIDY_TOKEN_API'),
        };
    }
}
