<?php

namespace Omnipay\IcepayPayments\Message;

use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;

/**
 * Provides the base isSuccessful() implementation.
 */
abstract class AbstractResponse extends OmnipayAbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return isset($this->data['ContractProfileId']) ?? false;
    }
}
