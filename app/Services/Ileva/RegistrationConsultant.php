<?php
namespace App\Services\Ileva;

use App\Services\Ileva\Ileva;

class RegistrationConsultant extends Ileva
{
    private const ENDPOINT_CONSULTANT_REGISTRATION = '/...';

    public function registerConsultant($dadosCadastro) {
        $corpoRequisicao = $this->dataRequestRegistrationConsultant($dadosCadastro);
        $tokenByAssociation = $this->tokenByNameMembership($dadosCadastro['nome_associacao']);
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $tokenByAssociation
        ];

        $response = Ileva::withHeaders($headers)
            ->post(self::buildUrl(self::ENDPOINT_CONSULTANT_REGISTRATION), $corpoRequisicao);

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
