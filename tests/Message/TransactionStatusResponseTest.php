<?php

namespace Omnipay\IcepayPayments\Tests\Message;

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
        $this->request->setTransactionReference('6e9096aa-7ab8-4cb6-83f6-2f4847e5608a');
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsSuccessful(): void
    {
        $responseJsonBody = json_decode(file_get_contents(__DIR__.'/../Mocks/TransactionStatusSuccess.json'), true);

        $response = new TransactionStatusResponse($this->request, $responseJsonBody, 200);
        $expectedResponseBody = [
            'id' => '6e9096aa-7ab8-4cb6-83f6-2f4847e5608a',
            'lastUpdatedUtc' => '2020-02-28T12:35:28.31',
            'contractProfileId' => '0332ca56-90eb-4859-8d42-2c0898214069',
            'authorisationId' => '00000000-0000-0000-0000-000000000000',
            'paymentMethod' => 'CREDITCARD',
            'issuer' => 'MASTER',
            'currencyCode' => 'EUR',
            'amount' => 13.37,
            'reference' => '1234567-4e4583295b55440eb7301222841030df',
            'status' => 'COMPLETED',
            'requestTimestamp' => '2020-02-28T12:35:12.187',
            'acquirerId' => '69bbc2c0-315e-4958-ae60-3ee52636e379',
            'providerTransactionId' => '12345678',
            'paymentTime' => '2020-02-28T12:35:28.193',
            'paymentDetails' => [],
            'languageCode' => 'nl',
            'description' => '1234567-4e4583295b55440eb7301222841030df',
            'countryCode' => 'NL',
            'events' => [
                [
                    'type' => 'TransactionInitiated',
                    'dateTime' => '2020-02-28T12:35:12.1913677Z',
                ],
                [
                    'type' => 'TransactionStarted',
                    'dateTime' => '2020-02-28T12:35:12.9319302Z',
                ],
                [
                    'type' => 'TransactionCompleted',
                    'dateTime' => '2020-02-28T12:35:28.3092395Z',
                ],
            ],
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertSame($expectedResponseBody, $response->getData());
        $this->assertSame($expectedResponseBody['id'], $response->getTransactionReference());
    }

    /**
     * Tests if TransactionStatusResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionStatusFailed.json');
        $response = new TransactionStatusResponse($this->request, json_decode($responseJsonBody, true), 400);

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * Tests if TransactionStatusResponse::isCancelled will return true with the given json response.
     */
    public function testResponseReturnsCancelled(): void
    {
        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/TransactionStatusCancelled.json');
        $response = new TransactionStatusResponse($this->request, json_decode($responseJsonBody, true), 200);

        $expectedResponseBody = [
            'id' => '6e9096aa-7ab8-4cb6-83f6-2f4847e5608a',
            'lastUpdatedUtc' => '2020-02-28T12:35:28.31',
            'contractProfileId' => '0332ca56-90eb-4859-8d42-2c0898214069',
            'authorisationId' => '00000000-0000-0000-0000-000000000000',
            'paymentMethod' => 'CREDITCARD',
            'issuer' => 'MASTER',
            'currencyCode' => 'EUR',
            'amount' => 13.37,
            'reference' => '1234567-4e4583295b55440eb7301222841030df',
            'status' => 'CANCELLED',
            'requestTimestamp' => '2020-02-28T12:35:12.187',
            'acquirerId' => '69bbc2c0-315e-4958-ae60-3ee52636e379',
            'providerTransactionId' => '12345678',
            'paymentTime' => '2020-02-28T12:35:28.193',
            'paymentDetails' => [],
            'languageCode' => 'nl',
            'description' => '1234567-4e4583295b55440eb7301222841030df',
            'countryCode' => 'NL',
            'events' => [
                [
                    'type' => 'TransactionInitiated',
                    'dateTime' => '2020-02-28T12:35:12.1913677Z',
                ],
                [
                    'type' => 'TransactionStarted',
                    'dateTime' => '2020-02-28T12:35:12.9319302Z',
                ],
                [
                    'type' => 'TransactionCompleted',
                    'dateTime' => '2020-02-28T12:35:28.3092395Z',
                ],
            ],
        ];

        $this->assertTrue($response->isCancelled());
        $this->assertSame($expectedResponseBody, $response->getData());
    }
}
