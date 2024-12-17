<?php

namespace App\Traits;

trait HttpHelper
{
    public function getDefaultHeaders(array $aditionalHeaders = []): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            ...$aditionalHeaders
        ];
    }
}
