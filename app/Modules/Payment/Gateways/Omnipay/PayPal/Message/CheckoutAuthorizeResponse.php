<?php

namespace Omnipay\PayPal\Message;

use Modules\Order\Helpers\OrderInvoiceHelper;
use Modules\Order\Models\OrderModel;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal Express Authorize Response
 */
class CheckoutAuthorizeResponse extends Response implements RedirectResponseInterface
{
    protected $liveCheckoutEndpoint = 'https://www.paypal.com/cgi-bin/webscr';
    protected $testCheckoutEndpoint = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->data['order'] = $data['order'];
    }

    public function isSuccessful()
    {
        return false;
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
        /** @var OrderModel $order */
        $order = $this->data['order'];

        if (!$order) {
            return [];
        }

        [$first_name, $last_name] = explode(' ', $order->b_firstname, 2);

        return [
            'cmd' => '_ext-enter',
            'redirect_cmd' => '_xclick',
            'mrb' => 'R-2JR83330TB370181P',
            'pal' => 'RDGQCFJTT6Y6A',
            'rm' => "2",
            'custom' => $order->orderid,
            'business' => $this->getBusiness(),
            'email' => $order->email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'day_phone_a' => substr($order->phone, -10, -7),
            'day_phone_b' => substr($order->phone, -7, -4),
            'day_phone_c' => substr($order->phone, -4),
            'night_phone_a' => substr($order->phone, -10, -7),
            'night_phone_b' => substr($order->phone, -7, -4),
            'night_phone_c' => substr($order->phone, -4),
            'item_name' => "S3 Stores, Inc. Order # {$order->getOrderNumber()}",
            'amount' => sprintf("%0.2f", $order->total),
            'currency_code' => 'USD',
            'bn' => 'x-cart',
            'paymentaction' => 'authorization',
            'address1' => $order->b_address,
            "country" => $order->b_country,
            "state" => $order->b_state,
            'city' => $order->b_city,
            'zip' => $order->b_zipcode,
            "return" => $this->getRequest()->getReturnUrl(),
            "cancel_return" => $this->getRequest()->getCancelUrl(),
        ];
    }

    protected function getCheckoutEndpoint()
    {
        return $this->getRequest()->getTestMode() ? $this->testCheckoutEndpoint : $this->liveCheckoutEndpoint;
    }

    protected function getBusiness()
    {
        return $this->getRequest()->getTestMode() ? 'igor@s3stores.com' : 'paypal@s3stores.com';
    }

    public function redirect(): void
    {
        OrderInvoiceHelper::sendOrderStatusNotification($this->getRequest()->getOrder());
        parent::redirect();
    }
}
