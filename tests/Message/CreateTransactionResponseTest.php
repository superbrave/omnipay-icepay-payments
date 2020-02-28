<?php

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\AbstractResponse;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

/**
 * Class CreateTransactionResponseTest.
 */
class CreateTransactionResponseTest extends AbstractTestCase
{
    /**
     * @var CreateTransactionRequest
     */
    private $request;

    /**
     * Creates a new CreateTransactionRequest instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateTransactionRequest($this->httpClient, $this->httpRequest);
    }

    /**
     * Tests if CreateTransactionResponse::isSuccessful will return true with the given json response.
     */
    public function testIfResponseReturnsSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CreateTransactionSuccess.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $expectedResponseBody = [
            'contractId' => '0332ca56-90eb-4859-8d42-2c0898214069',
            'transactionId' => '6e9096aa-7ab8-4cb6-83f6-2f4847e5608a',
            'transactionStatusCode' => AbstractResponse::RESPONSE_STATUS_STARTED,
            'transactionStatusDetails' => '',
            'acquirerRequestUri' => 'https://www.superbrave.nl/redirect-url',
            'acquirerTransactionId' => '12345678',
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($expectedResponseBody, $response->getData());
    }

    /**
     * Tests if CreateTransactionResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CreateTransactionFailed.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * Tests if CreateTransactionResponse::isCancelled will return true from the json response.
     */
    public function testIfResponseIsCancelled(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CreateTransactionCancelled.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertTrue($response->isCancelled());
    }

    /**
     * Tests if CreateTransactionResponse::isCancelled will return true when we cannot create a transaction at Icepay.
     */
    public function testsIfResponseIsFailed(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CreateTransactionFailed.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertTrue($response->isCancelled());
    }

    /**
     * Tests if CreateTransactionResponse::isRedirect will return true from the json response.
     */
    public function testIfResponseIsARedirect(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CreateTransactionSuccess.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertTrue($response->isRedirect());
        $this->assertSame('https://www.superbrave.nl/redirect-url', $response->getRedirectUrl());
    }

    /**
     * Tests if CreateTransactionResponse::isRedirect will return false from the json response.
     */
    public function testIfResponseIsNotARedirect(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CreateTransactionFailed.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isRedirect());
        $this->assertEmpty($response->getRedirectUrl());
    }
}
