<?php

namespace Omnipay\Postfinance\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testGetDataWithoutCard()
    {
        $this->request->initialize(array(
            'pspId' => 'testPspId',
            'language' => 'en_US',
            'shaIn' => 'MyShaInSecret',
            'amount' => '12.00',
            'currency' => 'CHF',
            'transactionId' => '123',
            'description' => 'Order Description',
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel',
            'title' => 'My shop title'
        ));

        $expected = array(
            'PSPID' => 'testPspId',
            'LANGUAGE' => 'en_US',
            // Currency has to be converted to integer, so 12.00 becomes 1200
            'AMOUNT' => 1200,
            'CURRENCY' => 'CHF',
            // transactionId maps to ORDERID
            'ORDERID' => '123',
            // description maps to COM
            'COM' => 'Order Description',
            'ACCEPTURL' => 'https://www.example.com/return',
            'CANCELURL' => 'https://www.example.com/cancel',
            'EXCEPTIONURL' => null,
            'DECLINEURL' => null,
            'OPERATION' => null,
            'TITLE' => 'My shop title'
        );

        $this->assertEquals($expected, $this->request->getData());
    }

    public function testGetDataWithCard()
    {
        $this->request->initialize(array(
            'pspId' => 'testPspId',
            'language' => 'en_US',
            'shaIn' => 'MyShaInSecret',
            'amount' => '12.00',
            'currency' => 'CHF',
            'transactionId' => '123',
            'description' => 'Order Description',
            'returnUrl' => 'https://www.example.com/return',
            'cancelUrl' => 'https://www.example.com/cancel'
        ));

        $card = new CreditCard(array(
            'name' => 'Hans Muster',
            'address1' => 'Teststrasse 123',
            'address2' => 'Postfach 321',
            'city' => 'Bern',
            'country' => 'CH',
            'postcode' => '3000',
            'phone' => '098 765 43 21',
            'email' => 'test@test.ch',
        ));
        $this->request->setCard($card);

        $expected = array(
            'PSPID' => 'testPspId',
            'LANGUAGE' => 'en_US',
            // Currency has to be converted to integer, so 12.00 becomes 1200
            'AMOUNT' => 1200,
            'CURRENCY' => 'CHF',
            // transactionId maps to ORDERID
            'ORDERID' => '123',
            // description maps to COM
            'COM' => 'Order Description',
            'ACCEPTURL' => 'https://www.example.com/return',
            'CANCELURL' => 'https://www.example.com/cancel',
            'EXCEPTIONURL' => null,
            'DECLINEURL' => null,
            'OPERATION' => null,

            // Credit card data
            'CN' => 'Hans Muster',
            'EMAIL' => 'test@test.ch',
            'OWNERADDRESS' => 'Teststrasse 123 / Postfach 321',
            'OWNERZIP' => '3000',
            'OWNERTOWN' => 'Bern',
            'OWNERCTY' => 'CH',
            'OWNERTELNO' => '098 765 43 21'
        );

        $this->assertEquals($expected, $this->request->getData());
    }

}
