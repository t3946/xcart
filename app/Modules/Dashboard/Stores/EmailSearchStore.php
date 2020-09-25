<?php
namespace Modules\Dashboard\Stores;

use DateTime;
use Mindy\QueryBuilder\Aggregation\Count;
use Mindy\QueryBuilder\Aggregation\Max;
use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QAndNot;
use Mindy\QueryBuilder\Q\QOr;
use Mindy\QueryBuilder\QueryBuilder;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Pagination\Pagination;
use Modules\Forms\Models\EmailModel;
use Modules\Order\Helpers\OrderHelper;
use Modules\Order\Models\OrderModel;
use Modules\Goods\Models\ProductQuestionModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;

use Xcart\App\Store\BaseStore;
use Xcart\Connection;

class EmailSearchStore extends BaseStore
{
    const CONST_MANUAL_STRING      = '=> ';
    const CONST_MANUAL_VIEW_STTINR = '-> ';

    const CONST_CACHE_KEY_EVENT = 'order_search_store_events_count_';
    const CONST_CACHE_KEY_PRIORITY = 'order_search_store_priority_count_';


    protected $form_data = [];
    private $where = [];
    private $having = [];
    /** @var QuerySet */
    private $qs;
    /** @var Pagination */
    protected $pager;
    private $order;
    private $sort;
    private $model = null;

    public $defaultPagerPageSize = 25;

    public static function getFeatures()
    {
        return [
            'mobile_added'     => 'Orders with products added via mobile-storefront',
            'gc_applied'       => 'Entirely or partially paid by Gift Certificate',
            'discount_applied' => 'Global discount applied',
            'coupon_applied'   => 'Discount coupon applied',
            'free_ship'        => 'Free shipping',
            'free_tax'         => 'Tax exempt',
            'gc_ordered'       => 'Gift Certificates purchased',
            'notes'            => 'Orders that have notes assigned',
        ];
    }

    public static function getSources()
    {
        return [
            'xcart_orders_only'  => 'S3 Stores websites',
            'amazon_orders_only' => 'Amazon website',
            'amazon_orders_MFN'  => 'Amazon - MFN',
            'amazon_orders_FBA'  => 'Amazon - FBA',
            'amazon_orders_FB'  => 'Amazon - FB',
        ];
    }

    public static function getReconciliationStatuses()
    {
        return [
            'F' => 'Fully reconciled',
            'FP' => 'Fully or partially reconciled',
            'P' => 'Partially reconciled',
            'N' => 'Not reconciled',
        ];
    }

    public static function getQuestionStatuses()
    {
        return ProductQuestionModel::getFields()['status']['choices'];
    }

    public function __construct($data, Model $model = null)
    {
        $this->form_data = $data;

        if ($model) {
            $this->model = $model;
        }

        $this->sort = isset($_GET['PageSort']) ? (int)$_GET['PageSort'] : null;

        $this->populate($data);
    }

    public function __clone()
    {
        $clone = clone $this;
        $clone->qs = clone $this->qs;
        return $clone;
    }

    public function setOrder(array $order = [])
    {
        $this->order = $order;
        return $this;
    }

    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    private function checkNot($type)
    {
        $type = explode('.', $type);

        if (count($type) == 2) {
            $not = (!empty($this->form_data['not'][$type[0]][$type[1]]));
        }
        else {
            $not = (!empty($this->form_data['not'][$type[0]]));
        }

        return $not;
    }

    /**
     * @param array $where
     * @param string $type
     */
    private function getQ(array $where, $type)
    {
        if (!empty($where))
        {
            $not = $this->checkNot($type);

            $this->where[] = ($not) ? new QAndNot($where) : new QAnd($where);
        }
    }

    /**
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function populate(array $data)
    {
        $qs = $this->getQuerySet();

        if (!empty($data['email'])) {
            $filter = [];
            $field = $data['email']['field'];
            if ($data['email']['condition'] === 'contains') {
                $field .= '__contains';
            }
            $filter[$field] = $data['email']['value'];
            $this->where = $filter;
        }
        $qs->filter($this->where)->having($this->having);
        $this->qs = $qs;
    }

    private function arrLikeToLookup($data, $fields)
    {
        foreach ($data as $k => $v)
        {
            $t = [];

            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $t[$field . '__contains'] = $v;
                }
            }
            else {
                $t[$fields . '__contains'] = $v;
            }

            $data[$k] = new QOr($t);
        }

        return $data;
    }

    public static function explodeInOrLike($data, $clean = true)
    {
        $tmp_like = [];
        $tmp_in = [];
        $len_prefix = strlen(self::CONST_MANUAL_STRING);

        if (!empty($data))
        {
            if (is_array($data))
            {
                foreach ($data as $v) {
                    $v = html_entity_decode($v);

                    if (strpos($v, self::CONST_MANUAL_STRING) === 0) {
                        $tmp_like[] = $clean ? substr($v, $len_prefix) : $v;
                    }
                    else {
                        $tmp_in[] = $v;
                    }
                }
            }
            else {
                if (strpos($data, self::CONST_MANUAL_STRING) === 0) {
                    $tmp_like[] = $clean ? substr($data, $len_prefix) : $data;
                }
                else {
                    $tmp_like[] = $data;
                }
            }
        }

        return [$tmp_in, $tmp_like];
    }

    public static function getClearedData($data)
    {
        return self::clearRecursive($data);
    }

    private static function clearRecursive($data)
    {
        if (is_array($data) )
        {
            if (!empty($data))
            {
                $ta = [];
                foreach ($data as $k=>$v)
                {
                    $t = self::clearRecursive($v);

                    if (!is_null($t)) {
                        $ta[$k] = $t;
                    }
                }

                if ($ta) {
                    return $ta;
                }
            }
        }
        elseif (is_string($data)) {
            if ($data === '0' || !empty($data)) {
                return str_replace(['\\n', '\\r'], ["\n", "\r"], $data);
            }
        }
        elseif (is_numeric($data)) {
            return $data;
        }
        elseif ($data === 0 || !empty($data)) {
            return $data;
        }

        return null;
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = EmailModel::objects()->getQuerySet();
        }
        return $this->qs;
    }

    public function setQuerySet($qs) {
        $this->qs = $qs;
    }

    public function setSorting($sorting, $qs)
    {
        if ($sorting) {

            switch ($sorting) {
                case 10:
                    {
                        $qs->order(['date']);
                        break;
                    }
                case 11:
                    {
                        $qs->order(['-date']);
                        break;
                    }
                case 1:
                default:
                    {
                        $user = Xcart::app()->user;
                        if ($user->show_events) {
                            /** @var QuerySet $qs */
                            $e_qs = OrderHelper::getCountEventsQS($user->id, ($user->show_events_min_date) ? (new DateTime($user->show_events_min_date)) : null);
                            $qs->join('left join', $e_qs->select(['order_id', 'count' => new Expression('count(*)')])->group(['order_id'])->allSql(), ['events.order_id' => 'orderid'], 'events');
                            $qs->order(['-shipping.important', '-events.count', '-date', '-orderid']);
                        } else {
                            $qs->order(['-shipping.important', '-date', '-orderid']);
                        }
                    }
            }
        }

        return $qs;
    }

    public function getQSWithSorting()
    {
        $qs = clone $this->qs;
        $joins = $qs->getQueryBuilder()->getJoins();
        $joins = array_keys($joins);

        if (!in_array('group', $joins)) {
            $qs->join('left join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
        }

        $qs->join('left join', 'xcart_shipping', ['shipping.shippingid' => 'group.shippingid'], 'shipping');


        if (!$this->sort && $this->model instanceof DashboardFilter) {
            $this->sort = $this->model->sorting;
        }

        $qs = $this->sort ? $this->setSorting($this->sort, $qs) : $this->setSorting(11, $qs);

        if ($this->order) {
            $qs->order($this->order);
        }

        return $qs;
    }

    public function getPager()
    {
        if (!$this->pager) {
            $this->pager = new Pagination($this->getQSWithSorting(),[
                    'pageSize' => $this->defaultPagerPageSize,
                    'view' => 'dashboard/parts/_pager.tpl',
                    'sorting_filter'       => (new DashboardFilter)->getField('sorting')->choices,
                    'sort' => $this->sort,
                ], new QuerySetDataSource());
        }

        return $this->pager;
    }

    public function getModels()
    {
        return $this->prepareModels($this->getPager()->paginate());
    }

    public function getPriorityShippingCount()
    {
        $qs = clone $this->qs;
        $qs->join('inner join', 'xcart_order_groups', ['orderid' => 'group.orderid'], 'group');
        $qs->join('inner join', 'xcart_shipping', ['shipping.shippingid' => 'group.shippingid'], 'shipping');
        $qs->filter(['shipping.important' => 1, new QAndNot(['group.shippingid' => ''])]);
        $qs->addSelect(['shipping.important']);

        return (int)Connection::getInstance()->fetchColumn("select COUNT(`order`.`important`) from ({$qs->allSql()}) as `order`");
    }

    public function getCachedPriorityShippingCount()
    {
        return null;
    }

    public function getCount()
    {
        return $this->qs->count();
    }

    private function getCacheCountKey($prefix = 'email_search_store_count_', array $params = [])
    {
        if ($this->model) {
            $id = $this->model::classNameShort() . $this->model->pk;
        }
        else {
            $md5 = json_encode($this->where);
            $id = md5($md5);
        }

        if ($params) {
            $id.= '_';
            $id.= md5(serialize($params));
        }

        return $prefix.$id;
    }

    public function getCacheKeyCount()
    {
        return $this->getCacheCountKey();
    }

    public function getCacheKeyPriority()
    {
        return $this->getCacheCountKey(self::CONST_CACHE_KEY_PRIORITY);
    }

    public function getCacheKeyEvent()
    {
        return $this->getCacheCountKey(self::CONST_CACHE_KEY_EVENT, ['user_id' => Xcart::app()->user->login]);
    }

    public function clearCache()
    {
        Xcart::app()->cache->set($this->getCacheKeyCount(), null);
        Xcart::app()->cache->set($this->getCacheKeyPriority(), null);
        Xcart::app()->cache->set($this->getCacheKeyEvent(), null);
    }

    public function getCashedCount()
    {
        $key = $this->getCacheCountKey();
        $count = Xcart::app()->cache->get($key);

        if (is_null($count))
        {
            $count = $this->getCount();
            Xcart::app()->cache->set($key, $count, $this->getCacheLifeTime());
        }

        return $count;
    }

    public function getCacheLifeTime($min = 20)
    {
        return $min + rand(1, $min * rand(1, round($min/2)));
    }

    public function getEventsCount(array $ids = [])
    {
        return 0;
    }

    public function getCachedEventsCount()
    {
        return 0;
    }

    public function prepareModels($models)
    {
        if (!$models) {
            return [];
        }

        $connection = Connection::getInstance();

        $order_ids = array_map(function ($model) { return $model->orderid; }, $models);
        $order_ids = array_filter($order_ids);

        if (empty($order_ids)) {
            return [];
        }

        $lom_sql     = QueryBuilder::getInstance($connection)->from('xcart_order_logs')->order(['-date'])->where(['orderid__in' => $order_ids, 'type__in' => ['S', 'EL']])->toSQL();
        $lo_messages = $connection->fetchAll($lom_sql);

        $loa_sql     = QueryBuilder::getInstance($connection)->select(['orderid', 'date' => new Max('date')])->from('xcart_order_logs')->group(['orderid'])->order(['-date'])->where(['orderid__in' => $order_ids])->toSQL();
        $lo_activity = $connection->fetchAll($loa_sql);

        OrderHelper::getMaxEtaTimeByOrder($order_ids);
        OrderHelper::getCountEvents($order_ids);

        foreach ($models as $model) {

            foreach ($lo_activity as $activity) {
                if ($activity['orderid'] == $model->orderid) {
                    $model->last_activity = $activity['date'];
                    break;
                }
            }

            foreach ($lo_messages as $message) {
                if ($model->orderid == $message['orderid']) {
                    $model->last_message = $message;
                    break;
                }
            }
        }

        return $models;
    }
}