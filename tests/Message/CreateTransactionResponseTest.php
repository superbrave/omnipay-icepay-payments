<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\IcepayPayments\AbstractTestCase;

/**
 * Class CreateTransactionResponseTest.
 */
class CreateTransactionResponseTest extends AbstractTestCase
{
    /**
     * @var ResponseInterface
     */
    private $response;

    protected function setUp(): void
    {
        $this->response = $this->getMockBuilder(RequestInterface::class);
    }
}
