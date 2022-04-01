<?php

namespace Modules\Payment\Gateways;


use DateInterval;
use DateTime;
use Modules\Order\Stores\OrderTransactionStore;

class BluePay extends AbstractGateway
{
    public static function getProcessorName(): string
    {
        return 'BluePay';
    }

    public function init(): void
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

    public function refund($params): bool
    {
        $this->result = $this->gateway
            ->setToken($params['transactionReference'])
            ->refund($params)->send();
        if ($res = $this->result->isSuccessful()){
            $res = $this->lookup(['transactionReference' => $this->result->getTransactionReference()]);
        }
        return $res;
    }

    public function void($params): bool
    {
        $params['amount'] = str_replace(',', '', $params['amount']);
        $this->result = $this->gateway
            ->setToken($params['transactionReference'])
            ->void($params)
            ->send();
        if ($res = $this->result->isSuccessful()){
            $res = $this->lookup(['transactionReference' => $this->result->getTransactionReference()]);
        }
        return $res;
    }

    public function lookup($params): bool
    {
        $params['transID'] = $params['transactionReference'];
        $params['reportStart'] = (new DateTime())->sub(new DateInterval('P1Y'))->format('Y-m-d');
        $params['reportEnd'] = (new DateTime())->add(new DateInterval('P1D'))->format('Y-m-d');
        $this->result = $this->gateway
            ->lookup($params)
            ->send();
        return $this->result->isSuccessful();

    }

    public function authorize($params): bool
    {

        $this->result = $this->gateway
            ->authorize($params)
            ->send();
        if ($res = $this->result->isSuccessful()){
            $res = $this->lookup(['transactionReference' => $this->result->getTransactionReference()]);
        }
        return $res;
    }

    public function capture($params): bool
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

    public function getState($mode):? string
    {
        $state = null;
        if (isset(OrderTransactionStore::$gatewayMethods[$mode]) && $this->result->isSuccessful()){
            $state = OrderTransactionStore::$gatewayMethods[$mode]['status'];
        }
        $data = $this->result->getData();
        if (!$state && ($state = $data['state'])) {
            $statuses = array_map(function ($a) {return $a['status'];}, OrderTransactionStore::$gatewayMethods);
            if (!in_array($state, $statuses) || !$data['status']) {
                $state = null;
            }
        }
        return $state;
    }

    /**
     * @param $params
     * @return bool
     */
    public function reauthorize($params): bool
    {
        return true;
    }

    /**
     * @param $params
     * @return bool
     */
    public function purchase($params): bool
    {
        return true;
    }

    /**
     * @param $params
     * @return bool
     */
    public function complete($params): bool
    {
        return true;
    }

}