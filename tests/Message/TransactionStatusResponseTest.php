<?php

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\AbstractResponse;
use Omnipay\IcepayPayments\Message\TransactionStatusRequest;
use Omnipay\IcepayPayments\Message\TransactionStatusResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

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
        $this->request->setTransactionReference('7c9cb2f4-83ce-4b10-8d5c-de230181224f');
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsSuccessful(): void
    {
        $responseJsonBody = json_decode(file_get_contents(__DIR__.'/../Mocks/TransactionStatusSuccess.json'), true);
        $data = array_merge(
            $responseJsonBody,
            [
                'statusCode' => 200,
            ]
        );

        $response = new TransactionStatusResponse($this->request, $data);
        $expectedResponseBody = [
            'contractId' => 'NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4',
            'transactionId' => '7c9cb2f4-83ce-4b10-8d5c-de230181224f',
            'statusCode' => 200,
            'transactionStatusDetails' => '',
            'acquirerTransactionId' => '',
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($expectedResponseBody, $response->getData());
        $this->assertSame($expectedResponseBody['transactionId'], $response->getTransactionReference());
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionStatusFail.json');
        $response = new TransactionStatusResponse($this->request, json_decode($responseJsonBody, true));

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
