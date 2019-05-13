<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;

/**
 * Provides the base implementation for possible responses.
 *
 * @see http://docs2.icepay.com/payment-process/transaction-status-flow/transaction-statuses/
 */
abstract class AbstractResponse extends OmnipayAbstractResponse
{
    /**
     * The transaction has been cancelled by the consumer.
     *
     * @var string
     */
    public const RESPONSE_STATUS_CANCELLED = 'CANCELLED';

    /**
     * A chargeback was requested by the consumer on a transaction under dispute. The system will create a new
     * chargeback transaction. All funds will be returned to the consumer.
     *
     * @var string
     */
    public const RESPONSE_STATUS_CBACK = 'CBACK';

    /**
     * The transaction was successfully processed and was cleared by the payments system. Funds have not (yet) been
     * received by ICEPAY. It's the customers own risk to deliver products and/or services based on this status.
     *
     * @var string
     */
    public const RESPONSE_STATUS_COMPLETED = 'COMPLETED';

    /**
     * The consumer did not complete the transaction in due time.
     *
     * @var string
     */
    public const RESPONSE_STATUS_EXPIRED = 'EXPIRED';

    /**
     * The transaction failed due to technical reasons.
     *
     * @var string
     */
    public const RESPONSE_STATUS_FAILED = 'FAILED';

    /**
     * A transaction was initiated by the consumer.
     *
     * @var string
     */
    public const RESPONSE_STATUS_PENDING = 'PENDING';

    /**
     * A refund was initiated by the merchant. The system will create a new refund transaction. All (or part of the)
     * funds will be returned to the consumer.
     *
     * @var string
     */
    public const RESPONSE_STATUS_REFUND = 'REFUND';

    /**
     * The transaction failed due to functional reasons.
     *
     * @var string
     */
    public const RESPONSE_STATUS_REJECTED = 'REJECTED';

    /**
     * The transaction was settled to ICEPAY, funds were received by ICEPAY and the transaction was fully reconciled in
     * the payments system. The transaction will be credited to the balance of the merchant and is available for payout.
     *
     * @var string
     */
    public const RESPONSE_STATUS_SETTLED = 'SETTLED';

    /**
     * The payment process was started by the consumer after initiation of the transaction.
     *
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
