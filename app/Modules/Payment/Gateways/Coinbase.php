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

    }
    public function refund($params): bool
    {
        return true;
    }

    public function void($params): bool
    {
        return true;
    }

    public function capture($params): bool
    {
        return true;
    }

    public function lookup($params): bool
    {
        return true;
    }

    public function authorize($params): bool
    {
        return true;
    }

    public function reauthorize($params): bool
    {
        return true;
    }

    public function purchase($params): bool
    {
        return true;
    }

    public function complete($params): bool
    {
        return true;
    }

    public function getState($mode)
    {
        // TODO: Implement getState() method.
    }
}