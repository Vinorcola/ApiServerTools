<?php

namespace Vinorcola\ApiServerTools;

use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends JsonResponse
{
    public function __construct(array $data = [], $error = null, int $status = 200, array $headers = [])
    {
        $payload = [
            'ok'     => $status >= 200 && $status < 400,
            'status' => $status,
        ];
        if ($error) {
            $payload['error'] = $error;
        }
        $payload['data'] = $data;

        parent::__construct($payload, $status, $headers);
    }
}
