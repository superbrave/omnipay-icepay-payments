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

        $response = array_merge(
            $this->getResponseBody(),
            [
                'statusCode' => $this->getResponse()->getStatusCode(),
            ]
        );

        return new CompleteAuthoriseAndCaptureResponse(
            $this,
            $response
        );
    }
}
