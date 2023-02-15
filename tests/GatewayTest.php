<?php

namespace Omnipay\Postfinance;

use Omnipay\Postfinance\Message\Helper;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setPspId('testPspId');
        $this->gateway->setLanguage('en_US');
        $this->gateway->setShaIn('MyShaInSecret');
        $this->gateway->setShaOut('Mysecretsig1875!?');

        $this->options = array(
            'amount' => '10.00',
            'currency' => 'CHF',
            'transactionId' => '1',
            'returnUrl' => 'https://www.example.com/return',
        );
    }

    /**
     * Test SHA hashing of data.
     * Use example data from the official documentation and ensure that the implmentation generates the same hashes.
     */
    public function testSHASign()
    {
        $data = array(
            'PSPID' => 'MyPSPID',
            'ORDERID' => '1234',
            'AMOUNT' => 1500,
            'CURRENCY' => 'EUR',
            'LANGUAGE' => 'en_US',
            // add a bogus SHASIGN data, it should be ignored during calculation of a hash
            'SHASIGN' => 'bogus',
            // add an empty value. Empty fields should be ignored as well!
            'EMPTY_MUST_IGNORE' => ''
        );

        // reconstruct the hashes from the official documentation and ensure we calculate the same hashes
        $this->assertEquals(
            'F4CC376CD7A834D997B91598FA747825A238BE0A',
            Helper::createShaHash($data, 'Mysecretsig1875!?', 'sha1'),
            'SHA-1 hash does not match the excepted output'
        );

        $this->assertEquals(
            'E019359BAA3456AE5A986B6AABD22CF1B3E09438739E97F17A7F61DF5A11B30F',
            Helper::createShaHash($data, 'Mysecretsig1875!?', 'sha256'),
            'SHA-256 hash does not match the excepted output'
        );

        $this->assertEquals(
            'D1CFE8833A297D0922E908B2B44934B09EE966EF1584DC0D696304E07BB58BA71973C2383C831D878D8A243BB7D7DFFFBE53CEE21955CDFEF44FE82E551F859D',
            Helper::createShaHash($data, 'Mysecretsig1875!?', 'sha512'),
            'SHA-512 hash does not match the excepted output'
        );

        // reconstruct sha-out example from the official documentation
        $outData = array(
            'BRAND' => 'VISA',
            'ACCEPTANCE' => 1234,
            'amount' => 15,
            'CARDNO' => 'XXXXXXXXXXXX1111',
            'NCERROR' => 0,
            'currency' => 'EUR',
            'PAYID' => 32100123,
            'PM' => 'CreditCard',
            'orderID' => 12,
            'STATUS' => 9
        );

        $this->assertEquals(
            '209113288F93A9AB8E474EA78D899AFDBB874355',
            Helper::createShaHash($outData, 'Mysecretsig1875!?', 'sha1'),
            'SHA-OUT hash does not match the excepted output (sha1)'
        );
    }

    //------------------------------------------------------------------------------------------------------------------
    // Purchase
    //------------------------------------------------------------------------------------------------------------------

    public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();

        // Expected redirect-data for the default options
        $data = array(
            'PSPID' => 'testPspId',
            'ORDERID' => '1',
            'AMOUNT' => 1000,
            'CURRENCY' => 'CHF',
            'LANGUAGE' => 'en_US',
            'COM' => null,
            'ACCEPTURL' => 'https://www.example.com/return',
            'CANCELURL' => null,
            'EXCEPTIONURL' => null,
            'DECLINEURL' => null,
            'OPERATION' => ''
        );

        // sign the data
        $data['SHASIGN'] = Helper::createShaHash($data, $this->gateway->getShaIn());

        $this->assertInstanceOf('\Omnipay\Postfinance\Message\PurchaseResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
        $this->assertEquals($data, $response->getRedirectData());
        $this->assertStringStartsWith('https://e-payment.postfinance.ch/ncol/', $response->getRedirectUrl());
    }

    public function testCompletePurchaseSuccess()
    {
        $data = array(
            'BRAND' => 'VISA',
            'ACCEPTANCE' => 1234,
            'amount' => 15,
            'CARDNO' => 'XXXXXXXXXXXX1111',
            'NCERROR' => 0,
            'currency' => 'EUR',
            'PAYID' => '32100123',
            'PM' => 'CreditCard',
            'orderID' => 12,
            'STATUS' => 9,
            'SHASIGN' => '209113288F93A9AB8E474EA78D899AFDBB874355'
        );


        $this->getHttpRequest()->query->replace($data);

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertInstanceOf('\Omnipay\Postfinance\Message\CompletePurchaseResponse', $response);
        $this->assertFalse($response->isRedirect());
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('32100123', $response->getTransactionReference());
        $this->assertEquals('', $response->getMessage());
    }


    public function testCompletePurchaseError()
    {
        $data = array(
            'STATUS' => 0,
            'NCERROR' => 500,
            'orderID' => '1',
            'PAYID' => 'a'
        );

        // create sha hash for the given data
        $data['SHASIGN'] = Helper::createShaHash($data, $this->gateway->getShaOut());

        $this->getHttpRequest()->query->replace($data);

        $response = $this->gateway->completePurchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(500, $response->getMessage());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompletePurchaseInvalid()
    {
        $this->getHttpRequest()->query->replace(array(
            'STATUS' => 9,
            'NCERROR' => 0,
            'orderID' => '1',
            'PAYID' => 'a',
            'SHASIGN' => 'InvalidHash'
        ));

        $response = $this->gateway->completePurchase($this->options)->send();
    }

    //------------------------------------------------------------------------------------------------------------------
    // Authorize
    //------------------------------------------------------------------------------------------------------------------

    public function testAuthorize()
    {
        $response = $this->gateway->authorize($this->options)->send();

        $data = $response->getData();
        $this->assertEquals('RES', $data['OPERATION']);
        $this->assertInstanceOf('\Omnipay\Postfinance\Message\PurchaseResponse', $response);
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertEquals('POST', $response->getRedirectMethod());
        $this->assertStringStartsWith('https://e-payment.postfinance.ch/ncol/', $response->getRedirectUrl());
    }

    public function testCompleteAuthorizeSuccess()
    {
        $data = array(
            'STATUS' => 5,
            'NCERROR' => 0,
            'orderID' => '1',
            'amount' => 12,
            'PAYID' => 'abc'
        );

        // create sha hash for the given data
        $data['SHASIGN'] = Helper::createShaHash($data, $this->gateway->getShaOut());

        $this->getHttpRequest()->query->replace($data);

        $response = $this->gateway->completeAuthorize($this->options)->send();

        $this->assertFalse($response->isRedirect());
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('abc', $response->getTransactionReference());
    }


    public function testCompleteAuthorizeError()
    {
        $data = array(
            'STATUS' => 0,
            'NCERROR' => 500,
            'orderID' => '1',
            'PAYID' => 'a'
        );

        // create sha hash for the given data
        $data['SHASIGN'] = Helper::createShaHash($data, $this->gateway->getShaOut());

        $this->getHttpRequest()->query->replace($data);

        $response = $this->gateway->completeAuthorize($this->options)->send();

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * @expectedException Omnipay\Common\Exception\InvalidResponseException
     */
    public function testCompleteAuthorizeInvalid()
    {
        $this->getHttpRequest()->query->replace(array(
            'STATUS' => 9,
            'NCERROR' => 0,
            'orderID' => '1',
            'PAYID' => 'a',
            'SHASIGN' => 'InvalidHash'
        ));

        $response = $this->gateway->completeAuthorize($this->options)->send();
    }
}
