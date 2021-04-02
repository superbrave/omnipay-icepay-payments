<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class CompleteAuthorizeRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
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

        return new CompleteAuthorizeResponse(
            $this,
            $response->getStatusCode(),
            $this->getResponseBody($response),
        );
    }

    /**
     * Return the Symfony HttpRequest.
     *
     * @see AbstractRequest::$httpClient
     *
     * @return Request
     */
    public function getHttpRequest(): Request
    {
        return $this->httpRequest;
    }
}
