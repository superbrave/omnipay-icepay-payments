<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments\Message;

use DateTimeInterface;
use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractRequest extends OmnipayAbstractRequest
{
    /**
     * Timestamp format according Icepay documentation.
     *
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
     * Get the base URL of the API.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->getParameter('baseUrl');
    }

    /**
     * Set the base URL of the API.
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
     * Get the ContractProfileId (also known as the UserId).
     *
     * @return string
     */
    public function getContractProfileId(): string
    {
        return $this->getParameter('contractProfileId');
    }

    /**
     * Set the ContractProfileId (also known as the UserId).
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
     * Set the Secret Key.
     *
     * @param string $secretKey
     *
     * @return self
     */
    public function setSecretKey(string $secretKey): self
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
     * Set the country code.
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
     * Get the issuer code.
     *
     * @return string
     */
    public function getIssuerCode(): string
    {
        return $this->getParameter('issuerCode');
    }

    /**
     * Set the issuer code.
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
     * Set the language code.
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
     * Set the reference.
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
     * Set the timestamp as string value.
     *
     * @param DateTimeInterface $timestamp
     *
     * @return self
     */
    public function setTimestamp(DateTimeInterface $timestamp): self
    {
        return $this->setParameter('timestamp', $timestamp);
    }

    /**
     * Send the request to the API of the payment service provider.
     *
     * @param string     $requestMethod
     * @param string     $requestPath
     * @param array|null $data
     *
     * @return ResponseInterface
     */
    protected function sendRequest(string $requestMethod, string $requestPath, ?array $data = null): ResponseInterface
    {
        $requestUrl = sprintf('%s%s', $this->getBaseUrl(), $requestPath);
        $requestHeaders = $this->getAuthenticationHeaders(
            $this->createChecksum($requestUrl, $requestMethod, $data)
        );

        $response = $this->httpClient->request(
            $requestMethod,
            $requestUrl,
            $requestHeaders,
            json_encode($data)
        );

        return $response;
    }

    /**
     * Get the json response data from the request.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function getResponseBody(ResponseInterface $response): array
    {
        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    /**
     * Create a checksum based on the request method, path, secret and data.
     *
     * @see https://documentation.icepay.com/payments/checksum/
     *
     * @param string     $requestUrl    full request url to the API endpoint
     * @param string     $requestMethod Request method e.g. POST or GET
     * @param array|null $data
     *
     * @return string
     */
    private function createChecksum(string $requestUrl, string $requestMethod, array $data = null): string
    {
        return base64_encode(
            hash_hmac(
                'sha256',
                sprintf(
                    '%s%s%s%s',
                    $requestUrl,
                    $requestMethod,
                    $this->getContractProfileId(),
                    json_encode($data)
                ),
                base64_decode($this->getSecretKey()),
                true
            )
        );
    }

    /**
     * Get the authentication headers information.
     *
     * @param string $checksum
     *
     * @return array
     */
    private function getAuthenticationHeaders(string $checksum): array
    {
        return [
            'Content-Type' => 'application/json',
            'ContractProfileId' => $this->getContractProfileId(),
            'CHECKSUM' => $checksum,
        ];
    }
}
