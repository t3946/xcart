<?php

namespace Modules\Payment\Gateways;

class BluePay extends Gateway
{
    public static function getProcessorName()
    {
        return 'BluePay';
    }

    public function init()
    {
        parent::init();

        $this->gateway->setAccountId($this->model->param01);
        $this->gateway->setSecretKey($this->model->param02);
        $this->gateway->setDeveloperMode($this->test_mode);
    }

    public function getLinks()
    {
        return [];
    }

    public function refund($params)
    {
        $this->result = $this->gateway
            ->setToken($params['transaction_id'])
            ->refund([
                'amount' => $params['amount']
            ])->send();
        $data = $this->result->getData();
        if ($this->result->isSuccessful() && ($data['STATUS'] == 1)) {
            return true;
        } else {
            return false;
        }
    }

    public function void($params)
    {
        $this->result = $this->gateway
            ->setToken($params['transaction_id'])
            ->void([])
            ->send();
        $data = $this->result->getData();
        if ($this->result->isSuccessful() && ($data['STATUS'] == 1)) {
            return true;
        } else {
            return false;
        }
    }

    public function lookup($params)
    {
        $result = [];
        if ($params['transaction_status'] == 'authorized') {
            $result = ['links' => [
                ['rel' => 'capture'],
                ['rel' => 'void'],
            ]];
        }
        if ($params['transaction_status'] == 'completed') {
            $result = ['links' => [
                ['rel' => 'refund']
            ]];
        }
        return $result;
    }

    public function authorize($params)
    {

        $this->result = $this->gateway
            ->authorize($params)
            ->send();
        $data = $this->result->getData();
        if ($this->result->isSuccessful() && ($data['STATUS'] == 1)) {
            return true;
        } else {
            return false;
        }
    }
}