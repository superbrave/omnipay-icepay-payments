<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * The request for completing the authorise and capture at Icepay.
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
            Request::METHOD_POST,
            sprintf(
                '/transaction/%s',
                $this->getTransactionReference()
            ),
            $data
        );

        return new CompleteAuthoriseAndCaptureResponse(
            $this,
            $this->getResponseBody()
        );
    }

    /**
     * Get the HttpRequest.
     *
     * @return Request
     */
    public function getHttpRequest(): Request
    {
        return $this->httpRequest;
    }
}
