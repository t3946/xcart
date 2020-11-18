<?php


namespace Modules\PBX\Admin;


use Modules\Admin\Contrib\Admin;
use Modules\Order\Models\OrderModel;
use Modules\PBX\Forms\CallsFilterForm;
use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Form\Form;
use Xcart\App\Orm\Fields\Field;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\ModelFieldInterface;
use Xcart\App\Orm\Model;

class PBXAdmin extends Admin
{
    public function getListColumns()
    {
        return [
            'orders',
            'e164',
            'cname',
            'direction',
            'account',
            'start_at',
            'duration',
            'audio',
        ];
    }

    public function getForm()
    {
    }

    public function getModel()
    {
        return new PbxAnveoCallModel;
    }

    public static function getName()
    {
        return 'Call recordings';
    }


    /**
     * @param Model|PbxAnveoCallModel $item
     * @param $property
     * @return mixed|string|Field|FileField|ModelFieldInterface|Model
     */
    public function getItemProperty(Model $item, $property)
    {
        switch ($property) {
            case 'orders':
                return implode('<br/>', array_unique(array_map(
                    static fn(OrderModel $order) => "<a href='{$order->getAdminUrl()}' target='_blank'>{$order->getOrderNumber()}</a>",
                    $item->$property->all())));
            case 'e164':
                return $item->getFrontendE164();
            case 'direction':
                return $item->getDirection();
            case 'duration':
                return ($d = $item->getDuration()) ? $d->format('%H:%I:%S') : '';
            case 'audio':
                return ($url = $item->getUrl()) ? "<a href='{$url}' target='_blank'>Listen</a>" : 'Not defined';
        }

        return parent::getItemProperty($item, $property);
    }

    public function getAvailableListColumns()
    {
        return array_merge(parent::getAvailableListColumns(), [
            'direction' => [
                'title' => 'Direction',
                'template' => 'admin/list/columns/default.tpl',
            ],
            'duration' => [
                'title' => 'Duration',
                'template' => 'admin/list/columns/default.tpl',
            ],
            'audio' => [
                'title' => 'Audio',
                'template' => 'admin/list/columns/default.tpl',
            ],
        ]);
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
                '-id'
            ]);
        }
        return $qs;
    }

    public function getListItemActions()
    {
        return [];
    }

    public function getListGroupActions()
    {
        return [];
    }

    public function getFilterForm()
    {
        return new CallsFilterForm;
    }

}