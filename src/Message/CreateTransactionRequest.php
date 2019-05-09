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
     * HTTP method type of request.
     *
     * @var string
     */
    const REQUEST_METHOD = 'POST';

    /**
     * {@inheritdoc}
     */
    protected function runTransaction(ClientInterface $client, array $data): PsrResponseInterface
    {
        // we need to do the hash here because we need to know the full url and request method
        $hash = $this->getSecurityHash(
            self::REQUEST_FUNCTION,
            self::REQUEST_METHOD,
            json_encode($data)
        );

        $request = new Request(
            self::REQUEST_METHOD,
            $this->getEndpoint() . self::REQUEST_FUNCTION,
            $this->getHeaders($hash)
        );

        return $client->send($request);
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $data = parent::getData();

        $data['Fulfillment']['PaymentMethod'] = $this->getPaymentMethod();
        $data['Fulfillment']['IssuerCode'] = $this->getIssuerCode();
        $data['Fulfillment']['AmountInCents'] = $this->getAmountInteger();
        $data['Fulfillment']['CurrencyCode'] = $this->getCurrencyCode();
        $data['Fulfillment']['Timestamp'] = $this->getTimestamp();
        $data['Fulfillment']['LanguageCode'] = $this->getLanguageCode();
        $data['Fulfillment']['Reference'] = $this->getReference();

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
