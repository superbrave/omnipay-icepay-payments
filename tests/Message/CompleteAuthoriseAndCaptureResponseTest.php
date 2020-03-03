<?php

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\AbstractResponse;
use Omnipay\IcepayPayments\Message\CompleteAuthoriseAndCaptureRequest;
use Omnipay\IcepayPayments\Message\CompleteAuthoriseAndCaptureResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CompleteAuthoriseAndCaptureResponseTest.
 */
class CompleteAuthoriseAndCaptureResponseTest extends AbstractTestCase
{
    /**
     * Tests if CompleteAuthoriseAndCaptureResponse::isSuccessful will return true with the given json response.
     */
    public function testResponseReturnsSuccessful(): void
    {
        $request = new CompleteAuthoriseAndCaptureRequest($this->httpClient, new Request());
        $request->setTransactionReference('6e9096aa-7ab8-4cb6-83f6-2f4847e5608a');

        $responseJsonBody = json_decode(file_get_contents(__DIR__.'/../Mocks/CompleteAuthoriseAndCaptureSuccess.json'), true);
        $response = new CompleteAuthoriseAndCaptureResponse($request, $responseJsonBody);
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
     * Tests if CompleteAuthoriseAndCaptureResponse::isSuccessful will return false from the json response.
     */
    public function testIfResponseReturnNotSuccessful(): void
    {
        $request = new CompleteAuthoriseAndCaptureRequest($this->httpClient, new Request());
        $request->setTransactionReference('6e9096aa-7ab8-4cb6-83f6-2f4847e5608a');

        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CompleteAuthoriseAndCaptureFailed.json');
        $response = new CompleteAuthoriseAndCaptureResponse($request, json_decode($responseJsonBody, true));

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * Tests if CompleteAuthoriseAndCapture::isCancelled will return true with the given json response.
     */
    public function testResponseReturnsCancelled(): void
    {
        $httpRequest = new Request();
        $httpRequest->query->add([
            'amountInCents' => 2900,
            'checksum' => 'B409A55601CA364E7EFC702E783F3BC4004D068DB1151FD7963968E40F112027',
            'contractProfileId' => '0332ca56-90eb-4859-8d42-2c0898214069',
            'currencyCode' => 'EUR',
            'issuer' => 'MASTER',
            'paymentMethod' => 'CREDITCARD',
            'providerTransactionId' => '12345678',
            'reference' => '1234567-4e4583295b55440eb7301222841030df',
            'statusCode' => AbstractResponse::RESPONSE_STATUS_CANCELLED,
            'statusDetails' => '',
            'transactionId' => '6e9096aa-7ab8-4cb6-83f6-2f4847e5608a',
        ]);

        $request = new CompleteAuthoriseAndCaptureRequest($this->httpClient, $httpRequest);
        $request->setTransactionReference('6e9096aa-7ab8-4cb6-83f6-2f4847e5608a');

        $responseJsonBody = file_get_contents(__DIR__.'/../Mocks/CompleteAuthoriseAndCaptureCancelled.json');
        $response = new CompleteAuthoriseAndCaptureResponse($request, json_decode($responseJsonBody, true));

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
            'status' => AbstractResponse::RESPONSE_STATUS_CANCELLED,
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
        $this->assertSame(
            $response->getRequest()->getHttpRequest()->get('statusCode'),
            AbstractResponse::RESPONSE_STATUS_CANCELLED
        );
    }
}
