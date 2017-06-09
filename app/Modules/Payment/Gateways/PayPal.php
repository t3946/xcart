<?php

namespace Modules\Payment\Gateways;

use Modules\Core\Models\GlobalConfigModel;

class PayPal extends Gateway
{
    public static function getProcessorName()
    {
        return 'PayPal_Rest';
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
        // TODO: Implement refund() method.
    }

    public function void($params)
    {
        // TODO: Implement void() method.
    }

    public function capture($params)
    {
        $this->result = $this->gateway
            ->capture(array_merge($params, ['transactionReference' => $params]))
            ->send();
        return $this->result->isSuccessful();
    }

    public function lookup($params)
    {
        // TODO: Implement lookup() method.
    }

    public function authorize($params)
    {
        $this->result = $this->gateway
            ->authorize($params)
            ->send();
        return $this->result->isSuccessful();
    }
}