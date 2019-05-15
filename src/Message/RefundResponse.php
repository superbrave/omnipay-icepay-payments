<?php

namespace Omnipay\IcepayPayments\Message;

/**
 * The response after telling Icepay to refund a transaction or order.
 */
class RefundResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return in_array(
            $this->data['RefundStatusCode'],
            [
                self::RESPONSE_STATUS_COMPLETED,
                self::RESPONSE_STATUS_REFUND,
            ]
        );
    }
}
