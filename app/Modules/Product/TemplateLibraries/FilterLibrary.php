<?php

namespace Modules\Product\TemplateLibraries;

use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Aggregation\Max;
use Mindy\QueryBuilder\Aggregation\Min;
use Mindy\QueryBuilder\Expression;
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
     * @param QuerySet|Manager $qs    Manager or QuerySet of ProductModel
     * @param array            $types ['price', 'brand']
     *
     * @return array
     * @throws \Exception
     */
    public static function getFilterStructure($qs, array $form_data = [], array $types = ['price', 'brand', 'filter'])
    {
        $list = [];

        if (in_array('price', $types)) {
            $tqs = clone $qs;
            $tqs->with(['quick_prices'])
                ->select([new Min('xcart_pricing_1.price', 'min'),
                          new Max('xcart_pricing_1.price', 'max')]) //@TODO:FIX IT
                ->asArray();
            $prices = $tqs->get();
            $prices = [
                'min' => floor($prices['min']),
                'max' => ceil($prices['max']),
                'step' => 1,
            ];

            $fprice = empty($form_data['price'])?[]:$form_data['price'];

            $list['__price__'] = [
                'type' => 'price',
                'key' => 'price',
                'name' => 'Price',
                'values' => [
                    'prices' => $prices,
                    'selected' => array_replace_recursive($prices, $fprice),
                ],
            ];
        }

        if (in_array('brand', $types)) {
            $tqs = clone $qs;
            $brands = $tqs->select(['name' => 'brand__brand', 'value' => 'brandid', new Count('*', 'count')])
                          ->group(['brandid'])
                          ->order(['brand__brand'])
                          ->asArray()->cache(300)->all();

            foreach ($brands as $key => $brand) {
                $brands[$key]['checked'] = (!empty($form_data['brand']) && in_array($brand['value'],$form_data['brand']));
            }

            $list['__brand__'] = [
                'type' => 'list',
                'key' => 'brand',
                'name' => 'Brand',
                'values' => $brands,
            ];
        }


        if (in_array('filter', $types)) {
            $tqs = clone $qs;
            $tqs = $tqs->filter(['filter_values__fv_active' => 'Y'])->order([]);
            $filters = FilterModel::objects()
                                  ->filter(['f_active' => 'Y',
                                            'f_id__in' => $tqs->select(['filter_values__f_id'])])
                                  ->order(['f_order_by'])
                                  ->cache(300)->valuesList([]);

            if ($filters) {

                $values = $tqs->with(['filter_values'])
                              ->select(['filter_values__fv_name', 'filter_values__fv_id', 'filter_values__f_id', new Count('*', 'count')])
                              ->order(['filter_values__f_id','filter_values__fv_order_by','filter_values__fv_name'])
                              ->group(['filter_values__fv_id'])
                              ->asArray()->cache(300)->all();


                foreach ($filters as $filter)
                {
                    $list[$filter['f_id']] = [
                        'type' => 'list',
                        'key' => 'filter',
                        'name' =>$filter['f_name'],
                        'values' => []
                    ];
                }

                foreach ($values as $value)
                {
                    if ($list[$value['f_id']]) {
                        $list[$value['f_id']]['values'][] = [
                            'name' => $value['fv_name'],
                            'value' => $value['fv_id'],
                            'count' => $value['count'],
                            'checked' => (!empty($form_data['filter']) && in_array($value['fv_id'],$form_data['filter'])),
                        ];
                    }
                }
            }
        }

        return $list;
    }

    /**
     * @param Manager|QuerySet $pqs ProductModel querySet or manager
     * @param array            $form_data
     *
     * @return Manager|QuerySet
     */
    public static function getFiltrateQS($pqs, array $form_data)
    {
        if (!empty($form_data['price']))
        {
            //@TODO: from GREATEST(quick_prices__price, new_map_price)
            //@TODO: Maybe $pqs->filter([ new GREATEST(['quick_prices__price', 'new_map_price'], 'gte', $form_data['price']['min']) ]);
            if (!empty($form_data['price']['min'])) {
                $pqs->filter(['quick_prices__price__gte' => $form_data['price']['min']]);
            }
            if (!empty($form_data['price']['max'])) {
                $pqs->filter(['quick_prices__price__lte' => $form_data['price']['max']]);
            }
        }


        if (!empty($form_data['brand'])) {
            $pqs->filter(['brandid__in' => $form_data['brand']]);
        }


        if (!empty($form_data['filter'])) {
            $pqs->filter(['filter_values__fv_id__in' => $form_data['filter']]);
        }

        return $pqs;
    }
}