<?php

namespace Omnipay\IcepayPayments\Message;

use DateTime;
use DateTimeZone;
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
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $data = parent::getData();

        $data['ContractProfileId'] = $this->getContractProfileId();
        $data['AmountInCents'] = $this->getAmountInteger();
        $data['CurrencyCode'] = $this->getCurrencyCode();
        $data['Reference'] = $this->getReference();
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
