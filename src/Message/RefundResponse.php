<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

/**
 * Do note: refunds implementation has not been tested.
 */
class RefundResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->getTransactionStatus() === self::TRANSACTION_STATUS_COMPLETED;
    }

    /**
     * {@inheritdoc}
     */
    public function isCancelled(): bool
    {
        return $this->getTransactionStatus() === self::TRANSACTION_STATUS_CANCELLED;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTransactionStatus(): ?string
    {
        return $this->data['refundStatusCode'] ?? null;
    }
}
