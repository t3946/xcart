<?php

namespace Modules\Reports\Helpers;


use Modules\Reports\Stores\ReportsStore;

class ReportsHelper
{
    public static function getFormAndListData()
    {
        $properties = [
            'group_models' => ReportsStore::getGroupsNames(),
            'aggregate_settings' => ReportsStore::getAggregates(),
        ];
        return $properties;
    }
}