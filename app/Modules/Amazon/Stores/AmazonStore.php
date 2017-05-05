<?php
namespace Modules\Amazon\Stores;

use Mindy\QueryBuilder\Expression;
use Modules\Amazon\Models\AmazonReorderBatchDataModel;
use Xcart\App\Orm\QuerySet;
use Xcart\App\Store\BaseStore;
use Xcart\Connection;

class AmazonStore extends BaseStore
{
    private $qs = null;

    public function __construct($data)
    {
        $this->populate($data);
    }

    /**
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function populate(array $data)
    {
        $filter = [];
        $qs = $this->getQuerySet();

        if (!empty($data)) {
            if ($data['cost_to_us']) {
                if (!empty($data['cost_to_us']['from'])) {
                    $filter['cost_to_us__gte'] = floatval($data['cost_to_us']['from']);
                }
                if ($data['cost_to_us']['to']) {
                    $filter['cost_to_us__lte'] = floatval($data['cost_to_us']['to']);
                }
            }
            if (!empty($data['r_avail'])) {
                if ($data['r_avail']['from']) {
                    $filter['r_avail__gte']  = intval($data['r_avail']['from']);
                }
                if ($data['r_avail']['to']) {
                    $filter['r_avail__lte'] = intval($data['r_avail']['to']);
                }
            }
            if (!empty($data['restocking_qty'])) {
                if ($data['restocking_qty']['from']) {
                    $filter['restocking_qty__gte'] = floatval($data['restocking_qty']['from']);
                }
                if ($data['restocking_qty']['to']) {
                    $filter['restocking_qty__lte'] = floatval($data['restocking_qty']['to']);
                }
            }
        }
        $qs->filter($filter);

        $this->qs = $qs;
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = AmazonReorderBatchDataModel::objects()->getQuerySet();
            $this->qs->join('inner join', 'xcart_manufacturers', ['manufacturerid' => 'm.manufacturerid'], 'm');
        }
        return $this->qs;
    }



    public function getAmazonBatchData()
    {
            /** @var QuerySet $qs */
            $qs = $this->getQuerySet();
            $qs->select(['m.manufacturer', 'm.code',
                'r_order' => new Expression("(restocking_qty * cost_to_us)"),
                '*'
            ])
                ->order([
                    'm.manufacturer',
                    '-r_order'
                ]);

//            echo $qs->getSql();

            return Connection::getInstance()->executeQuery(
                $qs->getSql()
            )->fetchAll(\PDO::FETCH_GROUP);
    }

}