<?php
namespace Modules\Goods\Helpers;

use Modules\Goods\GoodsModule;
use Modules\Goods\Models\CategoryModel;

class ProductSortHelper
{
    public static $default = 'relevance';

    /** @var \Xcart\App\Orm\Manager|\Xcart\App\Orm\QuerySet  */
    private $qs;
    private $category;

    /**
     * ProductSortHelper constructor.
     *
     * @param \Xcart\App\Orm\Manager|\Xcart\App\Orm\QuerySet $qs ProductModel QuerySet
     */
    public function __construct($qs) {
        $this->qs = $qs;
        return $this;
    }

    public function setCategory($category = null)
    {
        $this->category = $category;
        return $this;
    }

    public function getSortedQS($orderBy = 'relevance')
    {
        switch ($orderBy) {
            case 'price': {
                $pqs = $this->getOrderByPrice('');
                break;
            }
            case '-price': {
                $pqs = $this->getOrderByPrice('-');
                break;
            }
            case 'new': {
                $pqs = $this->getOrderByNew();
                break;
            }
            case 'brand': {
                $pqs = $this->getOrderByBrand();
                break;
            }
            case 'relevance':
            default: {
                $orderBy = static::$default;
                $pqs = $this->getOrderByRelevance();
            }
        }
        
        return $pqs;
    }

    /**
     * @param CategoryModel $category
     * @param int           $max_product
     *
     * @return \Xcart\App\Orm\Manager|\Xcart\App\Orm\QuerySet
     */
    public function getOrderByRelevance($max_product = 50)
    {
        $qs = clone $this->qs;

        [$oldOrder] = $qs->getQueryBuilder()->getOrder();
        
        if ($this->category) {
            $oldOrder[] = 'categories__order_by';
            $oldOrder[] = 'categories__through__orderby';
            $qs->with(['categories_link']);
        }

        $qs->order($oldOrder);

        return $qs;
    }

    public function getOrderByPrice($direction = '-')
    {
        $qs = clone $this->qs;

        list($oldOrder, $orderOptions) = $qs->getQueryBuilder()->getOrder();

        $qs->with(['quick_prices']);
        $qs->filter(['quick_prices__price__isnull' => false]);
        array_unshift($oldOrder, $direction.'quick_prices__price');

        return $qs->order($oldOrder);
    }

    public function getOrderByNew()
    {
        $qs = clone $this->qs;
        list($oldOrder, $orderOptions) = $qs->getQueryBuilder()->getOrder();
        array_unshift($oldOrder, '-add_date');
        return $qs->order($oldOrder);
    }

    public function getOrderByBrand()
    {
        $qs = clone $this->qs;
        [$oldOrder, $orderOptions] = $qs->getQueryBuilder()->getOrder();
        array_unshift($oldOrder, '-manufacturerid');
        return $qs->order($oldOrder);
    }

    public static function getOrderBy() {
        return [
            'relevance' => GoodsModule::t('Relevance'),
            'price' => GoodsModule::t('Price low to high'),
            '-price' => GoodsModule::t('Price high to low'),
            'new' => GoodsModule::t('New'),
            'brand' => GoodsModule::t('Brand name'),
        ];

    }
}