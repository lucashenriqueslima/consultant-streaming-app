<?php

namespace App\Services\PuxaCapivara;

use Illuminate\Support\Facades\Http;

class PuxaCapivara extends Http
{
    private const BASE_URL = 'https://api.puxacapivara.com.br';

    /**
     * Appends the endpoint to the base API URL.
     *
     * @param  string  $endpoint
     * @return string
     */
    protected static function buildUrl(string $endpoint): string
    {
        return self::BASE_URL . $endpoint;
    }
}
