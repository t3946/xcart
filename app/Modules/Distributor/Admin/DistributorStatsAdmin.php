<?php

namespace Modules\Distributor\Admin;

use DateInterval;
use DateTime;
use Modules\Admin\Contrib\Admin;
use Modules\Distributor\Forms\DistributorStatsFilterForm;
use Modules\Distributor\Models\DistributorModel;
use Modules\Order\Models\OrderStatusModel;
use Xcart\App\Form\Form;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\QueryBuilder\Q\QOr;

class DistributorStatsAdmin extends Admin
{

    public function getListColumns(): array
    {
        return [
            'manufacturer',
            'created_at',
            'active_products',
            'ads_products',
            'orders',
            'sales',
        ];
    }

    /**
     * @param DistributorModel $item
     * @param $property
     * @return string
     */
    public function getItemProperty(Model $item, $property): string
    {
        $base_filter = ['cb_status__in' => [OrderStatusModel::ORDER_STATUS_AUTHORIZED, OrderStatusModel::ORDER_STATUS_COMPLETED]];
        $request = Xcart::app()->request;
        if ($request->getIsGet() && $filter_form = $this->getFilterForm()) {
            $filter_form->populate($_GET, $_FILES);
            if ($period_field = $filter_form->getField('stats_period')) {
                $period_value = $period_field->getValue();
                if (!empty($period_value)) {
                    $date = new DateTime();
                    $date->sub(new DateInterval("P{$period_value}D"));
                    $base_filter = array_merge($base_filter, ['order__date__gte' => $date->getTimestamp()]);
                }

            }
        }
        switch ($property) {
            case 'orders':
                return $item->order_groups->filter($base_filter)->group(['orderid'])->count();
            case 'sales':
                return $item->order_groups->filter($base_filter)->sum('total_gross');
        }
        return parent::getItemProperty($item, $property);
    }

    public function getForm(): ?ModelForm
    {
        return null;
    }

    public function getListItemActions(): array
    {
        return [];
    }

    public function getModel(): DistributorModel
    {
        return new DistributorModel();
    }

    public static function getName(): string
    {
        return 'Distributors stats';
    }

    public function getFilterForm(): ?Form
    {
        return new DistributorStatsFilterForm();
    }

    public function handleFilter($qs, $form)
    {
        if (($dx_field = $form->getField('manufacturer_code')) && $dx_value = trim($dx_field->getValue())) {
            $qs->filter(['manufacturer' => new QOr(['manufacturer__contains' => $dx_value, 'code' => $dx_value])]);
        }
        return parent::handleFilter($qs, $form);
    }
}