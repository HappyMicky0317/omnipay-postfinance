<?php

namespace Omnipay\Postfinance\Message;

use Omnipay\Common\CreditCard;

class PurchaseRequest extends AbstractRequest
{
    protected $optionalParams = array(
        'tp',
        'title',
        'bgColor',
        'txtColor',
        'tblBgColor',
        'hdTblBgColor',
        'tblTxtColor',
        'hdTblTxtColor',
        'buttonBgColor',
        'buttonTxtColor',
        'logo',
        'fontType',
        'hdFontType'
    );

    public function getData()
    {
        $this->validate('pspId', 'transactionId', 'amount', 'currency', 'language');

        $data = array(
            'PSPID'     => $this->getPspId(),
            'ORDERID'   => $this->getTransactionId(),
            'AMOUNT'    => $this->getAmountInteger(),
            'CURRENCY'  => $this->getCurrency(),
            'LANGUAGE'  => $this->getLanguage()
        );

        foreach ($this->optionalParams as $param) {
            $value = Helper::stringValue($this->getParameter($param));

            if ($value !== '') {
                $data[strtoupper($param)] = $value;
            }
        }

        /** @var CreditCard $card */
        if ($card = $this->getCard()) {
            $data['CN']             = $card->getName();
            $data['EMAIL']          = $card->getEmail();
            $data['OWNERADDRESS']   = $card->getAddress1() . ($card->getAddress2() ? ' / ' . $card->getAddress2() : '');
            $data['OWNERZIP']       = $card->getPostcode();
            $data['OWNERTOWN']      = $card->getCity();
            $data['OWNERCTY']       = $card->getCountry();
            $data['OWNERTELNO']     = $card->getPhone();
        }

        $data['COM'] = $this->getDescription();

        $data['ACCEPTURL']      = $this->getReturnUrl();
        $data['CANCELURL']      = $this->getCancelUrl();
        $data['EXCEPTIONURL']   = $this->getNotifyUrl();
        $data['DECLINEURL']     = $this->getNotifyUrl();

        // Operation setting
        $data['OPERATION'] = $this->getOperation();

        return $data;
    }

    // Send request the 1.x way
    public function send()
    {
        return $this->sendData($this->getData());
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }
}
