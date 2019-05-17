# Omnipay: Icepay Payments (ICEX2.0)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Introduction

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 7.2.9+. This package implements Icepay Payments support for Omnipay and it supports ICEX2.0. Therefore you need a `SecretKey` and a `ContractProfileId` (also known as `UserId`).

*Do note that this implementation does not support Authorise-Capture (for Afterpay) yet.*

## Installation

To install, simply add it to your `composer.json` file:
```shell
$ composer require superbrave/omnipay-icepay-payments
```

## Initialization

First, create the Omnipay gateway:
```php
$gateway = Omnipay\Omnipay::create('\Omnipay\IcepayPayments\Gateway');
// or
$gateway = new \Omnipay\IcepayPayments\Gateway(/* $httpClient, $httpRequest */);
```
Then, initialize it with the correct credentials:
```php
$gateway->initialize([
    'secretKey' => $secretKey, // The given secret key.
    'contractProfileId' => $contractProfileId, // The given contract profile id or user id.
    'testMode' => false // Optional, default: true
]);
// or
$gateway->setSecretKey($secretKey);
$gateway->setContractProfileId($contractProfileId);
```

## Usage

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

### General flow

1. [Create a transaction](#transaction).
2. Check for the [transaction status](#status).
3. Perform one [refund(s)](#refund) operations if needed.

### Transaction

To create a new order, use the `transaction` method:
```php
$data = [
    'Contract' => [
        'ContractProfileId' => '85cf0581-36e2-45c7-8d8c-a24c6f52902c',
        'AmountInCents' => 1337,
        'CurrencyCode' => 'EUR',
        'Reference' => '829c7998-6497-402c-a049-51801ba33662',
    ],
    'Postback' => [
        'UrlCompleted' => 'https://www.superbrave.nl/return-url',
        'UrlError' => 'https://www.superbrave.nl/cancel-url',
        'UrlsNotify' => [
            'https://www.superbrave.nl/notify-url',
        ],
    ],
    'IntegratorFootprint' => [
        'IPAddress' => '127.0.0.1',
        'TimeStampUTC' => '0',
    ],
    'ConsumerFootprint' => [
        'IPAddress' => '127.0.0.1',
        'TimeStampUTC' => '0',
    ],
    'Fulfillment' => [
        'PaymentMethod' => 'IDEAL',
        'IssuerCode' => 'ABNAMRO',
        'AmountInCents' => 1337,
        'CurrencyCode' => 'EUR',
        'Timestamp' => '2019-03-09T12:00:00Z',
        'LanguageCode' => 'nl',
        'CountryCode' => 'NL',
        'Reference' => '829c7998-6497-402c-a049-51801ba33662',
    ],
];

$response = $gateway->authorize($data)->send()->getData();
```
This will return the order details as well as the checkout HTML snippet to render on your site.

[API documentation](http://docs2.icepay.com/calling-our-webservice/transaction-functions/)

### Status

```php
$success = $gateway->fetchTransaction([
    'ContractProfileId' => '85cf0581-36e2-45c7-8d8c-a24c6f52902c',
    'AmountInCents' => 1337,
    'CurrencyCode' => 'EUR',
    'Reference' => '829c7998-6497-402c-a049-51801ba33662',
])->send()
->isSuccessful();
```

[API documentation](https://icepay2.docs.apiary.io/#reference/0/transaction)

### Refund
*Do note: refunds have not been tested in production*

```php
$success = $gateway->refund([
    'ContractProfileId' => '85cf0581-36e2-45c7-8d8c-a24c6f52902c',
    'AmountInCents' => 1337,
    'CurrencyCode' => 'EUR',
    'Reference' => '829c7998-6497-402c-a049-51801ba33662',
])->send()
->isSuccessful();
```

[API documentation](https://icepay2.docs.apiary.io/#reference/0/transaction/refund)

## License

This omnipay gateway is under the MIT license. See the [complete license](LICENSE).
