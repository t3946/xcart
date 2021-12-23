<?php

namespace Modules\Distributor\Admin;

use DateInterval;
use DateTime;
use Modules\Admin\Contrib\Admin;
use Modules\Distributor\Forms\DistributorStatsFilterForm;
use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Form\Form;
use Xcart\App\Form\ModelForm;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;

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
        $request = Xcart::app()->request;
        if ($request->getIsGet() && $filter_form = $this->getFilterForm()) {
            $filter_form->populate($_GET, $_FILES);
            if ($period_field = $filter_form->getField('stats_period')) {
                $period_value = $period_field->getValue();
                if (!empty($period_value)) {
                    $date = new DateTime();
                    $date->sub(new DateInterval("P{$period_value}D"));
                }

            }
        }
        switch ($property) {
            case 'orders':
                if ($period_value) {
                    return $item->order_groups->filter(['order__date__gte' => $date->getTimestamp()])->group(['orderid'])->count();
                }
                return $item->order_groups->group(["orderid"])->count();
            case 'sales':
                if ($period_value) {
                    return $item->order_groups->filter(['order__date__gte' => $date->getTimestamp()])->sum('total_gross');
                }
                return $item->order_groups->sum('total_gross');
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
}