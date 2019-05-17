<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\IcepayPayments\AbstractTestCase;

/**
 * Class TransactionStatusResponseTest.
 */
class TransactionStatusResponseTest extends AbstractTestCase
{
    /**
     * @var TransactionStatusRequest
     */
    private $request;

    /**
     * Creates a new TransactionStatusRequest instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new TransactionStatusRequest($this->httpClient, $this->httpRequest);
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionStatusSuccess.json');
        $response = new TransactionStatusResponse($this->request, json_decode($responseJsonBody, true));

        $expectedResponseBody = [
            'contractId' => 'NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4',
            'transactionId' => '7c9cb2f4-83ce-4b10-8d5c-de230181224f',
            'statusCode' => AbstractResponse::RESPONSE_STATUS_COMPLETED,
            'transactionStatusDetails' => '',
            'acquirerTransactionId' => '',
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($expectedResponseBody, $response->getData());
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionStatusFail.json');
        $response = new CreateTransactionResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * Tests if TransactionStatusResponse::isCancelled will return true with the given json response.
     */
    public function testResponseReturnsCancelled(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionStatusCancelled.json');
        $response = new TransactionStatusResponse($this->request, json_decode($responseJsonBody, true));

        $expectedResponseBody = [
            'contractId' => 'NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4',
            'transactionId' => '7c9cb2f4-83ce-4b10-8d5c-de230181224f',
            'statusCode' => AbstractResponse::RESPONSE_STATUS_CANCELLED,
            'transactionStatusDetails' => '',
            'acquirerTransactionId' => '',
        ];

        $this->assertTrue($response->isCancelled());
        $this->assertSame($expectedResponseBody, $response->getData());
    }
}
