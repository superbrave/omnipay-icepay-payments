# Omnipay: Icepay Payments (ICEX2.0)
[![Build Status](https://scrutinizer-ci.com/g/superbrave/omnipay-icepay-payments/badges/build.png?b=master)](https://scrutinizer-ci.com/g/superbrave/omnipay-icepay-payments/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/superbrave/omnipay-icepay-payments/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/superbrave/omnipay-icepay-payments/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/superbrave/omnipay-icepay-payments/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/superbrave/omnipay-icepay-payments/?branch=master)
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
        'Consumer' => [
            'Address' => [
                'CareOf' => null,
                'City' => 'Bree duh',
                'CountryCode' => 'NL',
                'HouseNumber' => null,
                'PostalCode' => '4817 HX',
                'Street' => 'Quite 18',
            ],
            'Category' => 'Person',
        ],
        'Timestamp' => '2019-03-09T12:00:00Z',
        'LanguageCode' => 'nl',
        'CountryCode' => 'NL',
        'Reference' => '829c7998-6497-402c-a049-51801ba33662',
        'Order' => [
            'OrderNumber' => '12345AB',
            'CurrencyCode' => 'EUR',
            'TotalGrossAmountCents' => 1337,
            'TotalNetAmountCents' => 1337,
        ],
        'Description' => '829c7998-6497-402c-a049-51801ba33662',
    ],
];

$request = $gateway->authorize($data);

$response = $response->send();
```

[API documentation](http://docs2.icepay.com/calling-our-webservice/transaction-functions/)

### Status

```php
$data = [
    'ContractProfileId' => '85cf0581-36e2-45c7-8d8c-a24c6f52902c',
    'AmountInCents' => 1337,
    'CurrencyCode' => 'EUR',
    'Reference' => '829c7998-6497-402c-a049-51801ba33662',
];

$request = $gateway->fetchTransaction($data);

$response = $request->send();
```

[API documentation](https://documentation.icepay.com/api/#operation/Get%20Transaction)

### Refund
*Do note: refunds implementation has not been tested.*

```php
$data = [
    'ContractProfileId' => '85cf0581-36e2-45c7-8d8c-a24c6f52902c',
    'AmountInCents' => 1337,
    'CurrencyCode' => 'EUR',
    'Reference' => '829c7998-6497-402c-a049-51801ba33662',
];

$request = $gateway->refund($data);

$response = $request->send();
```

[API documentation](https://documentation.icepay.com/api/#operation/Refund)

## License

This omnipay gateway is under the MIT license. See the [complete license](LICENSE).
