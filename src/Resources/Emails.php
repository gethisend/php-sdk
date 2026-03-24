<?php

namespace Hisend\Resources;

use Hisend\Hisend;

class Emails
{
    private Hisend $client;

    public function __construct(Hisend $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        return $this->client->request('GET', 'emails');
    }

    public function get(int $emailId): array
    {
        return $this->client->request('GET', "emails/{$emailId}");
    }

    public function send(array $data): array
    {
        return $this->client->request('POST', 'emails', $data);
    }

    public function sendBatch(array $data): array
    {
        return $this->client->request('POST', 'emails/batch', $data);
    }
}
