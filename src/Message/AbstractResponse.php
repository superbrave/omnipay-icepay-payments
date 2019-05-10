<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;

/**
 * Provides the base implementation for possible responses.
 */
abstract class AbstractResponse extends OmnipayAbstractResponse
{
    /**
     * @var string
     */
    public const RESPONSE_STATUS_CANCELLED = 'CANCELLED';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_CBACK = 'CBACK';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_COMPLETED = 'COMPLETED';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_EXPIRED = 'EXPIRED';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_FAILED = 'FAILED';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_PENDING = 'PENDING';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_REFUND = 'REFUND';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_REJECTED = 'REJECTED';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_SETTLED = 'SETTLED';

    /**
     * @var string
     */
    public const RESPONSE_STATUS_STARTED = 'STARTED';

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['contractId']);
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['transactionId'] ?? null;
    }
}
