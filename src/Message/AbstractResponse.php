<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;

abstract class AbstractResponse extends OmnipayAbstractResponse
{
    /**
     * A transaction was initiated by the consumer. This is a temporary status only when the platform is busy initiating
     * the transaction.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_PENDING = 'PENDING';

    /**
     * The payment process was started by the consumer after initiation of the transaction.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_STARTED = 'STARTED';

    /**
     * The transaction was successfully processed and was cleared by the payments system.
     *
     * Funds have not (yet) been received by ICEPAY. It’s the customers own risk to deliver products and/or services
     * based on this status.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_COMPLETED = 'COMPLETED';

    /**
     * The transaction has been cancelled by the consumer.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_CANCELLED = 'CANCELLED';

    /**
     * The consumer did not complete the transaction in due time.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_EXPIRED = 'EXPIRED';

    /**
     * The transaction failed due to technical reasons.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_FAILED = 'FAILED';

    /**
     * The transaction failed due to functional reasons.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_REJECTED = 'REJECTED';

    /**
     * The transaction was settled to ICEPAY, funds were received by ICEPAY and the transaction was fully reconciled in
     * the payments system. The transaction will be credited to the balance of the merchant and is available for payout.
     *
     * @var string
     */
    public const TRANSACTION_STATUS_SETTLED = 'SETTLED';

    /**
     * Get the transaction status.
     *
     * @see https://documentation.icepay.com/payments/payment-process/transaction-flow/
     *
     * @return string|null
     */
    abstract protected function getTransactionStatus(): ?string;

    /**
     * The transaction id that was given by ICEPAY.
     *
     * {@inheritdoc}
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['transactionId'] ?? null;
    }
}
