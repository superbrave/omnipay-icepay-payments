<?php

namespace Omnipay\IcepayPayments\Tests;

use Http\Mock\Client as ClientMock;
use Omnipay\Common\Http\Client;
use Omnipay\Common\Http\ClientInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Prepares the client and request for the tests.
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @var ClientMock
     */
    protected $clientMock;

    /**
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * @var Request
     */
    protected $httpRequest;

    /**
     * Creates a client and request for the tests.
     */
    protected function setUp(): void
    {
        $this->clientMock = new ClientMock();
        $this->httpClient = new Client($this->clientMock);
        $this->httpRequest = new Request();
    }
}
