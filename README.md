# Omnipay: Postfinance

**Postfinance Gateway for the Omnipay PHP payment processing library.**

[![Build Status](https://api.travis-ci.org/bummzack/omnipay-postfinance.png)](https://travis-ci.org/bummzack/omnipay-postfinance)
[![Code Coverage](https://scrutinizer-ci.com/g/bummzack/omnipay-postfinance/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bummzack/omnipay-postfinance/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bummzack/omnipay-postfinance/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bummzack/omnipay-postfinance/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/bummzack/omnipay-postfinance/v/stable)](https://packagist.org/packages/bummzack/omnipay-postfinance)
[![Latest Unstable Version](https://poser.pugx.org/bummzack/omnipay-postfinance/v/unstable)](https://packagist.org/packages/bummzack/omnipay-postfinance)
[![License](https://poser.pugx.org/bummzack/omnipay-postfinance/license)](https://packagist.org/packages/bummzack/omnipay-postfinance)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+.

This Gateway implements offsite payments via Postfinance. Purchase and Authorization are available, capturing an authorized payment has to be performed via Postfinance backend (not currently implemented for this Gateway).

**Please note:** This gateway cannot successfully complete your requests if you don't use an SHA-OUT signature. If you don't set the SHA-OUT signature in the Postfinance backend, callback URLs won't be supplied with any parameters, which makes it impossible to determine success or failure of a payment-request.

## Installation

Omnipay can be installed using [Composer](https://getcomposer.org/). [Installation instructions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

Run the following command to install omnipay and the postfinance gateway:

    composer require bummzack/omnipay-postfinance ~0.1
    
## Configuration in the Postfinance Backend

This is the easiest way to setup your Postfinance account to work with the Omnipay Gateway:

1. In the **Global Security Parameters** tab, choose **Each parameter followed by the passphrase.** as the way to hash parameters. 
2. The **Hash algorithm** can be chosen freely, but must be supplied as `hashingMethod` parameter to the gateway if you're using anything else than the default (`sha1`)
3. Make sure to supply an **SHA-IN pass phrase** in the **Data and origin verification** tab
4. Under **Transaction feedback**, make sure to check **I would like to receive transaction feedback parameters on the redirection URLs** *and* supply a **SHA-OUT pass phrase**.


## Basic Usage

Payment requests to the Postfinance Gateway must at least supply the following parameters:

 - `pspId` Your postfinance account ID
 - `transactionId` unique transaction ID
 - `amount` monetary amount
 - `currency` currency
 - `language` locale code indicating the customer language preference, example: `en_US`

It is highly recommended to use SHA-IN and -OUT signatures for your requests.

```php
$gateway = Omnipay::create('Postfinance');
$gateway->setPspId('myPspId');
$gateway->setShaIn('MyShaInSecret');
$gateway->setShaOut('MyShaOutSecret');
$gateway->setLanguage('de_DE');

// Send purchase request
$response = $gateway->purchase(
    [
        'transactionId' => '17',
        'amount' => '10.00',
        'currency' => 'CHF'
    ]
)->send();

// This is a redirect gateway, so redirect right away
$response->redirect();

```

