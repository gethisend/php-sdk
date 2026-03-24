<?php

namespace Hisend\Resources;

use Hisend\Hisend;

class Routing
{
    private Hisend $client;

    public function __construct(Hisend $client)
    {
        $this->client = $client;
    }

    public function list(int $domainId): array
    {
        return $this->client->request('GET', "domains/{$domainId}/routing");
    }

    public function create(int $domainId, array $data): array
    {
        return $this->client->request('POST', "domains/{$domainId}/routing", $data);
    }

    public function update(int $domainId, int $routingId, array $data): array
    {
        return $this->client->request('PUT', "domains/{$domainId}/routing/{$routingId}", $data);
    }

    public function get(int $domainId, int $routingId): array
    {
        return $this->client->request('GET', "domains/{$domainId}/routing/{$routingId}");
    }

    public function delete(int $domainId, int $routingId): void
    {
        $this->client->request('DELETE', "domains/{$domainId}/routing/{$routingId}");
    }
}
