<?php

namespace Hisend;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Hisend\Exceptions\HisendException;
use Hisend\Resources\Domains;
use Hisend\Resources\Emails;
use Hisend\Resources\Routing;
use Hisend\Resources\Threads;

class Hisend
{
    private string $apiKey;
    private Client $httpClient;

    public Emails $emails;
    public Domains $domains;
    public Routing $routing;
    public Threads $threads;

    public function __construct(string $apiKey, array $options = [])
    {
        $this->apiKey = $apiKey;
        $baseUrl = $options['base_url'] ?? 'https://api.hisend.app/v1/';

        $this->httpClient = new Client(array_merge([
            'base_uri' => rtrim($baseUrl, '/') . '/',
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'http_errors' => false, // We will handle them manually
        ], $options['guzzle'] ?? []));

        $this->emails = new Emails($this);
        $this->domains = new Domains($this);
        $this->routing = new Routing($this);
        $this->threads = new Threads($this);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array|null $data
     * @return mixed
     * @throws HisendException
     */
    public function request(string $method, string $endpoint, ?array $data = null): mixed
    {
        $options = [];
        if ($data !== null) {
            $options['json'] = $data;
        }

        try {
            $response = $this->httpClient->request($method, ltrim($endpoint, '/'), $options);
            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();

            if ($statusCode < 200 || $statusCode >= 300) {
                $errorMessage = $body;
                $decoded = json_decode($body, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['message'])) {
                    $errorMessage = $decoded['message'];
                } elseif (json_last_error() === JSON_ERROR_NONE && isset($decoded['error'])) {
                     $errorMessage = $decoded['error'];
                }

                throw new HisendException("API request failed: {$errorMessage}", $statusCode);
            }

            if (empty($body)) {
                return null;
            }

            return json_decode($body, true);
        } catch (RequestException $e) {
            throw new HisendException("HTTP Request failed: " . $e->getMessage(), null, $e);
        }
    }
}
