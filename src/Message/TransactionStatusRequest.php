<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * The request for getting the transaction status at Icepay.
 */
class TransactionStatusRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {
        // TODO: Implement sendData() method.
    }
}
