<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

class CreateTransactionResponseTest extends AbstractTestCase
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
    }

    /**
     * Tests if {@see CreateTransactionResponse::isSuccessful()} will return true with the given json success response.
     */
    public function testCreatedTransactionResponseIsSuccessful(): void
    {
        $response = new CreateTransactionResponse(
            $this->createTransactionRequest,
            json_decode(file_get_contents(__DIR__.'/../Mock/CreateTransactionResponseSuccess.json'), true)
        );

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * Tests if {@see CreateTransactionResponse::isRedirect()} will return true with the given json success response.
     */
    public function testCreatedTransactionResponseWillRedirect(): void
    {
        $response = new CreateTransactionResponse(
            $this->createTransactionRequest,
            json_decode(file_get_contents(__DIR__.'/../Mock/CreateTransactionResponseSuccess.json'), true)
        );

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Tests if {@see CreateTransactionResponse::getRedirectUrl()} will return a redirect url with the given json
     * success response.
     */
    public function testCreatedTransactionResponseWillReturnRedirectUrl(): void
    {
        $response = new CreateTransactionResponse(
            $this->createTransactionRequest,
            json_decode(file_get_contents(__DIR__.'/../Mock/CreateTransactionResponseSuccess.json'), true)
        );

        $this->assertSame(
            'https://acc-interconnect.icepay.com/iscreditcard/api/payments/creditcard/ab4e4929-eb89-4886-8903-acfe009e1e0f',
            $response->getRedirectUrl()
        );
    }

    /**
     * Tests if {@see CreateTransactionResponse::isSuccessful()} will return false with the given json failed response.
     */
    public function testFailedCreatedTransactionResponseIsNotSuccessful(): void
    {
        $response = new CreateTransactionResponse(
            $this->createTransactionRequest,
            json_decode(file_get_contents(__DIR__.'/../Mock/CreateTransactionResponseFailed.json'), true)
        );

        $this->assertNotTrue($response->isSuccessful());
    }

    /**
     * Tests if {@see CreateTransactionResponse::isRedirect()} will return false with the given json failed response.
     */
    public function testFailedCreatedTransactionResponseIsARedirect(): void
    {
        $response = new CreateTransactionResponse(
            $this->createTransactionRequest,
            json_decode(file_get_contents(__DIR__.'/../Mock/CreateTransactionResponseFailed.json'), true)
        );

        $this->assertNotTrue($response->isRedirect());
    }

    /**
     * Tests if {@see CreateTransactionResponse::getRedirectUrl()} will return empty with the given json failed response.
     */
    public function testFailedCreatedTransactionResponseWillReturnEmptyRedirectUrl(): void
    {
        $response = new CreateTransactionResponse(
            $this->createTransactionRequest,
            json_decode(file_get_contents(__DIR__.'/../Mock/CreateTransactionResponseFailed.json'), true)
        );

        $this->assertNotTrue($response->getRedirectUrl());
    }
}
