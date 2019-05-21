<?php

namespace Omnipay\IcepayPayments\Message;

use DateTime;
use GuzzleHttp\Psr7\Request;
use Omnipay\IcepayPayments\AbstractTestCase;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class CreateTransactionRequestTest.
 */
class CreateTransactionRequestTest extends AbstractTestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Creates a new CreateTransactionRequest instance.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateTransactionRequest($this->httpClient, $this->httpRequest);
        $this->request->setBaseUrl('https://www.superbrave.nl');
        $this->request->setSecretKey('NjRlYjM3MTctOGI1ZC00MDg4LTgxMDgtOTMyMjQ2NzVlNTM4');
        $this->request->setContractProfileId('64eb3717-8b5d-4088-8108-93224675e538');
    }

    /**
     * Tests if CreateTransactionRequest::getData validates the basic keys and returns an array of data.
     */
    public function testGetData(): void
    {
        $this->request->setAmountInteger(1337);
        $this->request->setCurrencyCode('EUR');
        $this->request->setReference('2fad9b1b-a2d3-455c-bc29-b79516fd3257');

        $this->request->setReturnUrl('https://www.superbrave.nl/return-url');
        $this->request->setCancelUrl('https://www.superbrave.nl/cancel-url');
        $this->request->setNotifyUrl('https://www.superbrave.nl/notify-url');
        $this->request->setPaymentMethod('IDEAL');
        $this->request->setIssuerCode('ABNAMRO');
        $this->request->setLanguageCode('nl');
        $this->request->setCountryCode('NL');
        $this->request->setTimestamp(new DateTime('2019-03-09T12:00:00'));

        $expectedData = [
            'Contract' => [
                'ContractProfileId' => '64eb3717-8b5d-4088-8108-93224675e538',
                'AmountInCents' => 1337,
                'CurrencyCode' => 'EUR',
                'Reference' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257',
            ],
            'Postback' => [
                'UrlCompleted' => 'https://www.superbrave.nl/return-url',
                'UrlError' => 'https://www.superbrave.nl/cancel-url',
                'UrlsNotify' => [
                    'https://www.superbrave.nl/notify-url',
                ],
            ],
            'IntegratorFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'ConsumerFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Fulfillment' => [
                'PaymentMethod' => 'IDEAL',
                'IssuerCode' => 'ABNAMRO',
                'AmountInCents' => 1337,
                'CurrencyCode' => 'EUR',
                'Timestamp' => '2019-03-09T12:00:00Z',
                'LanguageCode' => 'nl',
                'CountryCode' => 'NL',
                'Reference' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257',
            ],
        ];
        $this->assertEquals($expectedData, $this->request->getData());
    }

    /**
     * Tests if CreateTransactionRequest::sendData returns a CreateTransactionResponse.
     */
    public function testSendData(): void
    {
        $data = [
            'Contract' => [
                'ContractProfileId' => '64eb3717-8b5d-4088-8108-93224675e538',
                'AmountInCents' => 1337,
                'CurrencyCode' => 'EUR',
                'Reference' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257',
            ],
            'Postback' => [
                'UrlCompleted' => 'https://www.superbrave.nl/return-url',
                'UrlError' => 'https://www.superbrave.nl/cancel-url',
                'UrlsNotify' => [
                    'https://www.superbrave.nl/notify-url',
                ],
            ],
            'IntegratorFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'ConsumerFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Fulfillment' => [
                'PaymentMethod' => 'IDEAL',
                'IssuerCode' => 'ABNAMRO',
                'AmountInCents' => 1337,
                'CurrencyCode' => 'EUR',
                'Timestamp' => '2019-03-09T12:00:00Z',
                'LanguageCode' => 'nl',
                'CountryCode' => 'NL',
                'Reference' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257',
            ],
        ];
        $response = $this->request->sendData($data);

        $this->assertInstanceOf(CreateTransactionResponse::class, $response);

        $expectedRequest = new Request(
            SymfonyRequest::METHOD_POST,
            'https://www.superbrave.nl/contract/transaction'
        );

        $this->assertEquals($expectedRequest->getMethod(), $this->clientMock->getLastRequest()->getMethod());
        $this->assertEquals($expectedRequest->getUri(), $this->clientMock->getLastRequest()->getUri());
    }
}
