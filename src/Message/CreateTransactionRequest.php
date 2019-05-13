<?php

namespace Omnipay\IcepayPayments\Message;

use DateTime;
use DateTimeZone;
use Omnipay\Common\Message\ResponseInterface;

/**
 * The request for creating a transaction at Icepay.
 */
class CreateTransactionRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('UTC'));

        $data = parent::getData();

        $data['Contract']['ContractProfileId'] = $this->getContractProfileId();
        $data['Contract']['AmountInCents'] = $this->getAmountInteger();
        $data['Contract']['CurrencyCode'] = $this->getCurrencyCode();
        $data['Contract']['Reference'] = $this->getReference();

        $data['Postback']['UrlCompleted'] = $this->getReturnUrl();
        $data['Postback']['UrlError'] = $this->getCancelUrl();
        $data['Postback']['UrlsNotify'] = [$this->getNotifyUrl()]; // array

        $data['IntegratorFootprint']['IPAddress'] = '127.0.0.1';
        $data['IntegratorFootprint']['TimeStampUTC'] = '0';

        $data['ConsumerFootprint']['IPAddress'] = '127.0.0.1';
        $data['ConsumerFootprint']['TimeStampUTC'] = '0';

        $data['Fulfillment']['PaymentMethod'] = $this->getPaymentMethod();
        $data['Fulfillment']['IssuerCode'] = $this->getIssuerCode();
        $data['Fulfillment']['AmountInCents'] = $this->getAmountInteger();
        $data['Fulfillment']['CurrencyCode'] = $this->getCurrencyCode();
        $data['Fulfillment']['Timestamp'] = $dateTime->format('Y-m-d\TH:i:s\Z');
        $data['Fulfillment']['LanguageCode'] = $this->getLanguageCode();
        $data['Fulfillment']['CountryCode'] = $this->getCountryCode();
        $data['Fulfillment']['Reference'] = $this->getReference();

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {
        $this->sendRequest(
            self::METHOD_POST,
            '/contract/transaction',
            $data
        );

        return new CreateTransactionResponse(
            $this,
            $this->getResponseBody()
        );
    }
}
