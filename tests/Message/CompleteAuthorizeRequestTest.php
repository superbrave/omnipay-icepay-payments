<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Tests\Message;

use Omnipay\IcepayPayments\Message\CompleteAuthorizeRequest;
use Omnipay\IcepayPayments\Message\CompleteAuthorizeResponse;
use Omnipay\IcepayPayments\Tests\AbstractTestCase;

class CompleteAuthorizeRequestTest extends AbstractTestCase
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
        $this->completeAuthorizeRequest->setBaseUrl('https://www.superbrave.nl');
        $this->completeAuthorizeRequest->setContractProfileId('B4980F36-K45K-4DBF-BF6E-DG3941B2TG83');
        $this->completeAuthorizeRequest->setSecretKey('hJ8nnHU7yLRzgHpEGoecnQrcOs5bTv3u35yPKTrWnnQ=');
        $this->completeAuthorizeRequest->setTransactionReference('ab4e4929-eb89-4886-8903-acfe009e1e0f');
    }

    /**
     * Tests if {@see CompleteAuthorizeRequest::sendData()} will return a {@see CompleteAuthorizeResponse} instance.
     */
    public function testCanSendData(): void
    {
        $response = $this->completeAuthorizeRequest->sendData([]);

        $this->assertInstanceOf(CompleteAuthorizeResponse::class, $response);

        $lastRequest = $this->httpClientMock->getLastRequest();

        $this->assertSame('GET', $lastRequest->getMethod());
        $this->assertSame('/api/transaction/ab4e4929-eb89-4886-8903-acfe009e1e0f', $lastRequest->getUri()->getPath());
        $this->assertSame('application/json', $lastRequest->getHeader('content-type')[0]);
        $this->assertSame('B4980F36-K45K-4DBF-BF6E-DG3941B2TG83', $lastRequest->getHeader('contractprofileid')[0]);
        $this->assertSame('63t9hBIwtZ8DEcJS0Hp49+DvrLS7ieFQi/sYYWeneQk=', $lastRequest->getHeader('checksum')[0]);
    }
}
