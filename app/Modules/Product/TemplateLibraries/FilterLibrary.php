<?php

namespace Modules\Product\TemplateLibraries;

use Mindy\QueryBuilder\Aggregation\Max;
use Mindy\QueryBuilder\Aggregation\Min;
use Modules\Product\Models\FilterModel;
use Xcart\App\Orm\Manager;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Template\TemplateLibrary;

class FilterLibrary extends TemplateLibrary
{

    /**
     * @kind accessorFunction
     * @name getFilterStructure
     *
     * @param QuerySet|Manager $qs Manager or QuerySet of ProductModel
     * @return array
     */
    public static function getFilterStructure($qs, array $types = ['price', 'brand'])
    {
        $list = [];

        if (in_array('price', $types)) {
            $tqs = clone $qs;
            $tqs->with(['quick_prices']);
            $tqs->select([new Min('xcart_pricing_1.price', 'min'), new Max('xcart_pricing_1.price', 'max')]); //@TODO:FIX IT
            $tqs->asArray();;
            $list[] = [
                'type' => 'price',
                'name' => 'Price',
                'values' => $tqs->get()
            ];
//            $qs->select([new Min(''), new Max('')]);
        }

//        if ($values = $this->filter_values->filter(['fv_active' => 'Y'])->order(['f_id','fv_order_by'])->all()) {
//
//            $filters = FilterModel::objects()->filter(['f_id__in' => array_map(function($value){ return $value->f_id; }, $values)])->order(['f_order_by'])->all();
//
//            $list = [];
//            foreach ($filters as $filter)
//            {
//                $list[$filter->f_id] = ['name' =>$filter->f_name, 'values' => []];
//            }
//
//            foreach ($values as $value)
//            {
//                if ($list[$value->f_id]) {
//                    $list[$value->f_id]['values'][] = $value->fv_name;
//                }
//            }

//        }

        func_dump($list);

        return $list;
    }
}