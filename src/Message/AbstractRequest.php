<?php

namespace Omnipay\IcepayPayments\Message;

use DateTimeInterface;
use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractRequest.
 */
abstract class AbstractRequest extends OmnipayAbstractRequest
{
    /**
     * @var string
     */
    public const TIMESTAMP_FORMAT = 'Y-m-d\TH:i:s\Z';

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        $this->validate('contractProfileId', 'secretKey');

        return [];
    }

    /**
     * Send the request to the API of the Payment Service Provider.
     * The base url and the authentication headers are automatically added.
     *
     * @param string $method
     * @param string $urlPath
     * @param array  $data
     *
     * @return ResponseInterface
     */
    protected function sendRequest(string $method, string $urlPath, array $data): ResponseInterface
    {
        $securityHash = $this->getSecurityHash($method, $urlPath, $data);
        $headers = $this->getAuthenticationHeaders($securityHash);
        $body = null;

        if ($method === Request::METHOD_POST) {
            $headers['Content-Type'] = 'application/json';
            $body = json_encode($data);
        }

        $this->response = $this->httpClient->request(
            $method,
            $this->getBaseUrl().$urlPath,
            $headers,
            $body
        );

        return $this->response;
    }

    /**
     * Returns the JSON decoded response body.
     *
     * @return array
     */
    protected function getResponseBody(): array
    {
        $responseBody = json_decode($this->getResponse()->getBody()->getContents(), true);
        if (is_array($responseBody) === false) {
            $responseBody = [];
        }

        return $responseBody;
    }

    /**
     * Safety hash from icepay, to be generated after putting in all the data.
     *
     * @param string          $requestMethod
     * @param string          $urlPath
     * @param array|\stdClass $data
     * @param bool            $urlIsFullUrl  = false. True to have $urlPath be the absolute (full) url
     *
     * @return string
     */
    protected function getSecurityHash(
        string $requestMethod,
        string $urlPath,
        $data,
        bool $urlIsFullUrl = false
    ): string {
        $contractProfileId = $this->getContractProfileId();

        $fullUrl = $this->getBaseUrl().$urlPath;
        if ($urlIsFullUrl) {
            $fullUrl = $urlPath;
        }

        $toBeHashed = $fullUrl.$requestMethod.$contractProfileId.json_encode($data);

        $hash = hash_hmac(
            'sha256',
            $toBeHashed,
            base64_decode($this->getSecretKey()),
            true
        );

        return base64_encode($hash);
    }

    /**
     * Get the authentication headers information.
     *
     * @param string $securityHash
     *
     * @return array
     */
    private function getAuthenticationHeaders(string $securityHash): array
    {
        return [
            'CHECKSUM' => $securityHash,
            'USERID' => $this->getContractProfileId(),
        ];
    }

    /**
     * Returns the base URL of the API.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->getParameter('baseUrl');
    }

    /**
     * Sets the base URL of the API.
     *
     * @param string $baseUrl
     *
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {
        return $this->setParameter('baseUrl', $baseUrl);
    }

    /**
     * Get Contract Profile Id (also known as the user id).
     *
     * Use the Contract Profile Id assigned by Allied wallet.
     *
     * @return string
     */
    public function getContractProfileId(): string
    {
        return $this->getParameter('contractProfileId');
    }

    /**
     * Set Contract Profile Id (also known as the user id).
     *
     * @param string $contractProfileId
     *
     * @return self
     */
    public function setContractProfileId(string $contractProfileId): self
    {
        return $this->setParameter('contractProfileId', $contractProfileId);
    }

    /**
     * Get Secret Key.
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->getParameter('secretKey');
    }

    /**
     * Set Secret Key.
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
     * Get the currency code.
     *
     * @return string
     */
    public function getCurrencyCode(): string
    {
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
     * Get the country code.
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->getParameter('countryCode');
    }

    /**
     * Sets the country code.
     *
     * @param string $countryCode
     *
     * @return self
     */
    public function setCountryCode(string $countryCode): self
    {
        return $this->setParameter('countryCode', $countryCode);
    }

    /**
     * @return string
     */
    public function getIssuerCode(): string
    {
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
     * Get the language code.
     *
     * @return string
     */
    public function getLanguageCode(): string
    {
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
     * Get the reference.
     *
     * @return string
     */
    public function getReference(): string
    {
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
     * Get the timestamp.
     *
     * @return DateTimeInterface
     */
    public function getTimestamp(): DateTimeInterface
    {
        return $this->getParameter('timestamp');
    }

    /**
     * Sets the timestamp as string value.
     *
     * @param DateTimeInterface $timestamp
     *
     * @return self
     */
    public function setTimestamp(DateTimeInterface $timestamp): self
    {
        return $this->setParameter('timestamp', $timestamp);
    }
}
