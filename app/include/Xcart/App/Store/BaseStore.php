<?php

namespace Xcart\App\Store;

use DateTime;
use Mindy\QueryBuilder\Expression;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Pagination\Pagination;
use Modules\Order\Helpers\OrderHelper;
use Xcart\App\Helpers\SmartProperties;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;

abstract class BaseStore
{
    use SmartProperties;
    const CONST_MANUAL_STRING      = '=> ';
    const CONST_MANUAL_VIEW_STTINR = '-> ';



    protected $form_data = [];
    protected $where = [];
    protected $having = [];
    /** @var QuerySet */
    protected $qs;
    /** @var Pagination */
    protected $pager;
    protected $order;
    protected $sort;
    protected $model = null;

    public $defaultPagerPageSize = 25;

    /**
     * @param array $data
     *
     * @return QuerySet
     */
    abstract public function populate(array $data);

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

    public function setQuerySet($qs) {
        $this->qs = $qs;
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

    public function getCount()
    {
        return $this->qs->count();
    }

    public function getCacheKeyCount()
    {
        return $this->getCacheCountKey();
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
        return $min + random_int(1, $min * random_int(1, round($min / 2)));
    }

    public function setSorting($sorting, $qs)
    {
        return $qs;
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = static::getManager()->getQuerySet();
        }
        return $this->qs;
    }

    public function clearCache()
    {
        Xcart::app()->cache->set($this->getCacheKeyCount(), null);
    }

    public function prepareModels($models)
    {
        if (!$models) {
            return [];
        }
        return $models;
    }

    public function getQSWithSorting()
    {
        $qs = clone $this->qs;

        if (!$this->sort && $this->model instanceof DashboardFilter) {
            $this->sort = $this->model->sorting;
        }

        $qs = $this->sort ? $this->setSorting($this->sort, $qs) : $this->setSorting(11, $qs);

        if ($this->order) {
            $qs->order($this->order);
        }

        return $qs;
    }

}