<?php

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\AbstractResponse;
use Omnipay\IcepayPayments\Message\CompleteAuthoriseAndCaptureRequest;
use Omnipay\IcepayPayments\Message\CompleteAuthoriseAndCaptureResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

/**
 * Class CompleteAuthoriseAndCaptureResponseTest.
 */
class CompleteAuthoriseAndCaptureResponseTest extends AbstractTestCase
{
    /**
     * @var CompleteAuthoriseAndCaptureRequest
     */
    private $request;

    /**
     * Creates a new CompleteAuthoriseAndCaptureRequest instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CompleteAuthoriseAndCaptureRequest($this->httpClient, $this->httpRequest);
        $this->request->setTransactionReference('7c9cb2f4-83ce-4b10-8d5c-de230181224f');
    }

    /**
     * Tests if CompleteAuthoriseAndCaptureResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsSuccessful(): void
    {
        $responseJsonBody = json_decode(file_get_contents(__DIR__.'/../Mocks/CompleteAuthoriseAndCaptureSuccess.json'), true);
        $data = array_merge(
            $responseJsonBody,
            [
                'statusCode' => 200,
            ]
        );

        $response = new CompleteAuthoriseAndCaptureResponse($this->request, $data);
        $expectedResponseBody = [
            'contractId' => 'NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4',
            'transactionId' => '7c9cb2f4-83ce-4b10-8d5c-de230181224f',
            'status' => 'SETTLED',
            'transactionStatusDetails' => '',
            'acquirerTransactionId' => '',
            'statusCode' => 200,
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($expectedResponseBody, $response->getData());
        $this->assertSame($expectedResponseBody['transactionId'], $response->getTransactionReference());
    }

    /**
     * Tests if CompleteAuthoriseAndCaptureResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CompleteAuthoriseAndCaptureFailed.json');
        $response = new CompleteAuthoriseAndCaptureResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * Tests if CompleteAuthoriseAndCapture::isCancelled will return true with the given json response.
     */
    public function testResponseReturnsCancelled(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CompleteAuthoriseAndCaptureCancelled.json');
        $response = new CompleteAuthoriseAndCaptureResponse($this->request, json_decode($responseJsonBody, true));

        $expectedResponseBody = [
            'contractId' => 'NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4',
            'transactionId' => '7c9cb2f4-83ce-4b10-8d5c-de230181224f',
            'status' => AbstractResponse::RESPONSE_STATUS_CANCELLED,
            'transactionStatusDetails' => '',
            'acquirerTransactionId' => '',
        ];

        $this->assertTrue($response->isCancelled());
        $this->assertSame($expectedResponseBody, $response->getData());
    }
}
