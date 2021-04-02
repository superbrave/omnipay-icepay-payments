<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\FetchTransactionRequest;
use Omnipay\IcepayPayments\Message\FetchTransactionResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

class FetchTransactionResponseTest extends AbstractTestCase
{
    /**
     * @var FetchTransactionRequest
     */
    private $fetchTransactionRequest;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->fetchTransactionRequest = new FetchTransactionRequest($this->httpClient, $this->httpRequest);
    }

    /**
     * Tests if {@see FetchTransactionResponse::isSuccessful()} will return true with the expected json success response.
     */
    public function testFetchTransactionResponseIsSuccessful(): void
    {
        $response = new FetchTransactionResponse(
            $this->fetchTransactionRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/FetchTransactionResponseSuccess.json')
        );

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * Tests if {@see FetchTransactionResponse::isCancelled()} will return not true with the expected json success
     * response.
     */
    public function testFetchTransactionResponseIsNotCancelled(): void
    {
        $response = new FetchTransactionResponse(
            $this->fetchTransactionRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/FetchTransactionResponseSuccess.json')
        );

        $this->assertNotTrue($response->isCancelled());
    }

    /**
     * Tests if {@see FetchTransactionResponse::getCode()} will return 200 with the expected json success response.
     */
    public function testFetchTransactionResponseCanGetCodeWillReturn200(): void
    {
        $response = new FetchTransactionResponse(
            $this->fetchTransactionRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/FetchTransactionResponseSuccess.json')
        );

        $this->assertSame(200, $response->getCode());
    }

    /**
     * Tests if {@see FetchTransactionResponse::getTransactionReference()} will return the expected reference with the
     * expected json success response.
     */
    public function testFetchTransactionResponseCanGetTransactionReference(): void
    {
        $response = new FetchTransactionResponse(
            $this->fetchTransactionRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/FetchTransactionResponseSuccess.json')
        );

        $this->assertSame('ab4e4929-eb89-4886-8903-acfe009e1e0f', $response->getTransactionReference());
    }

    /**
     * Tests if {@see FetchTransactionResponse::isCancelled()} will return true with the expected json cancelled
     * response.
     */
    public function testFetchTransactionResponseIsCancelled(): void
    {
        $response = new FetchTransactionResponse(
            $this->fetchTransactionRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/FetchTransactionResponseCancelled.json')
        );

        $this->assertTrue($response->isCancelled());
    }

    /**
     * Tests if {@see FetchTransactionResponse::isCancelled()} will return true with the expected json failed response
     * and expected postback data.
     */
    public function testFetchTransactionResponseWillFallbackOnPostBackDataWhenTransactionIsCancelled(): void
    {
        $this->httpRequest->query->set('statusCode', 'CANCELLED');

        $fetchTransactionRequest = new FetchTransactionRequest($this->httpClient, $this->httpRequest);

        $response = new FetchTransactionResponse(
            $fetchTransactionRequest,
            404,
            []
        );

        $this->assertTrue($response->isCancelled());
    }

    /**
     * Convert the json response body file into an array.
     *
     * @param string $fileName
     *
     * @return array
     */
    private function convertJsonResponseBodyToArray(string $fileName): array
    {
        return json_decode(file_get_contents($fileName), true);
    }
}
