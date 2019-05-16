<?php

namespace Omnipay\IcepayPayments;

use Omnipay\Common\GatewayInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests the Icepay gateway.
 */
class GatewayTest extends TestCase
{
    /**
     * @var GatewayInterface
     */
    public $gateway;

    /**
     * Creates a new Gateway instance for testing.
     */
    protected function setUp(): void
    {
        $this->gateway = new Gateway();
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

            // request should have matching property, with correct value
            $request = $this->gateway->fetchTransaction();
            $this->assertSame($value, $request->$getter());
        }
    }

    /**
     * Returns the test cases for @see testInitializeSetsBaseUrlBasedOnTestMode.
     *
     * @return array
     */
    public function provideInitializeBaseUrlCases(): array
    {
        return array(
            array(
                array('testMode' => false),
                Gateway::API_BASE_URL,
            ),
            array(
                array('testMode' => true),
                Gateway::TEST_API_BASE_URL,
            ),
        );
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
