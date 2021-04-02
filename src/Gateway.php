<?php

declare(strict_types=1);

namespace Omnipay\IcepayPayments;

use DateTimeImmutable;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\IcepayPayments\Message\CaptureRequest;
use Omnipay\IcepayPayments\Message\CompleteAuthorizeRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\FetchTransactionRequest;
use Omnipay\IcepayPayments\Message\RefundRequest;

class Gateway extends AbstractGateway
{
    /**
     * @var string
     */
    private const API_BASE_URL = 'https://interconnect.icepay.com';

    /**
     * @var string
     */
    private const TEST_API_BASE_URL = 'https://acc-interconnect.icepay.com';

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'Icepay Payments';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(array $parameters = []): self
    {
        parent::initialize($parameters);

        $baseUrl = self::API_BASE_URL;
        if ($this->getTestMode()) {
            $baseUrl = self::TEST_API_BASE_URL;
        }

        $this->setBaseUrl($baseUrl);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function authorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CreateTransactionRequest::class, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function completeAuthorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CompleteAuthorizeRequest::class, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function capture(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function refund(array $parameters = []): RequestInterface
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return RequestInterface
     */
    public function fetchTransaction(array $parameters = []): RequestInterface
    {
        return $this->createRequest(FetchTransactionRequest::class, $parameters);
    }

    /**
     * Apply the timestamp to the parameters at every request.
     *
     * {@inheritdoc}
     */
    protected function createRequest($class, array $parameters): RequestInterface
    {
        $parameters['timestamp'] = new DateTimeImmutable();

        return parent::createRequest($class, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters(): array
    {
        return [
            'baseUrl' => self::API_BASE_URL,
            'testMode' => false,
            'contractProfileId' => '',
            'secretKey' => '',
        ];
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
     * Get the secret key.
     *
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->getParameter('secretKey');
    }

    /**
     * Set the secret key.
     *
     * @param string $secretKey
     *
     * @return self
     */
    public function setSecretKey(string $secretKey): self
    {
        return $this->setParameter('secretKey', $secretKey);
    }
}
