<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The request for retrieving the payment transaction status when the CompleteAuthorise and Capture happens.
 * The payment transaction status can be different from what we get back as data from Icepay.
 */
class CompleteAuthoriseAndCaptureRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();

        $data['ContractProfileId'] = $this->getContractProfileId();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {
        $this->sendRequest(
            Request::METHOD_GET,
            sprintf(
                '/transaction/%s',
                $this->getTransactionReference()
            )
        );

        return new CompleteAuthoriseAndCaptureResponse(
            $this,
            $this->getResponseBody()
        );
    }

    /**
     * Get the HttpRequest.
     * Note: this is not an API request.
     *
     * @see Omnipay\Common\Message\AbstractRequest::$httpRequest
     *
     * @return Request
     */
    public function getHttpRequest(): Request
    {
        return $this->httpRequest;
    }
}
