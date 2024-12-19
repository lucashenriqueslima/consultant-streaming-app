<?php

namespace App\Services\PuxaCapivara;

use Illuminate\Support\Str;

class ConsultSheet extends PuxaCapivara
{
    public const ENDPOINT_CONSULT = '/analyze';

    /**
     * Realiza uma consulta de ficha de uma pessoa pelo CPF.
     *
     * @param string $cpf CPF da pessoa a ser consultada.
     *
     * @return array
     *      status: true se a consulta foi feita com sucesso, false caso contrario.
     *      mensagem: mensagem de erro ou os dados da pessoa caso tenha sido encontrado.
     *      detalhes: detalhes da consulta. Caso tenha ocorrido um erro, este campo conter  o erro.
     */
    public function searchDataByDocument(string $cpf, int $timeout = 15): mixed
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('puxa_capivara.token'),
        ];

        try {
            $response = PuxaCapivara::withHeaders($headers)
                ->timeout($timeout)
                ->post(self::buildUrl(self::ENDPOINT_CONSULT), [
                    'cpf' => Str::replace(['.', '-', ' '], '', $cpf),
                ]);

            if ($response->failed()) {
                throw new \Exception($response->json()['message'] ?? 'Erro desconhecido.');
            }

            return $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return [
                'status' => 'timeout',
                'message' => 'A conexão com o servidor falhou ou demorou muito para responder.',
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
