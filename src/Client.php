<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final readonly class Client
{
    /** @var array<string, string> */
    private array $headers;

    /**
     * Creates a new instance of the Client class.
     *
     * @param  string  $secret  The secret key for authentication.
     * @param  ClientInterface  $httpClient  The HTTP client to use for making requests.
     */
    public function __construct(
        private string $secret,
        private string $baseUrl = 'https://api.paychangu.com',
        private ClientInterface $httpClient = new GuzzleClient()
    ) {
        $this->headers = [
            'Authorization' => 'Bearer '.$this->secret,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Sends a GET request to the specified path.
     *
     * @param  string  $path  The path to send the request to.
     * @return ResponseInterface The response from the server.
     */
    public function get(string $path): ResponseInterface
    {
        return $this->request('GET', $path);
    }

    /**
     * Sends a POST request to the specified path.
     *
     * @param  string  $path  The path to send the request to.
     * @param  array<string, mixed>  $data  The data to send with the request.
     * @return ResponseInterface The response from the server.
     */
    public function post(string $path, array $data): ResponseInterface
    {
        return $this->request('POST', $path, $data);
    }

    /**
     * Gets the current secret.
     *
     * @return string The current secret.
     */
    public function secret(): string
    {
        return $this->secret;
    }

    /**
     * Sends a request to the specified path.
     *
     * @param  string  $method  The HTTP method to use.
     * @param  string  $path  The path to send the request to.
     * @param  array<string, mixed>  $data  The options to pass to the request.
     * @return ResponseInterface The response from the server.
     */
    private function request(string $method, string $path, array $data = []): ResponseInterface
    {
        return $this->httpClient->sendRequest(new Request(
            $method,
            $this->baseUrl.$path,
            $this->headers,
            (string) json_encode($data)
        ));
    }
}
