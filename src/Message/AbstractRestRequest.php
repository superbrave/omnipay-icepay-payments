<?php

namespace Omnipay\IcepayPayments\Message;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

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
     * {@inheritdoc}
     *
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData(): array
    {
        $this->validate('secretKey', 'amount'); // @todo

        $this->requestData = [];
        $this->requestData['Contract']['ContractProfileId'] = $this->getContractProfileId();
        $this->requestData['Contract']['AmountInCents'] = $this->getAmountInteger();
        $this->requestData['Contract']['CurrencyCode'] = $this->getCurrency();
        $this->requestData['Contract']['Reference'] = $this->getTransactionId();

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
     * Run the transaction by calling the service.
     *
     * @param ClientInterface $client Configured HttpClient
     * @param array           $data   All data to be sent in the transaction
     *
     * @return PsrResponseInterface
     */
    abstract protected function runTransaction(ClientInterface $client, array $data): PsrResponseInterface;

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
     * Get the FQDN for the response to be created
     *
     * @return string
     */
    abstract protected function getResponseName(): string;

    /**
     * Safety hash from icepay, to be generated after putting in all the data.
     *
     * @param string $relativeUrl     Relative to base url
     * @param string $requestMethod
     * @param string $jsonRequestBody
     *
     * @return string
     */
    protected function getSecurityHash(
        string $relativeUrl,
        string $requestMethod,
        string $jsonRequestBody
    ): string {
        $data = $this->getEndpoint() . $relativeUrl . $requestMethod . $this->getContractProfileId() . $jsonRequestBody;
        $hash = hash_hmac("sha256", $data, base64_decode($this->getSecretKey()), true);

        return base64_encode($hash);
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
     * @param string $secretKey
     *
     * @return self
     */
    public function setSecretKey($secretKey): self
    {
        return $this->setParameter('secretKey', $secretKey);
    }

}