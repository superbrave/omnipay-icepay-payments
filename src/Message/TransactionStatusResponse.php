<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\AbstractResponse;
use Psr\Http\Message\ResponseInterface;

/**
 * Response of retrieving a payment at Icepay
 */
class TransactionStatusResponse extends AbstractResponse
{
    /**
     * Contains decoded response. Response is a ResponseInterface instance, this contains the body as string.
     *
     * @var string[]
     */
    private $responseContent;

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        if (!($this->data instanceof ResponseInterface)) {
            return false;
        }

        /** @var ResponseInterface $this->data */
        if ($this->data->getStatusCode() !== 200) {
            return false;
        }

        $this->responseContent = json_decode($this->data->getBody(), true);

        if (!empty($this->responseContent['TransactionId'])) {
            return true;
        }

        return false;
    }

    /**
     * Get a reference provided by the gateway to represent this transaction
     *
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->data->getBody();
    }

}