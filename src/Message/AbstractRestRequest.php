<?php

namespace Omnipay\IcepayPayments\Message;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class AbstractRestRequest.
 */
abstract class AbstractRestRequest extends AbstractRequest
{
    /**
     * Test Endpoint URL
     *
     * @var string URL
     */
    protected $testEndpoint = 'https://test-interconnect.icepay.com/api/';

    /**
     * Live Endpoint URL
     *
     * @var string URL
     */
    protected $liveEndpoint = 'https://interconnect.icepay.com/api/';

    /**
     * The generated request data.
     *
     * @var array
     */
    protected $requestData = [];

    /**
     * For authentication purposes we need to send a checksum and userid to icepay.
     *
     * @var array
     */
    protected $requestHeaders = [
        'Content-Type' => 'application/json'
    ];

    /**
     * Run the transaction by calling the service.
     *
     * @param ClientInterface $client Configured HttpClient
     * @param array           $data   All data to be sent in the transaction
     *
     * @return PsrResponseInterface
     */
    abstract protected function runTransaction(ClientInterface $client, array $data): PsrResponseInterface;

    /**
     * Get the FQDN for the response to be created
     *
     * @return string
     */
    abstract protected function getResponseName(): string;

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $this->validate('secretKey', 'amount'); // @todo

        $this->requestData = [];
        $this->requestData['Contract']['ContractProfileId'] = $this->getContractProfileId();
        $this->requestData['Contract']['AmountInCents'] = $this->getAmountInteger();
        $this->requestData['Contract']['CurrencyCode'] = $this->getCurrencyCode();
        $this->requestData['Contract']['Reference'] = $this->getReference();

        $this->requestData['Postback']['UrlCompleted'] = $this->getReturnUrl();
        $this->requestData['Postback']['UrlError'] = $this->getCancelUrl();
        $this->requestData['Postback']['UrlsNotify'] = [$this->getNotifyUrl()]; // array

        $this->requestData['IntegratorFootprint']['IPAddress'] = '127.0.0.1';
        $this->requestData['IntegratorFootprint']['TimeStampUTC'] = '0';

        $this->requestData['ConsumerFootprint']['IPAddress'] = '127.0.0.1';
        $this->requestData['ConsumerFootprint']['TimeStampUTC'] = '0';

        return $this->requestData;
    }

    /**
     * Send data to the Gateway
     *
     * @param array $data Formatted data to be sent to icepay
     *
     * @return ResponseInterface
     */
    public function sendData($data): ResponseInterface
    {
        $client = new Client();

        // Replace this line with the correct function.
        $response = $this->runTransaction($client, $data);
        $class = $this->getResponseName();
        $this->response = new $class($this, $response);

        return $this->response;
    }

    /**
     * Get the API endpoint
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Safety hash from icepay, to be generated after putting in all the data.
     *
     * @param string $relativeUrl     Relative to base url
     * @param string $requestMethod
     * @param string $jsonRequestBody
     *
     * @return string
     */
    protected function getSecurityHash(string $relativeUrl, string $requestMethod, string $jsonRequestBody): string
    {
        $data = $this->getEndpoint() . $relativeUrl . $requestMethod . $this->getContractProfileId() . $jsonRequestBody;
        $hash = hash_hmac('sha256', $data, base64_decode($this->getSecretKey()), true);

        return base64_encode($hash);
    }

    /**
     * Get the request headers, including checksum and userid.
     *
     * @param string $hash
     *
     * @return array
     */
    protected function getHeaders(string $hash): array
    {
        return array_merge_recursive(
            [
                'headers' => $this->requestHeaders,
            ],
            [
                'headers' => [
                    'CHECKSUM' => $hash,
                    'USERID' => $this->getContractProfileId(),
                ]
            ]
        );
    }

    /**
     * @return string
     *
     * @throws InvalidRequestException
     */
    public function getContractProfileId(): string
    {
        if (empty($this->getParameter('contractProfileId'))) {
            throw new InvalidRequestException('contractProfileId must be set.');
        }

        return $this->getParameter('contractProfileId');
    }

    /**
     * Sets the contract profile id (also known as user id) for the API request.
     *
     * @param string $contractProfileId
     *
     * @return self
     */
    public function setContractProfileId($contractProfileId): self
    {
        return $this->setParameter('contractProfileId', $contractProfileId);
    }

    /**
     * @return string
     *
     * @throws InvalidRequestException
     */
    public function getSecretKey(): string
    {
        if (empty($this->getParameter('secretKey'))) {
            throw new InvalidRequestException('secretKey must be set.');
        }

        return $this->getParameter('secretKey');
    }

    /**
     * Sets the secret key for the API request.
     *
     * @param string $secretKey
     *
     * @return self
     */
    public function setSecretKey($secretKey): self
    {
        return $this->setParameter('secretKey', $secretKey);
    }

    /**
     * @return string
     *
     * @throws InvalidRequestException
     */
    public function getCurrencyCode(): string
    {
        if (empty($this->getParameter('currencyCode'))) {
            throw new InvalidRequestException('currencyCode must be set.');
        }

        return $this->getParameter('currencyCode');
    }

    /**
     * Sets the currency code.
     *
     * @param string $currencyCode
     *
     * @return self
     */
    public function setCurrencyCode(string $currencyCode): self
    {
        return $this->setParameter('currencyCode', $currencyCode);
    }

    /**
     * @return string
     *
     * @throws InvalidRequestException
     */
    public function getIssuerCode(): string
    {
        if (empty($this->getParameter('issuerCode'))) {
            throw new InvalidRequestException('issuerCode must be set.');
        }

        return $this->getParameter('issuerCode');
    }

    /**
     * Sets the issuerCode.
     *
     * @param string $issuerCode
     *
     * @return self
     */
    public function setIssuerCode(string $issuerCode): self
    {
        return $this->setParameter('issuerCode', $issuerCode);
    }

    /**
     * @return string
     *
     * @throws InvalidRequestException
     */
    public function getLanguageCode(): string
    {
        if (empty($this->getParameter('languageCode'))) {
            throw new InvalidRequestException('languageCode must be set.');
        }

        return $this->getParameter('languageCode');
    }

    /**
     * Sets the language code.
     *
     * @param string $languageCode
     *
     * @return self
     */
    public function setLanguageCode(string $languageCode): self
    {
        return $this->setParameter('languageCode', $languageCode);
    }

    /**
     * @return string
     *
     * @throws InvalidRequestException
     */
    public function getReference(): string
    {
        if (empty($this->getParameter('reference'))) {
            throw new InvalidRequestException('reference must be set.');
        }

        return $this->getParameter('reference');
    }

    /**
     * Sets the reference.
     *
     * @param string $reference
     *
     * @return self
     */
    public function setReference(string $reference): self
    {
        return $this->setParameter('reference', $reference);
    }

    /**
     * @return string
     *
     * @throws InvalidRequestException
     */
    public function getTimestamp(): string
    {
        if (empty($this->getParameter('timestamp'))) {
            throw new InvalidRequestException('timestamp must be set.');
        }

        return $this->getParameter('timestamp');
    }

    /**
     * Sets the timestamp as string value.
     *
     * @param string $timestamp
     *
     * @return self
     */
    public function setTimestamp(string $timestamp): self
    {
        return $this->setParameter('timestamp', $timestamp);
    }
}
