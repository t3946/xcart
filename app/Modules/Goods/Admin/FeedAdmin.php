<?php


namespace Modules\Goods\Admin;


use DateTime;
use Modules\Admin\Contrib\Admin;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Forms\FeedForm;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

class FeedAdmin extends Admin
{
    public $listRowTemplate = '/feeds/_tr_feed.tpl';
    public string $allList = '/feeds/_list.tpl';

    public function getForm()
    {
        return new FeedForm();
    }

    public function getModel()
    {
        return new SupplierFeedModel;
    }

    public function getListColumns()
    {
        return [
            'feed_id',
            'distributor',
            'feed_name',
            'feed_type',
            'site',
            'feed_file_name',
            'process_time',
            'last_update_time',
            'last_update_items_count',
            'add_new_only',
            'enabled'
        ];
    }

    public function getSearchColumns()
    {
        return ['distributor__code'];
    }

    public function getAvailableListColumns()
    {
        return [
            'feed_id' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'ID',
                'order' => 'feed_id',
            ],
            'distributor' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'DX',
            ],
            'feed_name' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Name'
            ],
            'feed_type' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Type'
            ],
            'site' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Storefront'
            ],
            'feed_file_name' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'File name'
            ],
            'last_update_time' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Update time',
                'order' => 'last_update_time'
            ],
            'average_update_period' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Average update time'
            ],
            'last_update_items_count' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Items'
            ],
            'process_time' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Process time',
                'order' => 'process_time',
            ],
            'add_new_only' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'New'
            ],
            'enabled' => [
                'template' => $this->columnDefaultTemplate,
            ],
        ];
    }

    public function getItemProperty(Model $item, $property)
    {
        if ($property === 'distributor') {
            if ($distributor = $item->$property) {
                return "<a href='{$distributor->getAdminUrl()}' target='_blank'>{$distributor->code}</a>";
            }
        }
        if ($property === 'last_update_items_count' && $item->$property > 0) {
            /** @var ProductModel $product */
            if ($product = ProductModel::objects()
                ->filter([
                    'forsale' => 'Y',
                    'manufacturerid' => $item->manufacturerid,
                    'sites__storefrontid' => $item->storefront_id
                ])
                ->order(['?'])
                ->limit(1)
                ->get()) {
                return "<a href='{$product->getAbsoluteUrl(true)}' target='_blank'>{$item->$property}</a>";
            }
        }
        if ($property === 'average_update_period') {
            return $item->getAverageUpdatePeriod();
        }
        if ($property === 'last_update_time') {
            $date = new DateTime;
            $date->setTimestamp($item->$property);
            return $date->format('Y-M-d H:i');
        }
        return parent::getItemProperty($item, $property);
    }

    public function renderInternal($view, $params)
    {
        $params = array_replace($this->getCommonData(), $params);

        if (
            Xcart::app()->request->getIsAjax()
            || Xcart::app()->request->get->has('popup')
            || $this->innerRender
        ) {
            echo $this->render($view, $params);
        } else {
            echo $this->renderSmarty("admin/home.tpl", [
                'single_mode' => true,
                'width' => '100%',
                'main' => 'raw_html',
                'content' => $this->render($view, $params),
            ]);
        }
    }

    public function applyOrder($qs)
    {
        $order = $this->getOrder();

        if ($order && isset($order['raw'])) {
            $qs->order([
                $order['raw']
            ]);
        } else if ($this->sort) {
            $qs->order([
                $this->sort
            ]);
        } else {
            $qs->order([
                '-feed_id'
            ]);
        }
        return $qs;
    }

    public static function getName()
    {
        return 'Feeds';
    }

    public function getSuggestionColumns()
    {
        return [
            'category' => [
                'class' => CategoryModel::class,
                'columns' => [
                    'category', 'pk'
                ],
                'filter' => [
                    'avail' => 'Y'
                ]
            ],
        ];
    }

    public function isAjaxCreate(): bool
    {
        return true;
    }

    public function isAjaxUpdate(): bool
    {
        return true;
    }

}