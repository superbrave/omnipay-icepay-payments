<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateTransactionRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $parentData = parent::getData();
        $data = [
            'ConsumerFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Contract' => [
                'ContractProfileId' => $this->getContractProfileId(),
                'AmountInCents' => $this->getAmountInteger(),
                'CurrencyCode' => $this->getCurrencyCode(),
                'Reference' => $this->getTransactionId(),
            ],
            'Fulfillment' => [
                'PaymentMethod' => $this->getPaymentMethod(),
                'IssuerCode' => $this->getIssuerCode(),
                'AmountInCents' => $this->getAmountInteger(),
                'CurrencyCode' => $this->getCurrencyCode(),
                'Timestamp' => $this->getTimestamp()->format(self::TIMESTAMP_FORMAT),
                'LanguageCode' => $this->getLanguageCode(),
                'CountryCode' => $this->getCountryCode(),
                'Reference' => $this->getTransactionId(),
                'Description' => $this->getDescription(),
            ],
            'IntegratorFootprint' => [
                'IPAddress' => '127.0.0.1',
                'TimeStampUTC' => '0',
            ],
            'Postback' => [
                'UrlCompleted' => $this->getReturnUrl(),
                'UrlError' => $this->getCancelUrl(),
                'UrlsNotify' => [
                    $this->getNotifyUrl(),
                ],
            ],
        ];

        return array_merge($parentData, $data);
    }

    /**
     * {@inheritdoc}
     *
     * @see https://documentation.icepay.com/api/#operation/Transaction
     */
    public function sendData($data): ResponseInterface
    {
        $response = $this->sendRequest(
            Request::METHOD_POST,
            '/api/contract/transaction',
            $data
        );

        return new CreateTransactionResponse(
            $this,
            $this->getResponseBody($response)
        );
    }
}
