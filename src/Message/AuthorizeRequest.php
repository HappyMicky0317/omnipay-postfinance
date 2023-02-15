<?php

namespace Omnipay\Postfinance\Message;

class AuthorizeRequest extends PurchaseRequest
{
    public function getData()
    {
        $data = parent::getData();

        // perform an authorize operation
        $data['OPERATION'] = Helper::POSTFINANCE_OPERATION_AUTHORIZE;

        return $data;
    }
}
