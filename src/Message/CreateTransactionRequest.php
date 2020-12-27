<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

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
        $parentData = parent::getData();

        $data = [
            'Contract' => [
                'ContractProfileId' => $this->getContractProfileId(),
                'AmountInCents' => $this->getAmountInteger(),
                'CurrencyCode' => $this->getCurrencyCode(),
                'Reference' => $this->getTransactionId(),
            ],
            'Postback' => [
                'UrlCompleted' => $this->getReturnUrl(),
                'UrlError' => $this->getCancelUrl(),
                'UrlsNotify' => [
                    $this->getNotifyUrl(),
                ],
            ],
            'IntegratorFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'ConsumerFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Fulfillment' => [
                'PaymentMethod' => $this->getPaymentMethod(),
                'IssuerCode' => $this->getIssuerCode(),
                'AmountInCents' => $this->getAmountInteger(),
                'CurrencyCode' => $this->getCurrencyCode(),
                'Consumer' => [
                    'Category' => 'Person',
                ],
                'DeliveryConsumer' => [
                    'Category' => 'Person',
                ],
                'Timestamp' => $this->getTimestamp()->format(self::TIMESTAMP_FORMAT),
                'LanguageCode' => $this->getLanguageCode(),
                'CountryCode' => $this->getCountryCode(),
                'Reference' => $this->getTransactionId(),
                'Order' => [
                    'OrderNumber' => $this->getReference(),
                    'CurrencyCode' => $this->getCurrencyCode(),
                    'TotalGrossAmountCents' => $this->getAmountInteger(),
                    'TotalNetAmountCents' => $this->getAmountInteger(),
                ],
                'Description' => $this->getDescription(),
            ],
        ];

        return array_merge($parentData, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data): ResponseInterface
    {
        $this->sendRequest(
            Request::METHOD_POST,
            '/contract/transaction',
            $data
        );

        return new CreateTransactionResponse(
            $this,
            $this->getResponseBody()
        );
    }
}
