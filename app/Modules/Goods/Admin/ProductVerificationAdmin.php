<?php


namespace Modules\Goods\Admin;


use DateTime;
use Modules\Admin\Contrib\Admin;
use Modules\Goods\Forms\ProductVerificationForm;
use Modules\Goods\Models\ProductModel;
use Modules\Order\Models\OrderModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;

class ProductVerificationAdmin extends Admin
{
    public $allTemplate = 'verification/all.tpl';

    public const ORDER_CB_STATUSES = [
        OrderStatusModel::ORDER_STATUS_COMPLETED,
        OrderStatusModel::ORDER_STATUS_AUTHORIZED,
        OrderStatusModel::ORDER_STATUS_UNPAID_PO,
        OrderStatusModel::ORDER_STATUS_QUEUED
    ];

    public function getForm()
    {
        return new ProductVerificationForm();
    }

    public function getModel()
    {
        return new ProductModel();
    }

    public function getListColumns()
    {
        return [
            'distributor',
            'orders',
            'productcode',
            'product',
            'link',
            'last_verify_date',
            'verification_status'
        ];
    }

    public function getQuerySet(): QuerySet
    {
        $filter = [
            'order_details__order_group__cb_status__in' => self::ORDER_CB_STATUSES,
            'order_details__order_group__order__vn_status__isnt' => OrderStatusModel::ORDER_VN_STATUS_VERIFIED,
            'order_details__order_group__order__order_type' => OrderModel::ORDER_TYPE_XCART,
            'verification_statusid__isnt' => ProductModel::PRODUCT_STATUS_VERIFY,
        ];
        return parent::getQuerySet()->filter($filter)->group(['productid']);
    }

    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'distributor':
                $dx = $item->distributor;
                return "<a target='_blank' href='{$dx->getAdminUrl(8)}'><b>{$dx->code}</b></a>";
            case 'orders':
                $orders = OrderModel::objects()->filter(
                    [
                        'groups__cb_status__in' => self::ORDER_CB_STATUSES,
                        'groups__detail_models__productid' => $item->productid,
                        'vn_status__isnt' => OrderStatusModel::ORDER_VN_STATUS_VERIFIED
                    ]
                );
                $links = array_map(
                    static fn($order
                    ) => "<a href='{$order->getAdminUrl()}' target='_blank'>{$order->getOrderNumber()}</a>",
                    $orders->all()
                );
                return implode('<br/>', $links);
            case 'productcode':
                return "<a target='_blank' href='{$item->getAdminUrl()}'>{$item->$property}</a>";
            case 'product':
                return "<a target='_blank' href='{$item->getAbsoluteUrl(true)}'>{$item->getFrontendName()}</a>";
            case 'link':
                return $item->getDistributorUrl()
                    ? "<a target='_blank' href='{$item->getDistributorUrl()}'>{$item->getMpn()}</a>"
                    : $item->getMpn();
            case 'last_verify_date':
                return $item->last_verify_date ? (new DateTime())->setTimestamp($item->last_verify_date)->format('Y-m-d') : '';

        }
        return parent::getItemProperty($item, $property);
    }

    public function getAvailableListColumns()
    {
        return [
            'product' => [
                'title' => 'Front End'
            ],
            'orders' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Orders',
            ],
            'link' => [
                'template' => $this->columnDefaultTemplate,
                'title' => 'Distr Website',
            ],
            'verification_status' => [
                'template' => 'list/columns/default.tpl',
            ]
        ];
    }

    public function getListItemActions()
    {
        return [];
    }

    public function getItemEditProperty(Model $item, $property)
    {
        if ($form = $this->getForm()) {
            $form->setInstance($item);
            if ($field = $form->getField($property)) {
                return $field->renderInput();
            }
        }
        return '';
    }

    public static function getName()
    {
        return 'Product Verification';
    }

    public function getListGroupActions()
    {
        return [];
    }
}