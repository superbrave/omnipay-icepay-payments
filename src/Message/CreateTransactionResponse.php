<?php

namespace Omnipay\IcepayPayments\Message;

/**
 * The response after creating a transaction at Icepay.
 */
class CreateTransactionResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private const RESPONSE_STATUS_CANCELLED = 'CANCELLED';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_CBACK = 'CBACK';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_COMPLETED = 'COMPLETED';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_EXPIRED = 'EXPIRED';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_FAILED = 'FAILED';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_PENDING = 'PENDING';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_REFUND = 'REFUND';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_REJECTED = 'REJECTED';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_SETTLED = 'SETTLED';

    /**
     * @var string
     */
    private const RESPONSE_STATUS_STARTED = 'STARTED';

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return parent::isSuccessful()
            && isset($this->data['transactionId'])
            && in_array($this->data['transactionStatusCode'], array(
                self::RESPONSE_STATUS_STARTED,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect(): bool
    {
        return $this->data['acquirerRequestUri'] ?? false;
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
    public function getTransactionReference(): ?string
    {
        return $this->data['transactionId'] ?? null;
    }
}
