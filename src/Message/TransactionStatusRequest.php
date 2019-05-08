<?php

namespace Omnipay\IcepayPayments\Message;

use Psr\Http\Client\ClientInterface;

/**
 * Get status of transaction at Icepay, using REST api
 */
class TransactionStatusRequest extends AbstractRestRequest
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