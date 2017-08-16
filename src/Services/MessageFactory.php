<?php

namespace SonnyBlaine\Integrator\Services;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Message\MessageFactory as MessageFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class MessageFactory implements MessageFactoryInterface
{

    /**
     * Creates a new PSR-7 request.
     *
     * @param string $method
     * @param string|UriInterface $uri
     * @param array $headers
     * @param resource|string|StreamInterface|null $body
     * @param string $protocolVersion
     *
     * @return RequestInterface
     */
    public function createRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return new Request($method, $uri, $headers, $body, $protocolVersion);
    }

    /**
     * Creates a new PSR-7 response.
     *
     * @param int $statusCode
     * @param string|null $reasonPhrase
     * @param array $headers
     * @param resource|string|StreamInterface|null $body
     * @param string $protocolVersion
     *
     * @return ResponseInterface
     */
    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return new Response($statusCode, $headers, $body, $protocolVersion, $reasonPhrase);
    }
}