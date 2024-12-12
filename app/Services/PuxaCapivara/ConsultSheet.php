<?php
namespace App\Services\PuxaCapivara;

class ConsultSheet extends PuxaCapivara
{
    public const ENDPOINT_CONSULT = '/analyze';

    /**
     * Realiza uma consulta de ficha de uma pessoa pelo CPF.
     *
     * @param string $documento CPF da pessoa a ser consultada.
     *
     * @return array
     *      status: true se a consulta foi feita com sucesso, false caso contrario.
     *      mensagem: mensagem de erro ou os dados da pessoa caso tenha sido encontrado.
     *      detalhes: detalhes da consulta. Caso tenha ocorrido um erro, este campo conter  o erro.
     */
    public function searchDataByDocument($documento)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . env('PUXA_CAPIVARA_TOKEN')
        ];

        try {
            $response = PuxaCapivara::withHeaders($headers)
                ->post(self::buildUrl(self::ENDPOINT_CONSULT), [
                    'cpf' => $documento
                ]);

            if ($response->failed()) {
                return [
                    'status' => false,
                    'mensagem' => 'Erro ao consultar ficha. Verifique os dados fornecidos.',
                    'detalhes' => json_decode($response->body(), true)
                ];
            }

            return [
                'status' => true,
                'mensagem' => json_decode($response->body(), true)
            ];
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'mensagem' => 'Ocorreu um erro inesperado ao consultar a ficha.',
                'detalhes' => $exception->getMessage(),
            ];
        }
    }
}
