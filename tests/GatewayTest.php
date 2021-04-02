<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Tests;

use Omnipay\Common\GatewayInterface;
use Omnipay\IcepayPayments\Gateway;
use Omnipay\IcepayPayments\Message\CaptureRequest;
use Omnipay\IcepayPayments\Message\CompleteAuthorizeRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\FetchTransactionRequest;
use Omnipay\IcepayPayments\Message\RefundRequest;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{
    /**
     * @var GatewayInterface
     */
    private $gateway;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->gateway = new Gateway();
    }

    /**
     * Tests if {@see Gateway::initialize()} will return an instance of {@see Gateway}.
     */
    public function testCanInitialize(): void
    {
        $this->assertInstanceOf(Gateway::class, $this->gateway->initialize([]));
    }

    /**
     * Tests if {@see Gateway::initialize()} will set the baseUrl for production.
     */
    public function testCanInitializeWillSetBaseUrlForProduction(): void
    {
        $this->gateway->initialize([]);

        $this->assertSame('https://interconnect.icepay.com', $this->gateway->getBaseUrl());
    }

    /**
     * Tests if {@see Gateway::initialize()} will set the baseUrl for test.
     */
    public function testCanInitializeWillSetBaseUrlForTest(): void
    {
        $this->gateway->initialize(['testMode' => true]);

        $this->assertSame('https://acc-interconnect.icepay.com', $this->gateway->getBaseUrl());
    }

    /**
     * Tests if {@see Gateway::authorize()} will return a {@see CreateTransactionRequest} instance.
     */
    public function testCanAuthorize(): void
    {
        $this->assertInstanceOf(CreateTransactionRequest::class, $this->gateway->authorize([]));
    }

    /**
     * Tests if {@see Gateway::completeAuthorize()} will return a {@see CompleteAuthorizeRequest} instance.
     */
    public function testCanCompleteAuthorize(): void
    {
        $this->assertInstanceOf(CompleteAuthorizeRequest::class, $this->gateway->completeAuthorize([]));
    }

    /**
     * Tests if {@see Gateway::capture()} will return a {@see CaptureRequest} instance.
     */
    public function testCanCapture(): void
    {
        $this->assertInstanceOf(CompleteAuthorizeRequest::class, $this->gateway->capture([]));
    }

    /**
     * Tests if {@see Gateway::refund()} will return a {@see RefundRequest} instance.
     */
    public function testCanRefund(): void
    {
        $this->assertInstanceOf(RefundRequest::class, $this->gateway->refund([]));
    }

    /**
     * Tests if {@see Gateway::fetchTransaction()} will return a {@see FetchTransactionRequest} instance.
     */
    public function testCanFetchTransaction(): void
    {
        $this->assertInstanceOf(FetchTransactionRequest::class, $this->gateway->fetchTransaction([]));
    }

    /**
     * Tests if {@see Gateway::getDefaultParameters()} will return the expected array of parameters.
     */
    public function testGetDefaultParameters(): void
    {
        $this->assertSame(
            [
                'baseUrl' => 'https://interconnect.icepay.com',
                'testMode' => false,
                'contractProfileId' => '',
                'secretKey' => '',
            ],
            $this->gateway->getDefaultParameters()
        );
    }
}
