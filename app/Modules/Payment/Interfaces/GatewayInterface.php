<?php

namespace Modules\Payment\Interfaces;


interface GatewayInterface
{
    public static function getProcessorName(): string;
    public static function isPartiallyCaptureEnabled();
    public function init(): void;

    /**
     * @param $params
     * @return bool
     */
    public function refund($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function void($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function capture($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function lookup($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function authorize($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function reauthorize($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function purchase($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function complete($params): bool;
    /**
     * @param $params
     * @return bool
     */
    public function success($params): bool;

    public function getState($mode):? string;
}