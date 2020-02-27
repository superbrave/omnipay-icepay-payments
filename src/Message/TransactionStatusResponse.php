<?php

namespace Omnipay\IcepayPayments\Message;

/**
 * The response after getting the transaction status at Icepay.
 * For this response, we check the response (statusCode) of the payment transaction at Icepay.
 */
class TransactionStatusResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['statusCode']) && $this->data['statusCode'] === 200;
    }

    /**
     * {@inheritdoc}
     */
    public function isCancelled(): bool
    {
        return isset($this->data['status']) && $this->data['status'] === self::RESPONSE_STATUS_CANCELLED;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference(): ?string
    {
        return $this->request->getTransactionReference();
    }
}
