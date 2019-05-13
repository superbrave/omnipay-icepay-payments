<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * The request for getting the transaction status at Icepay.
 */
class TransactionStatusRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();

        $data['ContractProfileId'] = $this->getContractProfileId();
        $data['AmountInCents'] = $this->getAmountInteger();
        $data['CurrencyCode'] = $this->getCurrencyCode();
        $data['Reference'] = $this->getReference();
        $data['Timestamp'] = $this->getTimestamp();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {
        $this->sendRequest(
            self::METHOD_POST,
            sprintf(
                '/transaction/%s',
                $this->getTransactionReference()
            ),
            $data
        );

        return new TransactionStatusResponse(
            $this,
            $this->getResponseBody()
        );
    }
}
