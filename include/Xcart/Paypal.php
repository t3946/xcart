<?php
namespace Xcart;

class Paypal
{
    private $sAccessToken;

    public function __construct()
    {
        $this->fetchPayPalToken();
    }

    private function fetchPayPalToken()
    {
        x_load('order');
        $this->sAccessToken = func_paypal_get_access_token();
        if (empty($this->sAccessToken))
            throw new \Exception('Paypal Access Token - failed');
    }

    public function captureTransaction($authorized_transaction_id, $data_arr)
    {
        return func_paypal_capture($this->sAccessToken, $authorized_transaction_id, $data_arr);
    }

}