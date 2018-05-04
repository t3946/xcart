<?php

namespace Modules\Payment\Interfaces;


interface GatewayInterface
{
    public static function getProcessorName();
    public static function isPartiallyCaptureEnabled();
    public function init();

    /**
     * @param $params
     * @return bool
     */
    public function refund($params);
    /**
     * @param $params
     * @return bool
     */
    public function void($params);
    /**
     * @param $params
     * @return bool
     */
    public function capture($params);
    /**
     * @param $params
     * @return bool
     */
    public function lookup($params);
    /**
     * @param $params
     * @return bool
     */
    public function authorize($params);
    /**
     * @param $params
     * @return bool
     */
    public function reauthorize($params);
    /**
     * @param $params
     * @return bool
     */
    public function purchase($params);
    /**
     * @param $params
     * @return bool
     */
    public function complete($params);
    /**
     * @param $params
     * @return bool
     */
    public function success($params);
}