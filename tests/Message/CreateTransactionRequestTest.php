<?php

namespace Omnipay\IcepayPayments\Tests\Message;

use DateTime;
use GuzzleHttp\Psr7\Request;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;
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
        $this->request->setTransactionId('2fad9b1b-a2d3-455c-bc29-b79516fd3257-random-uuid-hex');
        $this->request->setReference('2fad9b1b-a2d3-455c-bc29-b79516fd3257');
        $this->request->setReturnUrl('https://www.superbrave.nl/return-url');
        $this->request->setCancelUrl('https://www.superbrave.nl/cancel-url');
        $this->request->setNotifyUrl('https://www.superbrave.nl/notify-url');
        $this->request->setPaymentMethod('IDEAL');
        $this->request->setIssuerCode('ABNAMRO');
        $this->request->setLanguageCode('nl');
        $this->request->setCountryCode('NL');
        $this->request->setTimestamp(new DateTime('2019-03-09T12:00:00'));
        $this->request->setCity('Bree duh');
        $this->request->setStreet('Quite 18');
        $this->request->setPostalCode('4817 HX');
        $this->request->setDescription('2fad9b1b-a2d3-455c-bc29-b79516fd3257-random-uuid-hex');

        $expectedData = [
            'Contract' => [
                'ContractProfileId' => '64eb3717-8b5d-4088-8108-93224675e538',
                'AmountInCents' => 1337,
                'CurrencyCode' => 'EUR',
                'Reference' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257-random-uuid-hex',
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
                'Consumer' => [
                    'Address' => [
                        'CareOf' => null,
                        'City' => 'Bree duh',
                        'CountryCode' => 'NL',
                        'HouseNumber' => null,
                        'PostalCode' => '4817 HX',
                        'Street' => 'Quite 18',
                    ],
                    'Category' => 'Person',
                ],
                'Timestamp' => '2019-03-09T12:00:00Z',
                'LanguageCode' => 'nl',
                'CountryCode' => 'NL',
                'Order' => [
                    'OrderNumber' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257',
                    'CurrencyCode' => 'EUR',
                    'TotalGrossAmountCents' => 1337,
                    'TotalNetAmountCents' => 1337,
                ],
                'Reference' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257-random-uuid-hex',
                'Description' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257-random-uuid-hex',
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
            'Description' => '2fad9b1b-a2d3-455c-bc29-b79516fd3257-random-uuid-hex',
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
