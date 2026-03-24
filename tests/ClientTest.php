<?php

namespace Hisend\Tests;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use Hisend\Hisend;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testGetDomainsHasCorrectHeadersAndDecodesJson()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode([['id' => 1, 'name' => 'example.com']]))
        ]);

        $container = [];
        $history = Middleware::history($container);

        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push($history);

        $client = new Hisend('test-api-key', [
            'guzzle' => ['handler' => $handlerStack]
        ]);

        $domains = $client->domains->list();

        $this->assertCount(1, $domains);
        $this->assertEquals('example.com', $domains[0]['name']);
        
        // Assert the request was made correctly
        $this->assertCount(1, $container);
        $transaction = $container[0];
        $request = $transaction['request'];

        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('https://api.hisend.app/v1/domains', (string) $request->getUri());
        
        $authHeader = $request->getHeaderLine('Authorization');
        $this->assertEquals('Bearer test-api-key', $authHeader);

        $contentTypeHeader = $request->getHeaderLine('Content-Type');
        $this->assertEquals('application/json', $contentTypeHeader);
    }
}
