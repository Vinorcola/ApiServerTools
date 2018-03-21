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
                $data = [
                    'errorDetails' => $error->getErrorMessages(),
                ];
            } elseif ($error instanceof Throwable) {
                $payload['error'] = $error->getMessage();
                $data = [];
            } else {
                $payload['error'] = (string) $error;
                $data = [];
            }
        }
        $payload['data'] = (object) $data;

        parent::__construct($payload, $status, $headers);
    }
}
