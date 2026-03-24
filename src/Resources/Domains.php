<?php

namespace Hisend\Resources;

use Hisend\Hisend;

class Domains
{
    private Hisend $client;

    public function __construct(Hisend $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        return $this->client->request('GET', 'domains');
    }

    public function get(int $domainId): array
    {
        return $this->client->request('GET', "domains/{$domainId}");
    }

    public function verify(int $domainId): array
    {
        return $this->client->request('GET', "domains/{$domainId}/verify");
    }

    public function add(array $data): array
    {
        return $this->client->request('POST', 'domains', $data);
    }

    public function delete(int $domainId): void
    {
        $this->client->request('DELETE', "domains/{$domainId}");
    }
}
