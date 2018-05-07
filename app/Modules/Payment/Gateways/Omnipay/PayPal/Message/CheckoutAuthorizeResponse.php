<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * PayPal Express Authorize Response
 */
class CheckoutAuthorizeResponse extends Response implements RedirectResponseInterface
{
    protected $liveCheckoutEndpoint = 'https://www.paypal.com/cgi-bin/webscr';
    protected $testCheckoutEndpoint = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    public function isSuccessful()
    {
        return true;
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectUrl()
    {
        return $this->getCheckoutEndpoint().'?'.http_build_query($this->getRedirectQueryParameters(), '', '&');
    }

    public function getTransactionReference()
    {
        return isset($this->data['TOKEN']) ? $this->data['TOKEN'] : null;
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }

    protected function getRedirectQueryParameters()
    {
        $u_phone = '(606) 111-1111';
        return array(
            'cmd' => '_ext-enter',
            "redirect_cmd" => "_xclick",
            "mrb" => "R-2JR83330TB370181P",
            "pal" => "RDGQCFJTT6Y6A",
            "rm" => "2",
            "custom" => '111394',
            "business" => $this->getBusiness(),
            "email" => 'romann@s3stores.com',
            "first_name" => 'Albert',
            "last_name" => 'Einstain',
            "country" => 'US',
            "state" => 'NY',
            "day_phone_a" => substr($u_phone, -10, -7),
            "day_phone_b" => substr($u_phone, -7, -4),
            "day_phone_c" => substr($u_phone, -4),
            "night_phone_a" => substr($u_phone, -10, -7),
            "night_phone_b" => substr($u_phone, -7, -4),
            "night_phone_c" => substr($u_phone, -4),
            "item_name" => 'SKU' . " order # 99111",
            "amount" => sprintf("%0.2f", 1.11),
            "currency_code" => 'USD',
            "bn" => "x-cart"
        );
    }

    protected function getCheckoutEndpoint()
    {
        return $this->getRequest()->getTestMode() ? $this->testCheckoutEndpoint : $this->liveCheckoutEndpoint;
    }

    protected function getBusiness()
    {
        return $this->getRequest()->getTestMode() ? 'igor@s3stores.com' : 'paypal@s3stores.com';
    }
}
