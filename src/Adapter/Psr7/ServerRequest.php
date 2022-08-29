<?php

declare(strict_types=1);

namespace Deviantintegral\Har\Adapter\Psr7;

use Deviantintegral\Har\Cookie;
use Deviantintegral\Har\PostData;
use Exception;
use function GuzzleHttp\Psr7\stream_for;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest extends MessageBase implements ServerRequestInterface
{
    /**
     * @var \Deviantintegral\Har\Request
     */
    private $request;

    public function __construct(\Deviantintegral\Har\Request $request)
    {
        parent::__construct($request);

        // Clone to preserve the immutability of this request.
        $this->request = clone $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget()
    {
        return (string) $this->request->getUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function withRequestTarget($requestTarget)
    {
        $url = new Uri($requestTarget);
        if (!$url->getScheme() || !$url->getHost()) {
            throw new \LogicException(sprintf('%s must be an absolute-form target to use with this adapter.', $requestTarget));
        }

        $request = clone $this->request;
        $request->setUrl($url);

        return new static($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * {@inheritdoc}
     */
    public function withMethod($method)
    {
        if (!\is_string($method) || '' === $method) {
            throw new \InvalidArgumentException('Method must be a non-empty string.');
        }

        $request = clone $this->request;
        $request->setMethod($method);

        return new static($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getUri()
    {
        return $this->request->getUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $request = clone $this->request;
        $request->setUrl($uri);
        $return = new static($request);
        if (!$preserveHost && $host = $uri->getHost()) {
            $return = $return->withHeader('Host', $host);
        }

        return $return;
    }

    /**
     * Returns a clone of the underlying HAR request.
     */
    public function getHarRequest(): \Deviantintegral\Har\Request
    {
        return clone $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        $body = '';
        if ($this->request->hasPostData()) {
            $body = $this->request->getPostData()->getText();
        }

        return stream_for($body);
    }

    /**
     * {@inheritdoc}
     */
    public function withBody(StreamInterface $body)
    {
        $request = clone $this->request;
        $postData = new PostData();
        if ($request->hasPostData()) {
            $postData = $request->getPostData();
        }
        $postData->setText($body->getContents());
        $request->setPostData($postData);

        return new static($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getServerParams()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams()
    {
        return $this->request->getCookies();
    }

    /**
     * {@inheritdoc}
     */
    public function withCookieParams(array $cookies)
    {

        $request = clone $this->request;

        foreach ($cookies as $name => $value) {

            $harCookie = new Cookie();
            $harCookie->setName($name);
            $harCookie->setValue($value);

            $request->addCookie($harCookie);
        }

        return new static($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParams()
    {
        $queryParams = $this->request->getQueryString();
        $return = [];
        foreach ($queryParams as $queryParam) {
            $return[$queryParam->getName()][] = $queryParam->getValue();
        }

        return $return;

        return $this->request->getQueryString();
    }

    /**
     * {@inheritdoc}
     */
    public function withQueryParams(array $query)
    {
        throw new Exception('Not implemented');

        $request = clone $this->request;
        return new static($request);

    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFiles()
    {
        throw new Exception('Not implemented');

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        throw new Exception('Not implemented');

        $request = clone $this->request;
        return new static($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody()
    {
        throw new Exception('Not implemented');

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function withParsedBody($data)
    {
        throw new Exception('Not implemented');

        $request = clone $this->request;
        return new static($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        throw new Exception('Not implemented');

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name, $default = null)
    {
        throw new Exception('Not implemented');

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function withAttribute($name, $value)
    {
        throw new Exception('Not implemented');

        $request = clone $this->request;
        return new static($request);
    }

    /**
     * {@inheritdoc}
     */
    public function withoutAttribute($name)
    {
        throw new Exception('Not implemented');

        $request = clone $this->request;
        return new static($request);
    }
}
