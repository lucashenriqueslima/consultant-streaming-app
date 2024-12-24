<?php

namespace App\Services\Ileva;

use App\Enums\Association;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class Ileva extends Http
{
    protected const BASE_URL = 'https://api-integracao.ileva.com.br';

    public const ASSOCIATION_SOLIDY = 'solidy';
    public const ASSOCIATION_NOVA = 'nova';
    public const ASSOCIATION_MOTOCLUB = 'motoclube';

    public const ASSOCIATE_ACTIVE = 1;
    public const ASSOCIATE_PENDING = 0;
    public const ASSOCIATE_COMMISSIONED = true;
    public const ASSOCIATE_NOT_COMMISSIONED = false;

    public function dataRequestRegistrationConsultant($dataRegistration)
    {
        try {
            return [
                "status" => $dataRegistration['status'],
                "nome" => $dataRegistration['name'],
                "email" => $dataRegistration['email'],
                "telefone" => $dataRegistration['phone'],
                "cpf" => $dataRegistration['cpf'],
                "cod_equipe" => $dataRegistration['team_code'],
                "cod_estado" => $dataRegistration['state_code'],
                "cod_cidade" => $dataRegistration['city_code'],
                "data_nascimento" => $dataRegistration['birth_date'],
                "rua" => $dataRegistration['address'],
                "numero" => $dataRegistration['number'],
                "complemento" => $dataRegistration['complement'],
                "bairro" => $dataRegistration['neighborhood'],
                "cep" => $dataRegistration['zipcode'],
                "banco" => $dataRegistration['bank'],
                "agencia" => $dataRegistration['agency'],
                "operacao" => $dataRegistration['operation'],
                "conta" => $dataRegistration['account_number'],
                "recebe_comissao" => $dataRegistration['commission'],
                "tipo_pessoa" => $dataRegistration['person_type'],
                "razao_social" => $dataRegistration['company_name'],
                "representante" => $dataRegistration['representative']
            ];
        } catch (\Throwable $th) {
            throw new InvalidArgumentException($th->getMessage());
        }
    }

    protected static function buildUrl($endpoint)
    {
        return self::BASE_URL . $endpoint;
    }
}
