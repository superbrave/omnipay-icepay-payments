<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Do note: refunds implementation has not been tested.
 */
class RefundRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return [
            'ContractProfileId' => $this->getContractProfileId(),
            'AmountInCents' => $this->getAmountInteger(),
            'CurrencyCode' => $this->getCurrencyCode(),
            'Reference' => $this->getTransactionId(),
            'Timestamp' => $this->getTimestamp(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see https://documentation.icepay.com/api/#operation/Refund
     */
    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            Request::METHOD_POST,
            sprintf(
                '/api/transaction/%s/refund',
                $this->getTransactionReference()
            ),
            $data
        );

        return new RefundResponse(
            $this,
            $this->getResponseBody($response)
        );
    }
}
