<?php

declare(strict_types=1);

namespace Deviantintegral\Har\Adapter\Symfony;

use Deviantintegral\Har\PostData;
use function GuzzleHttp\Psr7\stream_for;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class Request extends HttpFoundationRequest
{
    /**
     * @var \Deviantintegral\Har\Request
     */
    private $request;

    public function __construct(\Deviantintegral\Har\Request $request)
    {
        // Clone to preserve the immutability of this request.
        $this->request = clone $request;

        $this->initialize(
            $request->getQueryString(),
            [],
            [],
            [],
            [],
            [],
        );
    }

    /**
     * Returns a clone of the underlying HAR request.
     */
    public function getHarRequest(): \Deviantintegral\Har\Request
    {
        return clone $this->request;
    }

}
