<?php

namespace Omnipay\IcepayPayments\Message;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

/**
 * Create a payment at Icepay, using REST api
 */
class CreateTransactionRequest extends AbstractRestRequest
{
    /**
     * Last part of the api url, which method you want to call.
     */
    const REQUEST_FUNCTION = 'contract/transaction';

    const REQUEST_METHOD = 'POST';

    /**
     * {@inheritdoc}
     */
    protected function runTransaction(
        ClientInterface $client,
        array $data
    ): Psr\Http\Message\ResponseInterface {
        // we need to do the hash here because we need to know the full url and request method
        $hash = $this->getSecurityHash(self::REQUEST_FUNCTION, self::REQUEST_METHOD, json_encode($data));

        /** @var Client $client */
        return $client->request(
            self::REQUEST_METHOD,
            $this->getEndpoint() . self::REQUEST_FUNCTION,
            [
                'headers' => [
                    'CHECKSUM' => $hash,
                    'USERID' => $this->getContractProfileId()
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = parent::getData();

        $data['Fulfillment']['PaymentMethod'] = $this->getParameter('paymentMethod');
        $data['Fulfillment']['IssuerCode'] = $this->getParameter('issuerCode');
        $data['Fulfillment']['AmountInCents'] = $this->getAmountInteger();
        $data['Fulfillment']['CurrencyCode'] = $this->getParameter('currencyCode');
        $data['Fulfillment']['Timestamp'] = date(DATE_ATOM);
        $data['Fulfillment']['LanguageCode'] = $this->getParameter('languageCode');
        $data['Fulfillment']['Reference'] = $this->getParameter('reference');
    }


    /**
     * {@inheritdoc}
     */
    protected function getResponseName(): string
    {
        return CreateTransactionResponse::class;
    }

}