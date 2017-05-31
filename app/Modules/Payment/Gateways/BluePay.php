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
}