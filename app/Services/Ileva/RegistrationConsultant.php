<?php
namespace App\Services\Ileva;

use App\Services\Ileva\Ileva;

class RegistrationConsultant extends Ileva
{
    private const ENDPOINT_CONSULTANT_REGISTRATION = '/consultor';

    public function registerConsultant($dataRegistration) {
        $this->validateArgumentsRegistrationConsultant($dataRegistration);

        $bodyRequest = $this->dataRequestRegistrationConsultant($dataRegistration);
        $tokenByAssociation = $this->tokenByNameMembership($dataRegistration['name_association']);
        $headers = [
            'Content-Type' => 'application/json',
            'access_token' => $tokenByAssociation
        ];

        $response = Ileva::withHeaders($headers)
            ->post(self::buildUrl(self::ENDPOINT_CONSULTANT_REGISTRATION), $bodyRequest);

        if ($response->failed()) {
            return [
                'status' => false,
                'mensagem' => 'Erro ao cadastrar consultor. Verifique os dados fornecidos.',
                'detalhes' => json_decode($response->body(), true)
            ];
        }

        return [
            'status' => true,
            'mensagem' => 'Consultor cadastrado com sucesso.',
            'detalhes' => json_decode($response->body(), true)
        ];
    }
}
