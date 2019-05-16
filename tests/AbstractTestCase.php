<?php

namespace Omnipay\IcepayPayments;

use Http\Mock\Client as MockClient;
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
     * @var MockClient
     */
    protected $mockClient;

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
        $this->mockClient = new MockClient();
        $this->httpClient = new Client($this->mockClient);
        $this->httpRequest = new Request();
    }
}
