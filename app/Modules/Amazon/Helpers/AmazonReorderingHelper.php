<?php

namespace Modules\Amazon\Helpers;


use DateTime;
use Modules\Amazon\Sqls\AmazonSql;
use Modules\Product\Models\ProductModel;
use Xcart\Connection;

class AmazonReorderingHelper
{
    public static function getFilterData($data)
    {
        if (!$data || $data['reset']) {
            $data = [];
        }
        return $data;
    }

    public static function calculateAmazonProducts($params)
    {
        return Connection::getInstance()
            ->executeQuery(AmazonSql::getAmazonReorderingSql(), $params)
            ->fetchAll();
    }



    /**
     * @param int $dayOfReorder 1 Monday - 7 Sunday ISO-8601
     * @return int;
     */
    public static function getDaysBeforeNextReorder($dayOfReorder)
    {
        return $dayOfReorder + 7 - (new DateTime())->format('N');
    }
}