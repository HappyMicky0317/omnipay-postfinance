<?php

namespace Omnipay\Postfinance\Message;

class Helper
{
    /**
     * Operational modes
     */
    const POSTFINANCE_OPERATION_SALE                      = 'SAL';
    const POSTFINANCE_OPERATION_AUTHORIZE                 = 'RES';

    /**
     * response status codes
     */
    const POSTFINANCE_INVALID                             = 0;
    const POSTFINANCE_PAYMENT_CANCELED_BY_CUSTOMER        = 1;
    const POSTFINANCE_AUTH_REFUSED                        = 2;

    const POSTFINANCE_ORDER_SAVED                         = 4;
    const POSTFINANCE_AWAIT_CUSTOMER_PAYMENT              = 41;

    const POSTFINANCE_AUTHORIZED                          = 5;
    const POSTFINANCE_AUTHORIZED_WAITING                  = 51;
    const POSTFINANCE_AUTHORIZED_UNKNOWN                  = 52;
    const POSTFINANCE_STAND_BY                            = 55;
    const POSTFINANCE_PAYMENTS_SCHEDULED                  = 56;
    const POSTFINANCE_AUTHORIZED_TO_GET_MANUALLY          = 59;

    const POSTFINANCE_VOIDED                              = 6;
    const POSTFINANCE_VOID_WAITING                        = 61;
    const POSTFINANCE_VOID_UNCERTAIN                      = 62;
    const POSTFINANCE_VOID_REFUSED                        = 63;
    const POSTFINANCE_VOIDED_ACCEPTED                     = 64;

    const POSTFINANCE_PAYMENT_DELETED                     = 7;
    const POSTFINANCE_PAYMENT_DELETED_WAITING             = 71;
    const POSTFINANCE_PAYMENT_DELETED_UNCERTAIN           = 72;
    const POSTFINANCE_PAYMENT_DELETED_REFUSED             = 73;
    const POSTFINANCE_PAYMENT_DELETED_OK                  = 74;
    const POSTFINANCE_PAYMENT_DELETED_PROCESSED_MERCHANT  = 75;

    const POSTFINANCE_REFUNDED                            = 8;
    const POSTFINANCE_REFUND_WAITING                      = 81;
    const POSTFINANCE_REFUND_UNCERTAIN_STATUS             = 82;
    const POSTFINANCE_REFUND_REFUSED                      = 83;
    const POSTFINANCE_REFUND_DECLINED_ACQUIRER            = 84;
    const POSTFINANCE_REFUND_PROCESSED_MERCHANT           = 85;

    const POSTFINANCE_PAYMENT_REQUESTED                   = 9;
    const POSTFINANCE_PAYMENT_PROCESSING                  = 91;
    const POSTFINANCE_PAYMENT_UNCERTAIN                   = 92;
    const POSTFINANCE_PAYMENT_REFUSED                     = 93;
    const POSTFINANCE_PAYMENT_DECLINED_ACQUIRER           = 94;
    const POSTFINANCE_PAYMENT_PROCESSED_MERCHANT          = 95;
    const POSTFINANCE_PAYMENT_IN_PROGRESS                 = 99;

    public static function stringValue($value)
    {
        // ensure a numeric zero gets converted properly
        if ($value === 0) {
            return '0';
        }

        return (string)$value;
    }

    public static function createShaHash(array $data, $signature, $algorithm = 'sha1')
    {
        uksort($data, 'strnatcasecmp');

        $hashParts = array();
        foreach ($data as $key => $value) {
            $str = self::stringValue($value);
            if ($str == '' || $key == 'SHASIGN') {
                continue;
            }
            $hashParts[] = strtoupper($key) . '=' . $str . $signature;
        }

        return strtoupper(hash(strtolower($algorithm), implode('', $hashParts)));
    }
}
