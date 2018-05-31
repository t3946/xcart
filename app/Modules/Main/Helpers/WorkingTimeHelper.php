<?php
/**
 * Класс для определения рабочего времени
 * Created by PhpStorm.
 * User: anna
 * Date: 31.05.2018
 * Time: 9:26
 */

namespace Modules\Main\Helpers;


use DateTime;
use Modules\Distributor\Models\RequestAvailabilityOptionModel;

class WorkingTimeHelper
{

    /**
     * Суббота и воскресенье (N)
     * Порядковый номер дня недели в соответствии со стандартом ISO-8601
     */
    const N_SATURDAY = 6;
    const N_SUNDAY = 7;

    /**
     * В данный момент рабочий день и рабочее время
     * @return bool
     */
    public static function workingDayTimeNow(): bool
    {
        return static::workingDayTime(static::getDayTimeNow());
    }

    /**
     * Текущая дата и время
     * @return DateTime
     */
    public static function getDayTimeNow(): DateTime
    {
        return (new DateTime())->setTimestamp(time());
    }

    /**
     * День рабочий и идет рабочее время
     * @param $dateTime DateTime
     * @return bool
     */
    public static function workingDayTime(DateTime $dateTime): bool
    {
        return static::workingTime($dateTime) && static::workingWeekDay($dateTime) && !static::holiday($dateTime);
    }

    /**
     * Идет рабочее время
     * @param $dateTime DateTime
     * @return bool
     */
    public static function workingTime(DateTime $dateTime): bool
    {
        $startTime = new DateTime('08:30');
        $endTime = new DateTime('16:30');

        return $dateTime >= $startTime && $dateTime <= $endTime;
    }

    /**
     * День недели является рабочим
     * @param $dateTime DateTime
     * @return bool
     */
    public static function workingWeekDay(DateTime $dateTime): bool
    {
        return !in_array(intval($dateTime->format( 'N' )), [self::N_SATURDAY, self::N_SUNDAY]);
    }

    /**
     * День является праздничным
     * @param $dateTime DateTime
     * @return mixed
     */
    public static function holiday(DateTime $dateTime): bool
    {
        return RequestAvailabilityOptionModel::objects()->get([
            'date_mm_dd_yyyy' => $dateTime->format('m/d/Y'),
            'active' => 'Y'
        ]) ? true : false;
    }
}