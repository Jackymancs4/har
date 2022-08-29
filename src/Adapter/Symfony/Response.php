<?php

declare(strict_types=1);

namespace Deviantintegral\Har\Adapter\Psr7;

use Deviantintegral\Har\Content;
use function GuzzleHttp\Psr7\stream_for;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

final class Response extends HttpFoundationResponse
{
    /**
     * @var \Deviantintegral\Har\Response
     */
    private $response;

    /**
     * Response constructor.
     */
    public function __construct(\Deviantintegral\Har\Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return \Deviantintegral\Har\Response
     */
    public function getHarResponse(): \Deviantintegral\Har\Response
    {
        return clone $this->response;
    }
}
