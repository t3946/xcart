<?php
namespace Modules\Order\Helpers;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Order\Models\OrderEventsModel;
use Modules\Order\Models\OrderUserLastActivityModel;
use Xcart\App\Main\Xcart;

class OrderHelper
{
    protected static $__events_count = [];
    protected static $__max_eta = [];

    public static function getMaxEtaTimeByOrder(array $ids)
    {
        $keys = array_keys(self::$__max_eta);
        $diff = array_diff($ids, $keys);

        if (!empty($diff)) {
            $connection = Xcart::app()->db->getConnection();
            $max_eta_sql = QueryBuilder::getInstance($connection)->from('xcart_products')
                                       ->select(['max_eta' => new Expression('MAX(t.eta_date_mm_dd_yyyy)'), 'details.orderid'])
                                       ->setAlias('t')
                                       ->join('inner join', 'xcart_order_details', ['t.productid' => 'details.productid'], 'details')
                                       ->where(['details.orderid__in' => $diff, 'eta_date_mm_dd_yyyy__gt' => 0])
                                       ->group(['details.orderid'])->toSQL();

            $orders_max_eta = $connection->fetchAll($max_eta_sql);

            foreach ($orders_max_eta as $item) {
                self::$__max_eta[$item['orderid']] = $item['max_eta'];
            }
        }

        $result = [];
        foreach (self::$__max_eta as $id => $eta) {
            if (in_array($id, $ids)) {

                $result[$id] = $eta;
            }
        }

        return $result;
    }


    public static function getCountEvents(array $ids, $user_id = null, $group = true)
    {
        $need_request = false;

        if (empty($user_id) && Xcart::app()->getIsWebMode())
        {
            $user_id = Xcart::app()->user->id;
        }

        foreach ($ids as $id) {
            $need_request = !isset(self::$__events_count[$id]) || !isset(self::$__events_count[$id][$user_id]);

            if ($need_request) {
                break;
            }
        }

        if ($need_request && $user_id) {

            $connection = Xcart::app()->db->getConnection();

            $qs = static::getEventCountQS($user_id);
            $topAlias = $qs->getTableAlias();

            $sql = $qs->filter(['order_id__in' => $ids,])->group(["{$topAlias}.order_id"])->allSql();

            $counts = $connection->fetchAll($sql);
            if ($counts) {
                foreach ($counts as $item) {
                    self::$__events_count[$item['order_id']][$user_id] = $item['count'];
                }
            }
            foreach ($ids as $id) {
                if (empty(self::$__events_count[$id])) {
                    self::$__events_count[$id][$user_id] = 0;
                }
            }
        }

        $result = [];
        foreach (self::$__events_count as $id => $user_count) {
            if (in_array($id, $ids)) {

                $result[$id] = $user_count[$user_id];
            }
        }

        return ($group) ? $result : array_sum($result);
    }

    /**
     * Return QuerySet without order filtrate
     *
     * @param int $user_id
     *
     * @return \Xcart\App\Orm\Manager
     */
    public static function getEventCountQS($user_id)
    {
        $qs = OrderEventsModel::objects();
        $topAlias = $qs->getTableAlias();

        $qs = $qs
            ->filter([
                new QOr([
                    new QAnd(['a.user_id' => $user_id, new QAnd(new Expression("`{$topAlias}`.`created_at` >= `a`.`created_at`"))]),
                    'a.user_id__isnull' => true
                ]),
                'created_at__gte' => (new \DateTime())->modify('-6 month')
            ])
            ->getQuerySet()
            ->join('left join', OrderUserLastActivityModel::tableName(), ['a.order_id' => 'order_id'], 'a')
            ->select(['order_id', 'count' => new Expression('count(*)')]);

        return $qs;
    }
}