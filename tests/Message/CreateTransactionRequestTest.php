<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Tests\Message;

use DateTimeImmutable;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

class CreateTransactionRequestTest extends AbstractTestCase
{
    /**
     * @var CreateTransactionRequest
     */
    private $createTransactionRequest;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createTransactionRequest = new CreateTransactionRequest($this->httpClient, $this->httpRequest);
        $this->createTransactionRequest->setBaseUrl('https://www.superbrave.nl');
    }

    /**
     * Tests if {@see CreateTransactionRequest::getData()} will return the expected data that was given as parameters.
     */
    public function testCanGetData(): void
    {
        $this->createTransactionRequest->setContractProfileId('B4980F36-K45K-4DBF-BF6E-DG3941B2TG83');
        $this->createTransactionRequest->setSecretKey('hJ8nnHU7yLRzgHpEGoecnQrcOs5bTv3u35yPKTrWnnQ=');
        $this->createTransactionRequest->setAmountInteger(12345);
        $this->createTransactionRequest->setCurrencyCode('EUR');
        $this->createTransactionRequest->setTransactionId('5735c396-340f-4326-a71f-14910b146c7b');
        $this->createTransactionRequest->setPaymentMethod('CREDITCARD');
        $this->createTransactionRequest->setIssuerCode('CREDITCARD');
        $this->createTransactionRequest->setLanguageCode('NL');
        $this->createTransactionRequest->setCountryCode('NL');
        $this->createTransactionRequest->setTimestamp(new DateTimeImmutable('2021-04-01T12:00:00'));
        $this->createTransactionRequest->setDescription('Payment for order #1234567890');
        $this->createTransactionRequest->setReturnUrl('https://www.superbrave.nl/return-url');
        $this->createTransactionRequest->setCancelUrl('https://www.superbrave.nl/cancel-url');
        $this->createTransactionRequest->setNotifyUrl('https://www.superbrave.nl/notify-url');

        $expectedData = [
            'ConsumerFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Contract' => [
                'ContractProfileId' => 'B4980F36-K45K-4DBF-BF6E-DG3941B2TG83',
                'AmountInCents' => 12345,
                'CurrencyCode' => 'EUR',
                'Reference' => '5735c396-340f-4326-a71f-14910b146c7b',
            ],
            'Fulfillment' => [
                'PaymentMethod' => 'CREDITCARD',
                'IssuerCode' => 'CREDITCARD',
                'AmountInCents' => 12345,
                'CurrencyCode' => 'EUR',
                'Timestamp' => '2021-04-01T12:00:00Z',
                'LanguageCode' => 'NL',
                'CountryCode' => 'NL',
                'Reference' => '5735c396-340f-4326-a71f-14910b146c7b',
                'Description' => 'Payment for order #1234567890',
            ],
            'IntegratorFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Postback' => [
                'UrlCompleted' => 'https://www.superbrave.nl/return-url',
                'UrlError' => 'https://www.superbrave.nl/cancel-url',
                'UrlsNotify' => [
                    'https://www.superbrave.nl/notify-url',
                ],
            ],
        ];

        $this->assertSame($expectedData, $this->createTransactionRequest->getData());
    }

    /**
     * Tests if {@see CreateTransactionRequest::getData()} will return an {@see InvalidRequestException} when required
     * keys are missing.
     */
    public function testCannotGetDataWillReturnInvalidRequestExceptionWhenKeysAreMissing(): void
    {
        $this->expectException(InvalidRequestException::class);

        $this->createTransactionRequest->setAmountInteger(12345);
        $this->createTransactionRequest->setCurrencyCode('EUR');
        $this->createTransactionRequest->setTransactionId('5735c396-340f-4326-a71f-14910b146c7b');
        $this->createTransactionRequest->setPaymentMethod('CREDITCARD');
        $this->createTransactionRequest->setIssuerCode('CREDITCARD');
        $this->createTransactionRequest->setLanguageCode('NL');
        $this->createTransactionRequest->setCountryCode('NL');
        $this->createTransactionRequest->setTimestamp(new DateTimeImmutable('2021-04-01T12:00:00'));
        $this->createTransactionRequest->setDescription('Payment for order #1234567890');

        $this->createTransactionRequest->getData();
    }

    /**
     * Tests if {@see CreateTransactionRequest::send()} will return the {@see CreateTransactionResponse} instance.
     */
    public function testCanSendData(): void
    {
        $this->createTransactionRequest->setContractProfileId('B4980F36-K45K-4DBF-BF6E-DG3941B2TG83');
        $this->createTransactionRequest->setSecretKey('hJ8nnHU7yLRzgHpEGoecnQrcOs5bTv3u35yPKTrWnnQ=');

        $data = [
            'ConsumerFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Contract' => [
                'ContractProfileId' => 'eb60116a-e052-4cc1-b8c9-a7db8a9d3d14',
                'AmountInCents' => 12345,
                'CurrencyCode' => 'EUR',
                'Reference' => '5735c396-340f-4326-a71f-14910b146c7b',
            ],
            'Fulfillment' => [
                'PaymentMethod' => 'CREDITCARD',
                'IssuerCode' => 'CREDITCARD',
                'AmountInCents' => 12345,
                'CurrencyCode' => 'EUR',
                'Timestamp' => '2021-04-01T12:00:00Z',
                'LanguageCode' => 'NL',
                'CountryCode' => 'NL',
                'Reference' => '5735c396-340f-4326-a71f-14910b146c7b',
                'Description' => 'Payment for order #1234567890',
            ],
            'IntegratorFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Postback' => [
                'UrlCompleted' => 'https://www.superbrave.nl/return-url',
                'UrlError' => 'https://www.superbrave.nl/cancel-url',
                'UrlsNotify' => [
                    'https://www.superbrave.nl/notify-url',
                ],
            ],
        ];

        $response = $this->createTransactionRequest->sendData($data);

        $this->assertInstanceOf(CreateTransactionResponse::class, $response);

        $lastRequest = $this->httpClientMock->getLastRequest();

        $this->assertSame('POST', $lastRequest->getMethod());
        $this->assertSame('/api/contract/transaction', $lastRequest->getUri()->getPath());
        $this->assertSame('application/json', $lastRequest->getHeader('content-type')[0]);
        $this->assertSame('B4980F36-K45K-4DBF-BF6E-DG3941B2TG83', $lastRequest->getHeader('contractprofileid')[0]);
        $this->assertSame('PHW9EK9lpmz1lSsqLv2PzFXrbVKaUj1i+TiCA4goKpw=', $lastRequest->getHeader('checksum')[0]);
    }
}
