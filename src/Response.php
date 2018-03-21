<?php

namespace Vinorcola\ApiServerTools;

use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class Response extends JsonResponse
{
    public function __construct(array $data = [], $error = null, int $status = 200, array $headers = [])
    {
        $payload = [
            'ok'     => $status >= 200 && $status < 400,
            'status' => $status,
        ];
        if ($error) {
            if ($error instanceof InvalidInputException) {
                $payload['error'] = $error->getMessage();
                $payload['data'] = [
                    'errorDetails' => $error->getErrorMessages(),
                ];
            } elseif ($error instanceof Throwable) {
                $payload['error'] = $error->getMessage();
                $payload['data'] = [];
            } else {
                $payload['error'] = (string) $error;
                $payload['data'] = [];
            }
        } else {
            $payload['data'] = $data;
        }

        parent::__construct($payload, $status, $headers);
    }
}
