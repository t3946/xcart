<?php


namespace Modules\User\Helpers;

use Xcart\App\Main\Xcart;

class DiscountHelper
{
    public const CODE_PARAM = 'discount_end_timestamp';
    public const CODE_PARAM_MINUTES = 'discount_end_minutes';
    public const DISCOUNT_PERIODS = [5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90];

    public static function getDiscountTime()
    {
        $time = Xcart::app()->request->session->get(self::CODE_PARAM) ?? 0;
        $minutes = self::getDiscountMinutes();
        return (($d_time = $time + $minutes * 60 - (new \DateTime('now'))->getTimeStamp()) > 0) ? $d_time : 0;
    }

    public static function getDiscountMinutes()
    {
        $minutes = Xcart::app()->request->session->get(self::CODE_PARAM_MINUTES) ?? 0;
        return ($minutes > 0) ? $minutes : 0;
    }
}