<?php

namespace Modules\Payment\Gateways;

class Coinbase extends AbstractGateway
{
    public static function getProcessorName(): string
    {
        return 'Coinbase';
    }

    public function init(): void
    {
        parent::init();

        $this->gateway->setApiKey($this->model->param01);

    }
    public function refund($params): bool
    {
        return false;
    }

    public function void($params): bool
    {
        return false;
    }

    public function capture($params): bool
    {
        return false;
    }

    public function lookup($params): bool
    {
        return false;
    }

    public function authorize($params): bool
    {
        return false;
    }

    public function reauthorize($params): bool
    {
        return false;
    }

    public function purchase($params): bool
    {
        $params['name'] = 'S3 Stores, Inc.';
        $params['description'] = "Order # {$params['order']->getOrderNumber()}";
        $params['redirect_url'] = $params['returnUrl'];
        $params['pricing_type'] = 'fixed_price';
        $params['metadata'] = $params['order']->getOrderNumber();

        $this->result = $this->gateway
            ->purchase($params)
            ->send();

        return $this->result->getData() && !isset($this->result->getData()['error']);
    }

    public function complete($params): bool
    {
        return false;
    }

    public function getState($mode):? string
    {
        return null;
    }
}