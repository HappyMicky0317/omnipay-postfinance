<?php

namespace Omnipay\Postfinance\Message;

/**
 * Postfinance abstract request.
 * Implements all property setters and getters.
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Get the PSPID
     * @return string
     */
    public function getPspId()
    {
        return $this->getParameter('pspId');
    }

    /**
     * Set the PSPID parameter. The PSPID is your postfinance account ID
     * This is a mandatory parameter
     * @param string $value your postfinance account ID
     * @return $this
     */
    public function setPspId($value)
    {
        return $this->setParameter('pspId', $value);
    }

    /**
     * Get the SHAIN signature
     * @return string
     */
    public function getShaIn()
    {
        return $this->getParameter('shaIn');
    }

    /**
     * Set the SHA IN parameter
     * @param string $value the SHAIN secret
     * @return $this
     */
    public function setShaIn($value)
    {
        return $this->setParameter('shaIn', $value);
    }

    /**
     * Get the SHAOUT signature
     * @return string
     */
    public function getShaOut()
    {
        return $this->getParameter('shaOut');
    }

    /**
     * Set the SHAOUT parameter
     * @param string $value the SHAOUT secret
     * @return $this
     */
    public function setShaOut($value)
    {
        return $this->setParameter('shaOut', $value);
    }

    /**
     * The hashing method to use for the SHA secrets
     * @return string
     */
    public function getHashingMethod()
    {
        return $this->getParameter('hashingMethod');
    }

    /**
     * Get the payment operation.
     *
     * @return mixed
     */
    public function getOperation()
    {
        return $this->getParameter('operation');
    }

    /**
     * Set the payment operation mode.
     * Valid values are:
     *  - leave blank to use the setting from the Postfinance backend
     *  - 'RES' (request for authorization)
     *  - 'SAL' (request for sale/purchase)
     *  - 'PAU' (request for pre-authorization)
     * @param $value
     * @return $this
     */
    public function setOperation($value)
    {
        return $this->setParameter('operation', $value);
    }

    /**
     * Set the hashing method.
     * Important: this must be set to the same hashing-algorithm that is being set in the postfinance backend
     * @param string $value Valid values are: sha1, sha256, sha512
     * @return $this
     */
    public function setHashingMethod($value)
    {
        return $this->setParameter('hashingMethod', $value);
    }

    /**
     * Get the encoding that will be used by the payment gateway.
     * @return string
     */
    public function getEncoding()
    {
        return $this->getParameter('encoding');
    }

    /**
     * Set the encoding that should be used by the gateway.
     * If you're sending UTF-8 encoded values, make sure to set this to UTF-8!
     * @param string $value Valid values are: ISO-8859-1, UTF-8
     * @return $this
     */
    public function setEncoding($value)
    {
        return $this->setParameter('encoding', $value);
    }

    /**
     * Get the language/locale that should be used for the customer
     * @return string
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Set the language/locale that should be used for the customer
     * @param string $value a locale like: en_US, fr_FR etc.
     * @return $this
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    /**
     * Get the custom template file parameter.
     * @return string
     */
    public function getTP()
    {
        return $this->getParameter('tp');
    }

    /**
     * Set the custom template file parameter.
     * Scope: Look & Feel of the payment page.
     * @param string $value
     *     Can be an absolute URL to your dynamic template,
     *     Can be set to: template_STD_postfinance_1_mobile.htm to use the default Mobile template
     *     Can be left blank to use the default template.
     * @return $this
     */
    public function setTP($value)
    {
        return $this->setParameter('tp', $value);
    }

    /**
     * Get the template title parameter
     * @return string
     */
    public function getTitle()
    {
        return $this->getParameter('title');
    }

    /**
     * Set the template title parameter
     * @param string $value the template title (eg. name of your shop)
     * @return $this
     */
    public function setTitle($value)
    {
        return $this->setParameter('title', $value);
    }

    /**
     * Get the template background-color (defaults to white)
     * @return string
     */
    public function getBgColor()
    {
        return $this->getParameter('bgColor');
    }

    /**
     * Set the template background-color.
     * @param string $value a hex color value, eg. #FFFFFF
     * @return $this
     */
    public function setBgColor($value)
    {
        return $this->setParameter('bgColor', $value);
    }

    /**
     * Get the template text-color (defaults to black)
     * @return string
     */
    public function getTxtColor()
    {
        return $this->getParameter('txtColor');
    }

    /**
     * Set the template text-color
     * @param string $value a hex color value, eg. #000000
     * @return $this
     */
    public function setTxtColor($value)
    {
        return $this->setParameter('txtColor', $value);
    }

    /**
     * Get the template table-background-color (defaults to white)
     * @return string
     */
    public function getTblBgColor()
    {
        return $this->getParameter('tblBgColor');
    }

    /**
     * Set the template table-background-color (defaults to white)
     * @param string $value a hex color value, eg. #FFFFFF
     * @return $this
     */
    public function setTblBgColor($value)
    {
        return $this->setParameter('tblBgColor', $value);
    }

    /**
     * Get the table-background-color for left column (iPhone template) (defaults to #00467F)
     * @return string
     */
    public function getHdTblBgColor()
    {
        return $this->getParameter('hdTblBgColor');
    }

    /**
     * Set the table-background-color for left column (iPhone template) (defaults to #00467F)
     * @param string $value a hex color value, eg. #00467F
     * @return $this
     */
    public function setHdTblBgColor($value)
    {
        return $this->setParameter('hdTblBgColor', $value);
    }

    /**
     * Get the template table-text-color (defaults to black)
     * @return string
     */
    public function getTblTxtColor()
    {
        return $this->getParameter('tblTxtColor');
    }

    /**
     * Set the template table-text-color (defaults to black)
     * @param string $value a hex color value, eg. #000000
     * @return $this
     */
    public function setTblTxtColor($value)
    {
        return $this->setParameter('tblTxtColor', $value);
    }

    /**
     * Get the table-text-color for left column (iPhone template) (defaults to white)
     * @return string
     */
    public function getHdTblTxtColor()
    {
        return $this->getParameter('hdTblTxtColor');
    }

    /**
     * Set the table-text-color for left column (iPhone template) (defaults to white)
     * @param string $value a hex color value, eg. #FFFFFF
     * @return $this
     */
    public function setHdTblTxtColor($value)
    {
        return $this->setParameter('hdTblTxtColor', $value);
    }

    /**
     * Get the template button background-color
     * @return string
     */
    public function getButtonBgColor()
    {
        return $this->getParameter('buttonBgColor');
    }

    /**
     * Set the template button background-color
     * @param string $value a hex color value, eg. #FFFFFF
     * @return $this
     */
    public function setButtonBgColor($value)
    {
        return $this->setParameter('buttonBgColor', $value);
    }

    /**
     * Get the template button text-color (defaults to black)
     * @return string
     */
    public function getButtonTxtColor()
    {
        return $this->getParameter('buttonTxtColor');
    }

    /**
     * Set the template button text-color (defaults to black)
     * @param string $value a hex color value, eg. #000000
     * @return $this
     */
    public function setButtonTxtColor($value)
    {
        return $this->setParameter('buttonTxtColor', $value);
    }

    /**
     * Get the template font (defaults to Verdana)
     * @return string
     */
    public function getFontType()
    {
        return $this->getParameter('fontType');
    }

    /**
     * Set the template font
     * @param string $value font face to use. A value such as "Arial" or "Verdana"
     * @return $this
     */
    public function setFontType($value)
    {
        return $this->setParameter('fontType', $value);
    }

    /**
     * Get the font for left column (iPhone template) (defaults to Verdana)
     * @return string
     */
    public function getHdFontType()
    {
        return $this->getParameter('hdFontType');
    }

    /**
     * Set the font for left column (iPhone template) (defaults to Verdana)
     * @param string $value font face to use. A value such as "Arial" or "Verdana"
     * @return $this
     */
    public function setHdFontType($value)
    {
        return $this->setParameter('hdFontType', $value);
    }

    /**
     * Get the template logo. Must be stored on an https:// server
     * @return string
     */
    public function getLogo()
    {
        return $this->getParameter('logo');
    }

    /**
     * Set the template logo.
     * @param string $value absolute URL to the logo to show on the payment page. Only https:// urls are accepted.
     * @return $this
     */
    public function setLogo($value)
    {
        return $this->setParameter('logo', $value);
    }
}
