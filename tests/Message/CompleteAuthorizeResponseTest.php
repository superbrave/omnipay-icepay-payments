<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\CompleteAuthorizeRequest;
use Omnipay\IcepayPayments\Message\CompleteAuthorizeResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

class CompleteAuthorizeResponseTest extends AbstractTestCase
{
    /**
     * @var CompleteAuthorizeRequest
     */
    private $completeAuthorizeRequest;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->completeAuthorizeRequest = new CompleteAuthorizeRequest($this->httpClient, $this->httpRequest);
    }

    /**
     * Tests if {@see CompleteAuthorizeResponse::isSuccessful()} will return true with the expected json success
     * response.
     */
    public function testCompleteAuthorizeResponseIsSuccessful(): void
    {
        $response = new CompleteAuthorizeResponse(
            $this->completeAuthorizeRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/CompleteAuthorizeResponseSuccess.json')
        );

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * Tests if {@see CompleteAuthorizeResponse::isCancelled()} will return not true with the expected json success
     * response.
     */
    public function testCompleteAuthorizeResponseIsNotCancelled(): void
    {
        $response = new CompleteAuthorizeResponse(
            $this->completeAuthorizeRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/CompleteAuthorizeResponseSuccess.json')
        );

        $this->assertNotTrue($response->isCancelled());
    }

    /**
     * Tests if {@see CompleteAuthorizeResponse::getCode()} will return 200 with the expected json success response.
     */
    public function testCompleteAuthorizeResponseCanGetCodeWillReturn200(): void
    {
        $response = new CompleteAuthorizeResponse(
            $this->completeAuthorizeRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/CompleteAuthorizeResponseSuccess.json')
        );

        $this->assertSame(200, $response->getCode());
    }

    /**
     * Tests if {@see CompleteAuthorizeResponse::getTransactionReference()} will return the expected reference with the
     * expected json success response.
     */
    public function testCompleteAuthorizeResponseCanGetTransactionReference(): void
    {
        $response = new CompleteAuthorizeResponse(
            $this->completeAuthorizeRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/CompleteAuthorizeResponseSuccess.json')
        );

        $this->assertSame('ab4e4929-eb89-4886-8903-acfe009e1e0f', $response->getTransactionReference());
    }

    /**
     * Tests if {@see CompleteAuthorizeResponse::isCancelled()} will return true with the expected json cancelled
     * response.
     */
    public function testCompleteAuthorizeResponseIsCancelled(): void
    {
        $response = new CompleteAuthorizeResponse(
            $this->completeAuthorizeRequest,
            200,
            $this->convertJsonResponseBodyToArray(__DIR__.'/../Mock/CompleteAuthorizeResponseCancelled.json')
        );

        $this->assertTrue($response->isCancelled());
    }

    /**
     * Tests if {@see CompleteAuthorizeResponse::isCancelled()} will return true with the expected json failed response
     * and expected postback data.
     */
    public function testCompleteAuthorizeResponseWillFallbackOnPostBackDataWhenTransactionIsCancelled(): void
    {
        $this->httpRequest->query->set('statusCode', 'CANCELLED');

        $CompleteAuthorizeRequest = new CompleteAuthorizeRequest($this->httpClient, $this->httpRequest);

        $response = new CompleteAuthorizeResponse(
            $CompleteAuthorizeRequest,
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
