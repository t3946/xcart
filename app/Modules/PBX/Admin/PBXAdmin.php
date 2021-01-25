<?php


namespace Modules\PBX\Admin;


use DateTime;
use Exception;
use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Admin\Contrib\Admin;
use Modules\Dashboard\Helpers\SearchHelper;
use Modules\Order\Models\OrderModel;
use Modules\PBX\Forms\TranslatesFilterForm;
use Modules\PBX\Helpers\PBXHelper;
use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Form\Form;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Fields\Field;
use Xcart\App\Orm\Fields\FileField;
use Xcart\App\Orm\Fields\ModelFieldInterface;
use Xcart\App\Orm\Model;
use Xcart\App\Orm\QuerySet;

class PBXAdmin extends Admin
{
    public function getListColumns()
    {
        return [
            'orders',
            'e164',
            'cname',
            'direction',
            'user',
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
     * @throws Exception
     */
    public function getItemProperty(Model $item, $property)
    {
        $route = Xcart::app()->router;
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
                return ($url = $item->getUrl())
                    ? "<audio 
                            style='width: 212px;' 
                            controls 
                            preload='none' 
                            data-call-id='{$item->id}'
                            src='{$url}' 
                            onplay=\"
                                [...document.getElementsByTagName('audio')]
                                    .filter((a) => a.dataset.callId !== this.dataset.callId)
                                    .forEach((audio) => audio.pause())
                                fetch('{$route->url('admin_pbx:listen')}', {
                                    method: 'POST',
                                    body: JSON.stringify({'call_id': this.dataset.callId})
                            })\"/>"
                    : 'Not defined';
            case 'user':
                if ($item->isLost() || $item->isVoiceMail()) {
                    return '';
                }
                break;
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

    public function getFilterForm(): ?Form
    {
        return new CallsFilterForm();
    }

    public function handleFilter(QuerySet $qs, $form): QuerySet
    {
        if (($order_field = $form->getField('orders'))
            && ($order = $order_field->getValue())
            && preg_match('/^([a-zA-Z]{2,4}-).++/i', $order)) {
            $order_field->setValue(substr($order, 3));
        }

        if (($phone_field = $form->getField('e164')) && $phone = $phone_field->getValue()) {
            $phone_field->setValue(preg_replace('/\D/', '', $phone));
        }

        if (($date_field = $form->getField('date_from')) && $date_range = $date_field->getValue()) {
            $range = SearchHelper::getDateRange($date_range, 'start_at');
            $qs->filter(array_map(static fn($q) => (new DateTime())->setTimestamp($q), $range));
        }

        $directions = $form->getField('direction')->getValue();

        if ($or = PBXHelper::getCallDirectionFilter($directions)) {
            $qs->filter([new QOr(array_map(static fn($a) => new QAnd($a), $or))]);
        }

        $qs = parent::handleFilter($qs, $form);

        return $qs;
    }

}