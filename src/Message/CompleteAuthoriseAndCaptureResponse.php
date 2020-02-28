<?php

namespace Omnipay\IcepayPayments\Message;

/**
 * The response after complete authorise and capture request.
 * For this response, we explicitly check what the status is of the payment transaction at Icepay.
 */
class CompleteAuthoriseAndCaptureResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['status']) && in_array($this->data['status'], [
            self::RESPONSE_STATUS_COMPLETED,
            self::RESPONSE_STATUS_SETTLED,
        ]);
    }

    /**
     * {@inheritdoc}
     *
     * In case there is no status 'cancelled' available yet, look up for a statusCode queryString in the HttpRequest.
     * Icepay redirects you to the complete page with the statusCode query string.
     */
    public function isCancelled(): bool
    {
        return isset($this->data['status']) && $this->data['status'] === self::RESPONSE_STATUS_CANCELLED
            || $this->getRequest()->getHttpRequest()->get('statusCode') === self::RESPONSE_STATUS_CANCELLED;
    }

    /**
     * {@inheritdoc}
     */
    public function getTransactionReference(): ?string
    {
        return $this->request->getTransactionReference();
    }
}
