<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\IcepayPayments\AbstractTestCase;

/**
 * Class RefundResponseTest.
 */
class RefundResponseTest extends AbstractTestCase
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

        $this->request = new RefundRequest($this->httpClient, $this->httpRequest);
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/RefundSuccess.json');
        $response = new RefundResponse($this->request, json_decode($responseJsonBody, true));

        $expectedResponseBody = [
            'RefundId' => '7c9cb2f4-83ce-4b10-8d5c-de230181224f',
            'RefundStatusCode' => AbstractResponse::RESPONSE_STATUS_COMPLETED,
            'RefundStatusDetails' => '',
            'AcquirerRefundId' => '',
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($expectedResponseBody, $response->getData());
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/RefundFail.json');
        $response = new RefundResponse($this->request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isSuccessful());
    }
}
