<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

class CreateTransactionResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->getTransactionStatus() === self::TRANSACTION_STATUS_STARTED;
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect(): bool
    {
        return isset($this->data['acquirerRequestUri']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl(): string
    {
        return $this->data['acquirerRequestUri'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getTransactionStatus(): ?string
    {
        return $this->data['transactionStatusCode'] ?? null;
    }
}
