<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Refunds for Icepay, using REST api
 */
class RefundRequest extends AbstractRestRequest
{
    /**
     * @inheritDoc
     */
    protected function runTransaction(
        ClientInterface $client,
        array $data
    ): Psr\Http\Message\ResponseInterface {
        // TODO: Implement runTransaction() method.
    }

    /**
     * @inheritDoc
     */
    protected function getResponseName(): string
    {
        // TODO: Implement getResponseName() method.
    }

}