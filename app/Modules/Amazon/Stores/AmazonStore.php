<?php
namespace Modules\Amazon\Stores;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
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
                if ($data['cost_to_us']['from'] != '') {
                    $filter['cost_to_us__gte'] = floatval($data['cost_to_us']['from']);
                }
                if ($data['cost_to_us']['to'] != '') {
                    $filter['cost_to_us__lte'] = floatval($data['cost_to_us']['to']);
                }
            }
            if (!empty($data['r_avail'])) {
                if ($data['r_avail']['from'] != '') {
                    $filter['r_avail__gte']  = intval($data['r_avail']['from']);
                }
                if ($data['r_avail']['to'] != '') {
                    $filter['r_avail__lte'] = intval($data['r_avail']['to']);
                }
            }
            if (!empty($data['restocking_qty'])) {
                if ($data['restocking_qty']['from'] != '') {
                    $filter['restocking_qty__gte'] = floatval($data['restocking_qty']['from']);
                }
                if ($data['restocking_qty']['to'] != '') {
                    $filter['restocking_qty__lte'] = floatval($data['restocking_qty']['to']);
                }
            }
            if (!empty($data['restocking_competitive_price'])) {
                $filter[] = new QOr(['min_fba_price__lt' => new Expression(' avg_comp_price'), 'avg_comp_price' => -1]);
            }
        }
        if (!empty($data['batch_id'])) {
            $filter['batch_id'] = $data['batch_id'];
        }
        $qs->filter($filter);

        $this->qs = $qs;
    }

    public function getQuerySet()
    {
        if (!$this->qs) {
            $this->qs = AmazonReorderBatchDataModel::objects()->getQuerySet();
            $this->qs->join('inner join', 'xcart_manufacturers', ['manufacturerid' => 'm.manufacturerid'], 'm');
            $this->qs->join('left join', 'xcart_manufacturers', ['m2.manufacturerid' => 'm.parent_manufacturer_id'], 'm2');
            $this->qs->join('inner join', 'xcart_products_sf', ['productid' => 'sf.productid'], 'sf');
        }
        return $this->qs;
    }



    public function getAmazonBatchData()
    {
            /** @var QuerySet $qs */
            $qs = $this->getQuerySet();
            $qs->select(['manufacturer' => new Expression("IFNULL(m2.manufacturer, `m`.`manufacturer`)"),
                'code' => new Expression("IFNULL(m2.code, `m`.`code`)"),
                'm.m_address',
                'm.m_city',
                'm.m_country',
                'm.m_state',
                'm.m_zipcode',
                'sf.sfid',
                'r_order' => new Expression("(restocking_qty * cost_to_us)"),
                'r_qty_order' => new Expression("IF (restocking_qty >= 2, 0, 1)"),
                '*'
            ])
                ->order([
                    'manufacturer',
                    'r_qty_order',
                    '-r_order'
                ]);

            //echo $qs->getSql();

            return Connection::getInstance()->executeQuery(
                $qs->getSql()
            )->fetchAll(\PDO::FETCH_GROUP);
    }

}