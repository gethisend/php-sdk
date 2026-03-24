<?php

namespace Hisend\Resources;

use Hisend\Hisend;

class Threads
{
    private Hisend $client;

    public function __construct(Hisend $client)
    {
        $this->client = $client;
    }

    public function list(): array
    {
        return $this->client->request('GET', 'threads');
    }

    public function getEmails(int $threadId): array
    {
        return $this->client->request('GET', "threads/{$threadId}/emails");
    }
}
