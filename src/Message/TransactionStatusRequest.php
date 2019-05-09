<?php

namespace Omnipay\IcepayPayments\Message;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Get status of transaction at Icepay, using REST api
 */
class TransactionStatusRequest extends AbstractRestRequest
{
    /**
     * Last part of the api url, which method you want to call.
     *
     * @var string
     */
    const REQUEST_FUNCTION = 'transaction/';

    /**
     * HTTP method type of request
     *
     * @var string
     */
    const REQUEST_METHOD = 'GET';

    /**
     * @inheritDoc
     */
    protected function runTransaction(\GuzzleHttp\ClientInterface $client, array $data): PsrResponseInterface
    {
        $location = self::REQUEST_FUNCTION . $this->getTransactionId();

        // we need to do the hash here because we need to know the full url and request method
        $hash = $this->getSecurityHash($location, self::REQUEST_METHOD, json_encode($data));

        $request = new Request(
            self::REQUEST_METHOD,
            $this->getEndpoint() . $location,
            $this->getHeaders($hash)
        );

        return $client->send($request);
    }

    /**
     * @inheritDoc
     */
    protected function getResponseName(): string
    {
        // TODO: Implement getResponseName() method.
    }

}