<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;

/**
 * The request for refunding at Icepay.
 */
class RefundRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();

        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('UTC'));

        $data['ContractProfileId'] = $this->getContractProfileId();
        $data['AmountInCents'] = $this->getAmountInteger();
        $data['CurrencyCode'] = $this->getCurrencyCode();
        $data['Reference'] = $this->getReference(); // This isn't the payment reference but needs to be unique among refunds.
        $data['Timestamp'] = $dateTime->format('Y-m-d\TH:i:s\Z');

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
                '/transaction/%s/refund',
                $this->getTransactionReference()
            ),
            $data
        );

        return new CreateTransactionResponse(
            $this,
            $this->getResponseBody()
        );
    }
}
