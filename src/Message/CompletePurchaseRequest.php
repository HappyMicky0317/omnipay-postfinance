<?php

namespace Omnipay\Postfinance\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends AbstractRequest
{
    // parameters that will be included in the SHA-OUT Hash
    protected $signatureParams = array(
        'AAVADDRESS',
        'AAVCHECK',
        'AAVMAIL',
        'AAVNAME',
        'AAVPHONE',
        'AAVZIP',
        'ACCEPTANCE',
        'ALIAS',
        'AMOUNT',
        'BIC',
        'BIN',
        'BRAND',
        'CARDNO',
        'CCCTY',
        'CN',
        'COLLECTOR_BIC',
        'COLLECTOR_IBAN',
        'COMPLUS',
        'CREATION_STATUS',
        'CREDITDEBIT',
        'CURRENCY',
        'CVCCHECK',
        'DCC_COMMPERCENTAGE',
        'DCC_CONVAMOUNT',
        'DCC_CONVCCY',
        'DCC_EXCHRATE',
        'DCC_EXCHRATESOURCE',
        'DCC_EXCHRATETS',
        'DCC_INDICATOR',
        'DCC_MARGINPERCENTAGE',
        'DCC_VALIDHOURS',
        'DEVICEID',
        'DIGESTCARDNO',
        'ECI',
        'ED',
        'EMAIL',
        'ENCCARDNO',
        'FXAMOUNT',
        'FXCURRENCY',
        'IP',
        'IPCTY',
        'MANDATEID',
        'MOBILEMODE',
        'NBREMAILUSAGE',
        'NBRIPUSAGE',
        'NBRIPUSAGE_ALLTX',
        'NBRUSAGE',
        'NCERROR',
        'ORDERID',
        'PAYID',
        'PAYIDSUB',
        'PAYMENT_REFERENCE',
        'PM',
        'SCO_CATEGORY',
        'SCORING',
        'SEQUENCETYPE',
        'SIGNDATE',
        'STATUS',
        'SUBBRAND',
        'SUBSCRIPTION_ID',
        'TRXDATE',
        'VC',
        'WALLET'
    );

    public function getData()
    {
        $data = array();
        foreach ($this->httpRequest->query as $key => $value) {
            $data[strtoupper($key)] = $value;
        }

        if (isset($data['SHASIGN'])) {
            $signData = array();
            foreach ($this->signatureParams as $param) {
                if (isset($data[$param])) {
                    $signData[$param] = $data[$param];
                }
            }

            $hash = Helper::createShaHash(
                $signData,
                $this->getShaOut(),
                $this->getHashingMethod()
            );

            if ($hash != $data['SHASIGN']) {
                throw new InvalidResponseException;
            }
        }

        //TODO: Deal with non-signed response?

        return $data;
    }

    // Sending the 1.x way
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
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
