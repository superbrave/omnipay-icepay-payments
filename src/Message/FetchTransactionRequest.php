<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class FetchTransactionRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     *
     * @see https://documentation.icepay.com/api/#operation/Get%20Transaction
     */
    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            Request::METHOD_GET,
            sprintf(
                '/api/transaction/%s',
                $this->getTransactionReference()
            )
        );

        return new FetchTransactionResponse(
            $this,
            $response->getStatusCode(),
            $this->getResponseBody($response)
        );
    }
}
