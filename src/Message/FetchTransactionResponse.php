<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Response;

class FetchTransactionResponse extends AbstractResponse
{
    /**
     * The response status code.
     *
     * @var int
     */
    private $statusCode;

    public function __construct(RequestInterface $request, int $statusCode, $data)
    {
        parent::__construct($request, $data);

        $this->statusCode = $statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode === Response::HTTP_OK;
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
    public function getCode(): int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference(): ?string
    {
        return $this->data['id'] ?? null;
    }

    /**
     * {@inheritdoc}
     *
     * Fallback on the postback data that was given by ICEPAY.
     * Do note: this is not (always) a reliable transaction status.
     *
     * @see https://documentation.icepay.com/payments/payment-process/payment-feedback/postback/
     */
    protected function getTransactionStatus(): ?string
    {
        if ($this->getCode() === 404) {
            return $this->request->getHttpRequest()->query->getAlpha('statusCode', null);
        }

        return $this->data['status'] ?? null;
    }
}
