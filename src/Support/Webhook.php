<?php

declare(strict_types=1);

namespace Codelabmw\Paychangu\Support;

use Codelabmw\Paychangu\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Throwable;

/**
 * @codeCoverageIgnore
 */
final readonly class Webhook
{
    /**
     * Creates a new Webhook instance.
     *
     * @param  Client  $client  The client instance.
     */
    public function __construct(
        private Client $client
    ) {
        //
    }

    /**
     * Gets the current request.
     *
     * @return RequestInterface The current request.
     */
    public static function getCurrentRequest(): RequestInterface
    {
        if (! isset($_SERVER['REQUEST_METHOD'])) {
            throw new RuntimeException('REQUEST_METHOD is not set');
        }
        if (! isset($_SERVER['REQUEST_URI'])) {
            throw new RuntimeException('REQUEST_URI is not set');
        }
        if (! isset($_SERVER['SERVER_PROTOCOL'])) {
            throw new RuntimeException('SERVER_PROTOCOL is not set');
        }

        $method = $_SERVER['REQUEST_METHOD'];

        $scheme = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
        $port = $_SERVER['SERVER_PORT'] ?? null;
        $requestUri = $_SERVER['REQUEST_URI'];

        $uriString = $scheme.'://'.$host;

        if ($port && ! in_array((int) $port, [80, 443], true)) {
            $uriString .= ':'.$port;
        }

        $uriString .= $requestUri;

        $uri = new Uri($uriString);
        $version = $_SERVER['SERVER_PROTOCOL'];

        $validMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'HEAD', 'OPTIONS'];
        if (! in_array(mb_strtoupper((string) $method), $validMethods, true)) {
            throw new InvalidArgumentException('Invalid HTTP method: '.$method);
        }

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = str_replace(' ', '-', ucwords(mb_strtolower(str_replace('_', ' ', mb_substr($key, 5)))));
                $headers[$header] = [$value];
            }

            if ($key === 'CONTENT_TYPE') {
                $headers['Content-Type'] = [$value];
            }

            if ($key === 'CONTENT_LENGTH') {
                $headers['Content-Length'] = [$value];
            }
        }

        if (function_exists('apache_request_headers')) {
            $apacheHeaders = apache_request_headers();

            foreach ($apacheHeaders as $key => $value) {
                $header = str_replace(' ', '-', ucwords(mb_strtolower(str_replace('-', ' ', $key))));

                if (isset($headers[$header])) {
                    if (! in_array($value, $headers[$header], true)) {
                        $headers[$header][] = $value;
                    }
                } else {
                    $headers[$header] = [$value];
                }
            }
        }

        if (isset($_SERVER['Signature'])) {
            $headers['Signature'] = [$_SERVER['Signature']];
        }

        $body = '';

        try {
            $body = file_get_contents('php://input');
        } catch (Throwable $e) {
            throw new RuntimeException('Failed to read request body: '.$e->getMessage(), $e->getCode(), $e);
        }

        return new Request($method, $uri, $headers, $body, $version);
    }

    /**
     * Authenticates a webhook request if it originates from Paychangu.
     *
     * @param  RequestInterface  $request  The request to authenticate.
     * @return bool True if the request is authenticated, false otherwise.
     */
    public function authenticate(RequestInterface $request): bool
    {
        $secret = $this->client->secret();
        $signature = $request->getHeaderLine('Signature');
        $body = $request->getBody()->getContents();

        return hash_hmac('sha256', $body, $secret) === $signature;
    }
}
