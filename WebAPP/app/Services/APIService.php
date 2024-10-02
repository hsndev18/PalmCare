<?php

namespace App\Services;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\ClientException;

class APIService
{
    public function fetchDataToModel($file, $file_name)
    {
        try {
            $client = new Client();

            $response = $client->post("http://localhost:5000/analyze", [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => $file,
                        'filename' => $file_name,
                    ]
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'verify' => false, // Disable SSL verification
            ]);
            return $response = json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            // Logging the error in the log file
            Log::error("Error occurred in fetchDataToModel: " . $response->getBody());
            throw new Exception("Error occurred in fetchDataToModel: " . $response->getBody());
        }
    }
}
