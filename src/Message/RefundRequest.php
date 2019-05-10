<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * The request for refunding at Icepay.
 */
class RefundRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {
        // TODO: Implement sendData() method.
    }
}
