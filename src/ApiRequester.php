<?php

namespace Vinorcola\ApiServerTools;

class ApiRequester
{
    /**
     * Make a request to an API.
     *
     * @param string     $method
     * @param string     $url
     * @param mixed|null $body
     * @param array      $headers
     * @return mixed
     */
    public static function request(string $method, string $url, $body = null, array $headers = [])
    {
        $connection = curl_init($url);
        curl_setopt($connection, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($connection, CURLOPT_HTTPHEADER, array_map(function ($key, $value) {
            return $key . ': ' . $value;
        }, array_keys($headers), $headers));
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        if ($body) {
            curl_setopt($connection, CURLOPT_POSTFIELDS, $body);
        }
        $rawResult = curl_exec($connection);
        curl_close($connection);

        return json_decode($rawResult, true);
    }
}
