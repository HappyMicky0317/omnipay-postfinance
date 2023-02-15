<?php

namespace Omnipay\Postfinance\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Postfinance purchase redirect response
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $endpointTemplate = 'https://e-payment.postfinance.ch/ncol/%s/orderstandard%s.asp';

    public function isRedirect()
    {
        return true;
    }

    public function isSuccessful()
    {
        return false;
    }

    /**
     * Gets the redirect target url.
     */
    public function getRedirectUrl()
    {
        return $this->getCheckoutEndpoint();
    }

    /**
     * Get the required redirect method (either GET or POST).
     */
    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     */
    public function getRedirectData()
    {
        // Build the post data as expected by Postfinance.
        $params = $this->getData();
        $postData = array();
        foreach ($params as $key => $value) {
            $postData[$key] = $value;
        }

        $postData['SHASIGN'] = Helper::createShaHash(
            $postData,
            $this->getRequest()->getShaIn(),
            $this->getRequest()->getHashingMethod()
        );

        return $postData;
    }

    protected function getCheckoutEndpoint()
    {
        $req = $this->getRequest();
        return sprintf(
            $this->endpointTemplate,
            $req->getTestMode() ? 'test' : 'prod',
            strtolower($req->getEncoding()) == 'utf-8' ? '_utf8' : ''
        );
    }
}
