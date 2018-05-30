<?php

namespace Omnipay\Xpay\Message;


use Modules\Order\Helpers\OrderInvoiceHelper;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse implements RedirectResponseInterface
{
    public const REDIRECT_URL = 'https://secure.s3stores.com/xpayments/payment.php';

    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        $this->raw = (string) $data;

        $data = str_replace(['<Response code>', '3dsecure'], ['<Response>', 'threedsecure'], $data); //Bug X-payments?

        $data = json_decode(json_encode((array)simplexml_load_string($data)),1);

        if ($this->data && count($this->data)) {
            $this->data = $data;
        } else {
            throw new InvalidResponseException();
        }
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return isset($this->data['error']) && !$this->data['error'];
    }

    public function isRedirect()
    {
        return true;
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    /**
     * Gets the redirect target url.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return self::REDIRECT_URL;
    }

    /**
     * Gets the redirect form data array, if the redirect method is POST.
     *
     * @return array
     */
    public function getRedirectData()
    {
        return array_merge($this->data, [
            'target' => 'main',
            'action' => 'start',
            'allow_save_card' => 'N',
        ]);
    }

    public function getTransactionReference()
    {
        return $this->data['txnId'];
    }

    public function redirect(): void
    {
        OrderInvoiceHelper::sendOrderStatusNotification($this->getRequest()->getOrder());
        parent::redirect();
    }

}