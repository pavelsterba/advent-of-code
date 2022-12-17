<?php

declare(strict_types=1);

namespace AdventOfCode\Utils;

use Symfony\Component\HttpClient\HttpClient as SymfonyHttpClient;

class HttpClient
{
    const HTTP_STATUS_OK = 200;

    private $client;

    public function __construct()
    {
        $this->client = SymfonyHttpClient::create([
            'headers' => [
                'User-Agent' => 'https://github.com/pavelsterba/advent-of-code by email@pavelsterba.com',
                'Cookie' => 'session=' . $_ENV["AOC_SESSION"],
            ]
        ]);
    }

    public function get(string $url): string
    {
        $response = $this->client->request('GET', $url);

        return $response->getContent();
    }
}
