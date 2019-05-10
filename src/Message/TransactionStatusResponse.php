<?php

namespace Omnipay\IcepayPayments\Message;

/**
 * The response after getting the transaction status at Icepay.
 */
class TransactionStatusResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return parent::isSuccessful()
            && in_array($this->data['transactionStatusCode'], array(
                self::RESPONSE_STATUS_COMPLETED,
                self::RESPONSE_STATUS_SETTLED,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function isCancelled(): bool
    {
        return $this->data['transactionStatusCode'] === self::RESPONSE_STATUS_CANCELLED;
    }
}
