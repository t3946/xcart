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

    /** Group data by column
     * @param array $data
     * @param string $column
     * @return array
     */
    public static function groupByColumn(array $data, string $column): array
    {
        foreach($data as $row) {
            $col = $row[$column];
            unset($row[$column]);
            $result[$col] = [$row];
        }
        return $result ?? [];
    }
}