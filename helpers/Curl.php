<?php

namespace App\Helpers;

class Curl
{
    /**
     * Send a request to the specified URL using cURL.
     *
     * @param string $url The URL to send the request to.
     * @param string $method The HTTP method to use ('GET', 'POST', etc.).
     * @param array $params The parameters to include in the request.
     * @param array $headers Optional headers to include in the request.
     * @return string|null The response from the request, or null on failure.
     */
    public static function request(string $url, string $method = 'GET', $params = [], array $headers = []): ?string
    {
        $curl = curl_init();

        // Set basic cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        // Set HTTP method and parameters
        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            is_string($params) ?
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params) :
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        } elseif ($method === 'PUT' || $method === 'DELETE') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            is_string($params) ?
                curl_setopt($curl, CURLOPT_POSTFIELDS, $params) :
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        } elseif ($method === 'GET' && !empty($params)) {
            $url .= '?' . http_build_query($params);
            curl_setopt($curl, CURLOPT_URL, $url);
        }

        // Set optional headers
        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        // Execute the cURL request and handle errors
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            curl_close($curl);

            return null;
        }

        curl_close($curl);

        return $response;
    }
}
