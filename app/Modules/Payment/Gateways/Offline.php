<?php

namespace Modules\Payment\Gateways;



use Omnipay\Offline\Message\Response;

class Offline extends AbstractGateway
{
    public static function getProcessorName(): string
    {
        return 'Offline';
    }

    /**
     * @param $params
     * @return bool
     */
    public function refund($params): bool
    {
        return true;
    }

    /**
     * @param $params
     * @return bool
     */
    public function void($params): bool
    {
        return true;
    }

    /**
     * @param $params
     * @return bool
     */
    public function capture($params): bool
    {
        return true;
    }

    /**
     * @param $params
     * @return bool
     */
    public function lookup($params): bool
    {
        return true;
    }

    /**
     * @param $params
     * @return bool
     */
    public function authorize($params): bool
    {
        return true;
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
        $this->result = new Response($this->gateway->purchase($params), $params);

        return false;
    }

    /**
     * @param $params
     * @return bool
     */
    public function complete($params): bool
    {
        return true;
    }

    public function getState($mode):? string
    {

    }
}



