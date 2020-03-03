<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * The response after getting the transaction status at Icepay.
 * For this response, we check the response (statusCode) of the payment transaction at Icepay.
 */
class TransactionStatusResponse extends AbstractResponse
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * TransactionStatusResponse constructor.
     *
     * @param RequestInterface $request
     * @param mixed            $data
     * @param int              $statusCode
     */
    public function __construct(RequestInterface $request, $data, int $statusCode)
    {
        parent::__construct($request, $data);

        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode === 200;
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
