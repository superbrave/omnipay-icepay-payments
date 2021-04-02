<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Tests;

use Http\Mock\Client as HttpClient;
use Omnipay\Common\Http\Client;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var HttpClient
     */
    protected $httpClientMock;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var Request
     */
    protected $httpRequest;

    /**
     * Set up a http client and request for the tests.
     */
    protected function setUp(): void
    {
        $this->httpClientMock = new HttpClient();
        $this->httpClient = new Client($this->httpClientMock);
        $this->httpRequest = new Request();
    }
}
