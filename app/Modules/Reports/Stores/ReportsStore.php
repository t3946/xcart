<?php

namespace Modules\Reports\Stores;

use Mindy\QueryBuilder\Aggregation\Avg;
use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Aggregation\Sum;
use Mindy\QueryBuilder\Expression;
use Modules\Dashboard\Stores\OrderSearchStore;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;
use Xcart\Connection;

class ReportsStore extends OrderSearchStore
{
    public $defaultPagerPageSize = 100;


    public static function getGroupsNames()
    {
        return [
            'storefront' => [
                'name' => 'Storefront',
                'avail_aggregates' => [
                    'qty',
                    'amount',
                    'f_total',
                    'subtotal',
                    'shipping',
                    'profit',
                    'avg_profit',
                    'avg_check',
                ]
            ],
            'distributor' => [
                'name' => 'Distributor',
                'avail_aggregates' => [
                    'qty',
                    'amount',
                    'f_total',
                    'subtotal',
                    'shipping',
                    'profit',
                    'avg_profit',
                    'avg_check',
                ]
            ],

            'brand' => [
                'name' => 'Brand',
                'avail_aggregates' => [
                    'subtotal',
                    'qty',
                    'amount'
                ]
            ]
        ];
    }

    public static function getAggregates()
    {
        return [
            'qty' => [
                'name' => 'Orders count',
                'prefix' => '',
                'suffix' => '',
            ],
            'f_total' => [
                'name' => 'Total',
                'prefix' => '$',
                'suffix' => '',
            ],
            'subtotal' => [
                'name' => 'Subtotal',
                'prefix' => '$',
                'suffix' => '',
            ],
            'shipping' => [
                'name' => 'Shipping Cost',
                'prefix' => '$',
                'suffix' => '',
            ],
            'profit' => [
                'name' => 'Profit $',
                'prefix' => '$',
                'suffix' => '',
            ],
            'avg_check' => [
                'name' => 'Avg. check',
                'prefix' => '$',
                'suffix' => '',
            ],
            /*'avg_profit' => [
                'name' => 'AVG Profit %',
                'prefix'  => '',
                'suffix' => '%',
            ],
            'amount' => [
                'name' => 'Amount',
                'prefix' => '',
                'suffix' => '',
            ],*/
        ];
    }

    public static function getAggregatesFields()
    {
        return [
            'qty' => new Count('orderid'),
            'amount' => '',
            'f_total' => new Sum('group.total_net'),
            'subtotal' => new Expression('SUM(order_details.price * order_details.amount)'),
            'shipping' => new Sum('group.shipping_net'),
            'profit' => new Sum('group.accounting_net_5_profit'),
            'avg_profit' => '',
            'avg_check' => new Avg('total'),
        ];
    }

    public function getQuerySet()
    {
        $filter = null;
        $qs = parent::getQuerySet();
        $order = ['date'];
        $joins = $qs->getQueryBuilder()->getJoins();
        $joins = array_keys($joins);
        if (!in_array('group', $joins)) {
            $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
        }
        switch ($this->form_data['report']['profit_margin']) {
            case "profit" :
                $filter = ['group.profit_margin__lt' => 100];
                break;
            case "profit15" :
                $filter = ['group.profit_margin__lte' => $this->form_data['order']['profit_margin_profit15_edit']];
                break;
            case "profit_between" :
                $filter = [
                    'group.profit_margin__gte' => $this->form_data['report']['profit_margin_profitbetween_start'],
                    'group.profit_margin__lt' => $this->form_data['report']['profit_margin_profitbetween_end'],
                ];
                break;
        }

        if (!empty($this->form_data['report']['group_settings'])) {
            $qs->group([]);
            $qs->select([]);
            ksort($this->form_data['report']['group_settings']);
            foreach ($this->form_data['report']['group_settings'] as $group_index => $group) {
                switch ($group) {
                    case 'storefront':
                        if (!in_array($group, $joins)) {
                            $qs->join('left join', 'xcart_storefronts', ['storefrontid' => "{$group}.storefrontid"], $group);
                        }
                        $qs->addSelect([$group => new Expression("COALESCE ($group.domain , 'www.artistsupplysource.com')")]);
                        $qs->addGroup(["{$group}.storefrontid"]);
                        if ($group_index != count($this->form_data['report']['group_settings'])) {
                            $order = ["$group.domain"];
                        }
                        break;
                    case 'brand':
                        if (!in_array($group, $joins)) {
                            $qs->join('inner join', 'xcart_order_details', ['orderid' => "order_details.orderid"], 'order_details');
                            $qs->join('inner join', 'xcart_products', ['products.productid' => "order_details.productid"], 'products');
                            $qs->join('inner join', 'xcart_brands', ['products.brandid' => "{$group}.brandid"], $group);
                        }
                        $qs->addSelect([$group => "$group.brand"]);
                        $qs->addGroup(["{$group}.brandid"]);
                        if ($group_index != count($this->form_data['report']['group_settings'])) {
                            $order = ["$group.brand"];
                        }
                        break;
                    case 'distributor':
                        if (!in_array($group, $joins)) {
                            $qs->join('inner join', 'xcart_manufacturers', ['group.manufacturerid' => "{$group}.manufacturerid"], $group);
                        }
                        $qs->addSelect([$group => "$group.manufacturer"]);
                        $qs->addGroup(["{$group}.manufacturerid"]);
                        if ($group_index != count($this->form_data['report']['group_settings'])) {
                            $order = ["$group.manufacturer"];
                        }
                        break;
                }

            }
        }

        if (!empty($this->form_data['report']['aggregate_settings'])) {
            $agg_oreder = [];
            $agg = self::getAggregatesFields();
            foreach ($this->form_data['report']['aggregate_settings'] as $aggregate_index => $aggregate_settings) {
                $aggr_enable = true;
                if ($this->form_data['report']['group_settings']) {
                    $groups = ReportsStore::getGroupsNames();
                    foreach ($this->form_data['report']['group_settings'] as $group_index => $group) {
                        if (!in_array($aggregate_settings, $groups[$group]['avail_aggregates'])){
                            $aggr_enable = false;
                            break;
                        }
                    }
                }
                if ($aggr_enable) {
                    $qs->addSelect([$aggregate_settings => $agg[$aggregate_settings]]);
                    $agg_oreder[] = "-" . $aggregate_settings;
                }
            }
            if ($agg_oreder) {
                krsort($agg_oreder);
            }
            $order = array_merge($order, $agg_oreder);
        }

        if ($filter) {
            $qs->filter($filter);
        }

        $qs->order($order);

        return $qs;
    }

    public function getPager()
    {
        if (!$this->pager) {
            $this->pager = new Pagination($this->getQuerySet(), ['pageSize' => $this->defaultPagerPageSize], new QuerySetDataSource());
        }

        return $this->pager;
    }

    public function getTotals()
    {
        $qsum = clone $this->getQuerySet();
        $qsum->join('inner join', 'xcart_manufacturers', ['m.manufacturerid' => 'group.manufacturerid'], 'm');
        $qsum->group([]);
        $qsum->select([
            new Sum('group.total_gross', 'total_gross'),
            new Sum('group.total_net', 'total_net'),
            new Sum('group.total_gst', 'total_gst'),
            new Sum('group.total_pst', 'total_pst'),
            new Sum('group.accounting_net_0', 'accounting_net_0'),
            new Sum('group.accounting_gst_0', 'accounting_gst_0'),
            new Sum('group.accounting_pst_0', 'accounting_pst_0'),
            new Sum('group.accounting_gross_0', 'accounting_gross_0'),
            new Sum('group.accounting_net_1_cost_to_us', 'accounting_net_1_cost_to_us'),
            new Sum('group.accounting_gst_1_cost_to_us', 'accounting_gst_1_cost_to_us'),
            new Sum('group.accounting_pst_1_cost_to_us', 'accounting_pst_1_cost_to_us'),
            new Sum('group.accounting_gross_1_cost_to_us', 'accounting_gross_1_cost_to_us'),
            new Sum('group.accounting_net_2_shipping', 'accounting_net_2_shipping'),
            new Sum('group.accounting_gst_2_shipping', 'accounting_gst_2_shipping'),
            new Sum('group.accounting_pst_2_shipping', 'accounting_pst_2_shipping'),
            new Sum('group.accounting_gross_2_shipping', 'accounting_gross_2_shipping'),
            new Sum('group.accounting_net_3_ref_to_cust', 'accounting_net_3_ref_to_cust'),
            new Sum('group.accounting_gst_3_ref_to_cust', 'accounting_gst_3_ref_to_cust'),
            new Sum('group.accounting_pst_3_ref_to_cust', 'accounting_pst_3_ref_to_cust'),
            new Sum('group.accounting_gross_3_ref_to_cust', 'accounting_gross_3_ref_to_cust'),
            new Sum('group.accounting_net_4_ref_to_us', 'accounting_net_4_ref_to_us'),
            new Sum('group.accounting_gst_4_ref_to_us', 'accounting_gst_4_ref_to_us'),
            new Sum('group.accounting_pst_4_ref_to_us', 'accounting_pst_4_ref_to_us'),
            new Sum('group.accounting_gross_4_ref_to_us', 'accounting_gross_4_ref_to_us'),
            new Sum('group.accounting_net_5_profit', 'accounting_net_5_profit'),
            new Sum('group.accounting_gst_5_profit', 'accounting_gst_5_profit'),
            new Sum('group.accounting_pst_5_profit', 'accounting_pst_5_profit'),
            new Sum('group.accounting_gross_5_profit', 'accounting_gross_5_profit'),
            'codes' => new Expression("GROUP_CONCAT(DISTINCT m.code ORDER BY m.code SEPARATOR ', ')")
        ]);

        $totals = Connection::getInstance()->fetchAssoc($qsum->getSQL());
        if ($totals) {
            if (floatval($totals['accounting_net_0']) != 0) {
                $totals['total_margin'] = round($totals['accounting_net_5_profit'] / $totals['accounting_net_0'] * 100, 2);
            }
            if (floatval($totals['accounting_gross_0']) != 0) {
                $totals['real_pm'] = round($totals['accounting_gross_5_profit'] / $totals['accounting_gross_0'] * 100, 2);
            }
            $totals["real_net"] = $totals['accounting_net_0'] + $totals['accounting_net_4_ref_to_us'] - $totals['accounting_gross_3_ref_to_cust'];
        }
        return $totals;
    }

    private function _group_by($array, $key)
    {
        $return = array();
        foreach ($array as $val) {
            $newkey = $val[$key];
            unset($val[$key]);
            $return[$newkey][] = $val;
        }
        return $return;
    }

    public function getReport()
    {
        $totals = Connection::getInstance()->executeQuery($this->getQuerySet()->getSQL())->fetchAll(\PDO::FETCH_GROUP);
        if ($totals) {
            uasort($totals, function ($a, $b) {
                $sa = $sb = [];
                if (!empty($this->form_data['report']['aggregate_settings'])) {
                    foreach ($this->form_data['report']['aggregate_settings'] as $aggregate_index => $aggregate_settings) {
                        foreach ($a as $ar) {
                            $sa[$aggregate_settings] += $ar[$aggregate_settings];
                        }
                        foreach ($b as $ar) {
                            $sb[$aggregate_settings] += $ar[$aggregate_settings];
                        }
                    }
                }
                return end($sa) < end($sb);
            });
        }
        return $totals;
    }
}