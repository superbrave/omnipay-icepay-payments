<?php

namespace Omnipay\IcepayPayments\Tests;

use Omnipay\Common\GatewayInterface;
use Omnipay\IcepayPayments\Gateway;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\RefundRequest;
use Omnipay\IcepayPayments\Message\TransactionStatusRequest;

/**
 * Tests the Icepay gateway.
 */
class GatewayTest extends AbstractTestCase
{
    /**
     * @var GatewayInterface
     */
    public $gateway;

    /**
     * @var array
     */
    private $options;

    /**
     * Creates a new Gateway instance for testing.
     */
    protected function setUp(): void
    {
        $this->gateway = new Gateway($this->httpClient, $this->httpRequest);
        $this->options = [
            'paymentMethod' => 'IDEAL',
            'amountInCents' => 1337,
            'currencyCode' => 'EUR',
            'languageCode' => 'nl',
            'countryCode' => 'NL',
            'issuerCode' => 'ABNAMRO',
            'reference' => '829c7998-6497-402c-a049-51801ba33662',
        ];
    }

    /**
     * Tests if Gateway::initialize sets the correct baseUrl based on the 'testMode' parameter.
     *
     * @dataProvider provideInitializeBaseUrlCases
     *
     * @param array  $parameters
     * @param string $expectedBaseUrl
     */
    public function testInitializeSetsBaseUrlBasedOnTestMode(array $parameters, string $expectedBaseUrl): void
    {
        $this->gateway->initialize($parameters);

        $this->assertSame($expectedBaseUrl, $this->gateway->getBaseUrl());
    }

    /**
     * Tests if default parameters on the gateway are also correctly set on the request instance
     * returned by Gateway::fetchTransaction.
     */
    public function testFetchTransactionParameters(): void
    {
        foreach ($this->gateway->getDefaultParameters() as $key => $default) {
            // set property on gateway
            $getter = 'get'.ucfirst($this->camelCase($key));
            $setter = 'set'.ucfirst($this->camelCase($key));
            $value = uniqid();
            $this->gateway->$setter($value);
            $this->assertSame($value, $this->gateway->$getter());

            // request should have matching property, with correct value
            $request = $this->gateway->fetchTransaction();
            $this->assertSame($this->gateway->$getter(), $request->$getter());
        }
    }

    /**
     * Tests if Gateway::authorize will return an instance of CreateTransactionRequest.
     */
    public function testAuthorize(): void
    {
        $request = $this->gateway->authorize($this->options);

        $this->assertInstanceOf(CreateTransactionRequest::class, $request);
    }

    /**
     * Tests if Gateway::completeAuthorize will return an instance of TransactionStatusRequest.
     */
    public function testCompleteAuthorize(): void
    {
        $request = $this->gateway->completeAuthorize($this->options);

        $this->assertInstanceOf(TransactionStatusRequest::class, $request);
    }

    /**
     * Tests if Gateway::capture will return an instance of TransactionStatusRequest.
     */
    public function testCapture(): void
    {
        $request = $this->gateway->capture($this->options);

        $this->assertInstanceOf(TransactionStatusRequest::class, $request);
    }

    /**
     * Tests if Gateway::refund will return an instance of RefundRequest.
     */
    public function testRefund(): void
    {
        $request = $this->gateway->refund($this->options);

        $this->assertInstanceOf(RefundRequest::class, $request);
    }

    /**
     * Returns the test cases for @see testInitializeSetsBaseUrlBasedOnTestMode.
     *
     * @return array
     */
    public function provideInitializeBaseUrlCases(): array
    {
        return [
            [
                ['testMode' => false],
                Gateway::API_BASE_URL,
            ],
            [
                ['testMode' => true],
                Gateway::TEST_API_BASE_URL,
            ],
        ];
    }

    /**
     * Converts a string to camel case.
     *
     * @param string $string
     *
     * @return string
     */
    public function camelCase($string): string
    {
        return preg_replace_callback(
            '/_([a-z])/',
            function ($match) {
                return strtoupper($match[1]);
            },
            $string
        );
    }
}
