<?php

namespace Omnipay\IcepayPayments\Message;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Create a payment at Icepay, using REST api
 */
class CreateTransactionRequest extends AbstractRestRequest
{
    /**
     * Last part of the api url, which method you want to call.
     *
     * @var string
     */
    const REQUEST_FUNCTION = 'contract/transaction';

    /**
     * HTTP method type of request
     *
     * @var string
     */
    const REQUEST_METHOD = 'POST';

    /**
     * {@inheritdoc}
     */
    protected function runTransaction(
        ClientInterface $client,
        array $data
    ): PsrResponseInterface {
        // we need to do the hash here because we need to know the full url and request method
        $hash = $this->getSecurityHash(self::REQUEST_FUNCTION, self::REQUEST_METHOD, json_encode($data));

        $headers = array_merge_recursive(
            [
                'headers' => $this->requestHeaders
            ],
            [
                'headers' => [
                    'CHECKSUM' => $hash,
                    'USERID' => $this->getContractProfileId()
                ]
            ]
        );

        $request = new Request(
            self::REQUEST_METHOD,
            $this->getEndpoint() . self::REQUEST_FUNCTION,
            $headers
        );

        return $client->send($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();

        $data['Fulfillment']['PaymentMethod'] = $this->getParameter('paymentMethod');
        $data['Fulfillment']['IssuerCode'] = $this->getParameter('issuerCode');
        $data['Fulfillment']['AmountInCents'] = $this->getAmountInteger();
        $data['Fulfillment']['CurrencyCode'] = $this->getParameter('currencyCode');
        $data['Fulfillment']['Timestamp'] = date(DATE_ATOM);
        $data['Fulfillment']['LanguageCode'] = $this->getParameter('languageCode');
        $data['Fulfillment']['Reference'] = $this->getParameter('reference');

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getResponseName(): string
    {
        return CreateTransactionResponse::class;
    }
}
