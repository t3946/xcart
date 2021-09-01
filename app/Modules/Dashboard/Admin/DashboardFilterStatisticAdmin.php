<?php


namespace Modules\Dashboard\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Dashboard\Models\DashboardFilter;
use Modules\Dashboard\Models\DashboardFilterStatisticModel;
use Xcart\App\Orm\Model;

class DashboardFilterStatisticAdmin extends Admin
{
    public string $allList = 'dashboard/admin/_list.tpl';
    private array $filter_data = [];

    public function getListColumns()
    {
        $dates_data = DashboardFilterStatisticModel::objects()->order(['-date', '-hour'])->asArray()->all();

        foreach ($dates_data as $d) {
            $this->filter_data[$d['filter_id']][$d['date'] . ' ' . $d['hour'] . ':00'] = (int)$d['count'];
        }
        return [
            'name',
            ...$this->getUniqueDates()
        ];
    }

    public function getModel()
    {
        return new DashboardFilter();
    }

    public function getForm()
    {
    }

    public function getListItemActions()
    {
        return [];
    }

    /*public function all($pk = null)
    {
        $request = Xcart::app()->request;

        $this->setBreadcrumbs();
        $search = $_GET['search'] ?? null;
        $qs = $this->getQuerySet();

        if ($request->getIsGet() && $filter_form = $this->getFilterForm()) {
            $filter_form->populate($_GET, $_FILES);
            $qs = $this->handleFilter($qs, $filter_form);
        }

        $qs = $this->handleSearch($qs, $search);
        $qs = $this->applyOrder($qs);
        $qs = $this->fixSort($qs);

        $pagination = new Pagination($qs, [
            'pageSize' => $this->getConfig()->page_size ?: $this->pageSize,
            'pageSizes' => $this->pageSizes
        ], new QuerySetDataSource());

        if ($request->get->has($pagination->getPageSizeKey())) {
            $this->getConfig()->page_size = $request->get->get($pagination->getPageSizeKey());
            $this->getConfig()->save();
        }

        $this->renderInternal($this->allTemplate, [
            'objects' => $pagination->paginate(),
            'pagination' => $pagination,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
            'canSort' => $this->getCanSort($qs),
            'filter_form' => $filter_form ?? null,
        ]);
    }*/

    private function getUniqueDates(): array
    {
        $res = [];

        foreach ($this->filter_data as $data) {
            array_push($res, ...array_keys($data));
        }
        return array_unique($res);
    }

    public function getAvailableListColumns()
    {
        $dates = array_flip($this->getUniqueDates());

        array_walk($dates, static fn(&$a) => $a = [
            'class' => 'center'
        ]);

        return array_merge([
            'name' => [
                'class' => 'sticky-col first-col'
            ],
        ], $dates);
    }

    public function getItemProperty(Model $item, $property)
    {
        $value = $item;
        $data = explode('__', $property);
        foreach ($data as $name) {
            if ($value->hasField($name)) {
                $value = ($value->$name instanceof Model) ? (string)$value->$name : $value->$name;
            } else {
                $value = $this->filter_data[$item->id][$name];
            }
        }
        return $value;
    }

    public function getListGroupActions()
    {
        return [];
    }

    public static function getName()
    {
        return 'Dashboard filters statistic';
    }
}