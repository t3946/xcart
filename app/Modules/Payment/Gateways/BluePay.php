<?php

namespace Modules\Payment\Gateways;


use DateInterval;
use DateTime;
use Modules\Order\Models\OrderTransactionModel;

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
            ->setToken($params['transactionReference'])
            ->refund($params)->send();
        if ($res = $this->result->isSuccessful()){
            $res = $this->lookup(['transactionReference' => $this->result->getTransactionReference()]);
        }
        return $res;
    }

    public function void($params)
    {
        $this->result = $this->gateway
            ->setToken($params['transactionReference'])
            ->void($params)
            ->send();
        if ($res = $this->result->isSuccessful()){
            $res = $this->lookup(['transactionReference' => $this->result->getTransactionReference()]);
        }
        return $res;
    }

    public function lookup($params)
    {
        $params['transID'] = $params['transactionReference'];
        $params['reportStart'] = (new DateTime())->sub(new DateInterval('P1Y'))->format('Y-m-d');
        $params['reportEnd'] = (new DateTime())->add(new DateInterval('P1D'))->format('Y-m-d');
        $this->result = $this->gateway
            ->lookup($params)
            ->send();
        return $this->result->isSuccessful();

    }

    public function authorize($params)
    {

        $this->result = $this->gateway
            ->authorize($params)
            ->send();
        if ($res = $this->result->isSuccessful()){
            $res = $this->lookup(['transactionReference' => $this->result->getTransactionReference()]);
        }
        return $res;
    }

    public function capture($params)
    {
        $this->result = $this->gateway
            ->setToken($params['transactionReference'])
            ->capture($params)
            ->send();
        if ($res = $this->result->isSuccessful()){
            $res = $this->lookup(['transactionReference' => $this->result->getTransactionReference()]);
        }
        return $res;
    }

    public function getState($mode)
    {
        $state = null;
        if (isset(self::$gatewayMethods[$mode]) && $this->result->isSuccessful()){
            $state = self::$gatewayMethods[$mode]['status'];
        }
        $data = $this->result->getData();
        if (!$state && ($state = $data['state'])) {
            $statuses = array_map(function ($a) {return $a['status'];}, self::$gatewayMethods);
            if (!in_array($state, $statuses)) {
                $state = null;
            }
        }
        return $state;
    }

    /**
     * @param $params
     * @return bool
     */
    public function reauthorize($params)
    {
        // TODO: Implement reauthorize() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function purchase($params)
    {
        // TODO: Implement purchase() method.
    }

    /**
     * @param $params
     * @return bool
     */
    public function complete($params)
    {
        // TODO: Implement complete() method.
    }
}