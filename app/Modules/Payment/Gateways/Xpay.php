<?php

namespace Modules\Payment\Gateways;



class Xpay extends Gateway
{
    public static function getProcessorName()
    {
        return 'Xpay';
    }

    public function init()
    {
        parent::init();
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