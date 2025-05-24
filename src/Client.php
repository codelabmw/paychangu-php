<?php

namespace Codelabmw\Paychangu;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class Client
{
    /** @var array<string, string> */
    private $headers = [];

    /**
     * Creates a new instance of the Client class.
     *
     * @param string $secret The secret key for authentication.
     * @param GuzzleClient|ClientInterface $httpClient The HTTP client to use for making requests.
     */
    public function __construct(
        private readonly string $secret,
        private readonly string $baseUrl = 'https://api.paychangu.com',
        private readonly GuzzleClient|ClientInterface $httpClient = new GuzzleClient()
    ) {
        $this->headers = [
            'Authorization' => 'Bearer ' . $this->secret,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Sends a GET request to the specified path.
     *
     * @param string $path The path to send the request to.
     * @return ResponseInterface The response from the server.
     */
    public function get(string $path): ResponseInterface
    {
        return $this->request('GET', $path);
    }

    /**
     * Sends a request to the specified path.
     *
     * @param string $method The HTTP method to use.
     * @param string $path The path to send the request to.
     * @return ResponseInterface The response from the server.
     */
    private function request(string $method, string $path): ResponseInterface
    {
        return $this->httpClient->request($method, $this->baseUrl . $path, [
            'headers' => $this->headers,
        ]);
    }
}