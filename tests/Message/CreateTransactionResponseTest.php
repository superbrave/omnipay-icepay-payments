<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\IcepayPayments\AbstractTestCase;

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
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionSuccess.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $expectedResponseBody = [
            'contractId' => 'NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4',
            'transactionId' => '64eb3717-8b5d-4088-8108-93224675e538',
            'transactionStatusCode' => AbstractResponse::RESPONSE_STATUS_STARTED,
            'transactionStatusDetails' => '',
            'acquirerRequestUri' => 'https://www.superbrave.nl/redirect-url',
            'acquirerTransactionId' => '',
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($expectedResponseBody, $response->getData());
    }

    /**
     * Tests if CreateTransactionResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionFail.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * Tests if CreateTransactionResponse::isRedirect will return true from the json response.
     */
    public function testIfResponseIsARedirect(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionSuccess.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertTrue($response->isRedirect());
        $this->assertSame('https://www.superbrave.nl/redirect-url', $response->getRedirectUrl());
    }

    /**
     * Tests if CreateTransactionResponse::isRedirect will return false from the json response.
     */
    public function testIfResponseIsNotARedirect(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionFail.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isRedirect());
        $this->assertEmpty($response->getRedirectUrl());
    }
}
