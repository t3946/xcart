<?php

namespace Modules\Payment\Gateways;

use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Models\OrderTransactionModel;

class PayPal extends Gateway
{
    public static function getProcessorName()
    {
        return 'PayPal';
    }

    public function init()
    {
        parent::init();

        $mode = current(GlobalConfigModel::objects()->filter(['name' => 'debug_mode'])->valuesList(['value'], true));
        $this->gateway->initialize([
            'testMode' => ($mode == 'Y')
        ]);
        switch ($mode) {
            case 'Y' :
                $this->gateway->setClientId(current(GlobalConfigModel::objects()->filter(['name' => 'sandbox_client_id'])->valuesList(['value'], true)));
                $this->gateway->setSecret(current(GlobalConfigModel::objects()->filter(['name' => 'sandbox_secret_key'])->valuesList(['value'], true)));
                break;
            default:
                $this->gateway->setClientId(current(GlobalConfigModel::objects()->filter(['name' => 'live_client_id'])->valuesList(['value'], true)));
                $this->gateway->setSecret(current(GlobalConfigModel::objects()->filter(['name' => 'live_secret_key'])->valuesList(['value'], true)));
                break;
        }

        $this->gateway->getHttp;

    }

    public function refund($params)
    {
        $this->result = $this->gateway
            ->refundCapture($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function void($params)
    {
        $this->result = $this->gateway
            ->void($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function capture($params)
    {
        $this->result = $this->gateway
            ->capture($params)
            ->send();
        return $this->result->isSuccessful();
    }

    public function lookup($params)
    {
        switch($params['status']) {
            case OrderTransactionModel::STATUS_AUTHORIZED :
                $params['statusLookup'] = 'authorization';
            break;
            case OrderTransactionModel::STATUS_COMPLETED :
            case OrderTransactionModel::STATUS_REFUNDED :
                $params['statusLookup'] = 'capture';
                break;
        }
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
        return $this->result->isSuccessful();
    }

    public function getState($mode)
    {
        $state = null;
        $data = $this->result->getData();

        if (isset(self::$gatewayMethods[$mode])){
            $state = self::$gatewayMethods[$mode]['status'];
        }
        if (!$state && ($state = $data['state'])) {
            $statuses = array_map(function ($a) {return $a['status'];}, self::$gatewayMethods);
            if (!in_array($state, $statuses)) {
                switch ($data['intent']) {
                    case 'authorize':
                        return OrderTransactionModel::STATUS_AUTHORIZED;
                        break;
                    case 'capture':
                        return OrderTransactionModel::STATUS_COMPLETED;
                        break;
                }
            }
        }
        switch ($data['name']){
            case 'AUTHORIZATION_EXPIRED' :
                return OrderTransactionModel::STATUS_EXPIRED;
                break;
            case 'AUTHORIZATION_ALREADY_COMPLETED' :
                return OrderTransactionModel::STATUS_COMPLETED;
                break;
        }
        return $state;
    }
}