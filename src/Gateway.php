<?php

namespace Omnipay\IcepayPayments;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\IcepayPayments\Message\AbstractRestRequest;
use Omnipay\IcepayPayments\Message\CreateTransactionRequest;
use Omnipay\IcepayPayments\Message\RefundRequest;
use Omnipay\IcepayPayments\Message\TransactionStatusRequest;

/**
 * Class Gateway
 *
 * @method RequestInterface completePurchase(array $options = array())
 * @method RequestInterface createCard(array $options = array())
 * @method RequestInterface updateCard(array $options = array())
 * @method RequestInterface deleteCard(array $options = array())
 * @method RequestInterface purchase(array $options = array())
 */
class Gateway extends AbstractGateway
{
    /**
     * Create and initialize a request object
     *
     * This function is usually used to create objects of type
     * Omnipay\Common\Message\AbstractRequest (or a non-abstract subclass of it)
     * and initialise them with using existing parameters from this gateway.
     *
     * @param string $class      The request class name
     * @param array  $parameters Data to be sent to Docdata
     *
     * @see \Omnipay\Common\Message\AbstractRequest
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    protected function createRequest($class, array $parameters)
    {
        /**
         * Recognise $obj as request
         *
         * @var AbstractRestRequest $obj Request class
         */
        $obj = new $class($this->httpClient, $this->httpRequest);

        return $obj->initialize(array_replace($this->getParameters(), $parameters));
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Icepay Payments';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return array(
            'contractProfileId' => '',
            'secretKey' => '',
            'testMode' => false,
        );
    }

    /**
     * Create an authorize request.
     * This is not an 'authorisation function' as icepay puts it, but a 'transaction function'.
     *
     * @param array $parameters Data to be sent to icepay
     *
     * @return RequestInterface
     */
    public function authorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(CreateTransactionRequest::class, $parameters);
    }

    /**
     * Create completeAuthorize request.
     * This is not an 'authorisation function' as icepay puts it, but a 'transaction function'.
     *
     * @param array $parameters Data to be sent to icepay
     *
     * @return RequestInterface
     */
    public function completeAuthorize(array $parameters = []): RequestInterface
    {
        return $this->createRequest(TransactionStatusRequest::class, $parameters);
    }

    /**
     * Get the status of the transaction.
     *
     * @param array $options Data to be sent to Icepay
     *
     * @return RequestInterface
     */
    public function fetchTransaction(array $options = []): RequestInterface
    {
        return $this->createRequest(TransactionStatusRequest::class, $options);
    }

    /**
     * Refund transaction
     *
     * @param array $parameters Data to be sent to icepay
     *
     * @return RequestInterface
     */
    public function refund(array $parameters = []): RequestInterface
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * Create a capture request
     *
     * @param array $parameters Data to be sent to Icepay
     *
     * @return RequestInterface
     */
    public function capture(array $parameters = []): RequestInterface
    {
        // @todo implement capture method (out of scope)
    }

    /**
     * Create a void / cancel request
     *
     * @param array $parameters Data to be sent to Icepay
     *
     * @return RequestInterface
     */
    public function void(array $parameters = []): RequestInterface
    {
        // @todo implement void / cancel method (out of scope)
    }

}