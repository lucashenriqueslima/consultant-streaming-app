<?php

namespace App\Services\Ileva;

use App\DTOs\IlevaConsultantDTO;
use App\Enums\Association;
use App\Services\Ileva\Ileva;
use App\Traits\HttpHelper;
use Illuminate\Support\Facades\Log;

class RegisterConsultant extends Ileva
{
    use HttpHelper;
    private const ENDPOINT_CONSULTANT_REGISTRATION = '/consultor';

    //invoke
    public function execute(IlevaConsultantDTO $ilevaConsultantDTO): mixed
    {
        try {
            $response = Ileva::withHeaders($this->getDefaultHeaders([
                'access_token' => Association::from($ilevaConsultantDTO->association)->getApiToken(),
            ]))
                ->post(self::buildUrl(self::ENDPOINT_CONSULTANT_REGISTRATION), $ilevaConsultantDTO->toArray());

            if ($response->failed()) {
                Log::error('Falha ao criar consultor no iLeva.', $response->json());
                throw new \Exception($response->json()['message']);
            }

            Log::info('Consultor criado com sucesso no iLeva.', $response->json());
            return $response;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
