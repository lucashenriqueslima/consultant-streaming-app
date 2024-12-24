<?php

namespace App\Services\PuxaCapivara;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
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
    public function searchDataByDocument(string $cpf, int $timeout = 10): mixed
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . config('puxa_capivara.token'),
        ];

        try {
            Log::info(__FILE__ . " - Iniciando consulta no puxa", ['cpf' => $cpf, 'timeout' => $timeout]);

            $response = PuxaCapivara::withHeaders($headers)
                ->timeout($timeout)
                ->post(self::buildUrl(self::ENDPOINT_CONSULT), [
                    'cpf' => Str::replace(['.', '-', ' '], '', $cpf),
                ]);

            if ($response->failed()) {
                Log::error(__FILE__ . " - 1 Erro ao consultar no puxa", ['cpf' => $cpf, 'timeout' => $timeout, 'response' => $response->json()]);
                throw new \Exception($response->json()['message'] ?? 'Erro desconhecido.');
            }

            Log::info(__FILE__ . " - Consulta no puxa finalizada!", ['cpf' => $cpf, 'timeout' => $timeout, 'response' => $response->json()]);
            return $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error(__FILE__ . " - Timeout na req do puxa", ['cpf' => $cpf, 'timeout' => $timeout, 'error' => $e->getMessage()]);
            return [
                'status' => 'timeout',
                'message' => 'A conexão com o servidor falhou ou demorou muito para responder.',
            ];
        } catch (\Exception $e) {
            Log::error(__FILE__ . " - 2 Erro ao consultar no puxa", ['cpf' => $cpf, 'timeout' => $timeout, 'error' => $e->getMessage()]);
            throw new \Exception($e->getMessage());
        }
    }
}
