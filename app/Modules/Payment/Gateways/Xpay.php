<?php

namespace Modules\Payment\Gateways;


use Modules\Core\Models\GlobalConfigModel;

class Xpay extends Gateway
{
    public static function getProcessorName()
    {
        return 'Xpay';
    }

    public function init()
    {
        parent::init();

        $this->gateway->setShoppingCartId(GlobalConfigModel::objects()->get(['name' => 'xpc_shopping_cart_id'])->value);
        $this->gateway->setPublicKey(GlobalConfigModel::objects()->get(['name' => 'xpc_public_key'])->value);
        $this->gateway->setPrivateKey(GlobalConfigModel::objects()->get(['name' => 'xpc_private_key'])->value);
        $this->gateway->setPrivateKeyPassword(GlobalConfigModel::objects()->get(['name' => 'xpc_private_key_password'])->value);
        $this->gateway->setConfigurationId($this->model->param01);
        $this->gateway->setMerchantEmail(GlobalConfigModel::objects()->get(['name' => 'orders_department'])->value);

    }

    public function getLinks()
    {
        return [];
    }

    public function refund($params)
    {

    }

    public function void($params)
    {

    }

    public function lookup($params)
    {

    }

    public function authorize($params)
    {

    }

    public function capture($params)
    {

    }

    public function getState($mode)
    {

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
        $this->result = $this->gateway
            ->purchase($params)
            ->send();

        return $this->result->isSuccessful();
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