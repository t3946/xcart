<?php

namespace Modules\Forms\Admin;

use Mindy\QueryBuilder\Expression;
use Modules\Admin\Contrib\Admin;
use Modules\Forms\Forms\EmailForm;
use Modules\Forms\Models\EmailModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Orm\Model;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;


class EmailAdmin extends Admin
{
    public $infoTemplate = '/admin/email_info.tpl';
    public $allTemplate = '/admin/email_all.tpl';
    public $listRowTemplate =  'admin/distributor/form/list/_tr_email.tpl';

    public function getListColumns()
    {
        return ['date', 'from_address', 'subject'];
    }

    public function getSearchColumns()
    {
        return ['subject', 'snippet', 'from_address'];
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
                '-date'
            ]);
        }
        return $qs;
    }

    public function getAvailableListColumns()
    {
        return [
            'id' => [
                'title' => 'ID',
                'template' => $this->columnDefaultTemplate,
            ],
            'from_address' => [
                'title' => 'From',
                'template' => $this->columnDefaultTemplate,
            ],
            'subject' => [
                'title' => 'Subject',
                'template' => $this->columnDefaultTemplate,
            ],
            'body' => [
                'title' => 'Body',
                'template' => $this->columnDefaultTemplate,
            ],
            'snippet' => [
                'title' => 'Sippet',
                'template' => $this->columnDefaultTemplate,
            ],
            'type' => [
                'title' => 'Type',
                'template' => $this->columnDefaultTemplate,
            ],
            'date' => [
                'title' => 'Date',
                'template' => $this->columnDefaultTemplate,
                'order' => 'date'
            ],
        ];
    }

    public static function getItemName()
    {
        return 'Email';
    }

    public function getForm()
    {
        return new EmailForm();
    }

    public function getModel()
    {
        return new EmailModel();
    }

    public static function getName()
    {
        return 'Inbox/Sorting dashboard';
    }

    public function getItemProperty(Model $item, $property)
    {
        /** @var EmailModel $item */
        if ($property === 'from_address') {
            return $item->getFrom();
        }
        if ($property === 'to_address') {
            return $item->getTo();
        }
        if ($property === 'date') {
            return $item->getDate();
        }
        if ($property === 'subject') {
            return $item->getSubject();
        }
        return parent::getItemProperty($item, $property);
    }

    public function getListItemActions()
    {
        return [];
    }

    public function getListGroupActions()
    {
        return [];
    }

    public function info($pk)
    {
        /** @var EmailModel $object */
        $object = $this->getModelOr404($pk);
        $object->setViewed();

        $this->setBreadcrumbs($object->subject);
        $this->renderInternal($this->infoTemplate, [
            'object' => $object,
            'fields' => $this->getForm()->getFields(),
        ]);
    }

    protected function getAllQuerySet($search)
    {
        $qs = $this->getQuerySet();
        $qs = $this->handleSearch($qs, $search);
        $qs = $this->applyOrder($qs);
        $qs = $this->fixSort($qs);
        $qs->group(['thread_id']);
        $qs2 = $this->getQuerySet();
        $qs2->select(['dt' => new Expression('MAX(date)'), 'thread_id']);
        $qs2->group(['thread_id']);
        $qs->join('inner join', $qs2->getQueryBuilder(), [$qs->getTableAlias() .'.date' => 'mx.dt', $qs->getTableAlias() .'.thread_id' => 'mx.thread_id'], 'mx');
        return $qs;
    }

    public function all($pk = null)
    {
        $this->setBreadcrumbs();
        $search = $_GET['search'] ?? null;

        $qs = $this->getAllQuerySet($search);
        $qs->filter(['dx_models__manufacturerid__isnull' => true, 'order_models__orderid__isnull' => true]);

        $pagination = new Pagination($qs, [
            'pageSize' => $this->getConfig()->page_size ?: $this->pageSize,
            'pageSizes' => $this->pageSizes
        ], new QuerySetDataSource());

        if (Xcart::app()->request->get->has($pagination->getPageSizeKey())) {
            $this->getConfig()->page_size = Xcart::app()->request->get->get($pagination->getPageSizeKey());
            $this->getConfig()->save();
        }

        $this->renderInternal($this->allTemplate, [
            'objects' => $pagination->paginate(),
            'pagination' => $pagination,
            'order' => $this->getOrder(),
            'search' => $this->getSearchColumns(),
            'columns' => $this->buildListColumns(),
            'canSort' => $this->getCanSort($qs),
        ]);
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
        }
        else {
            echo $this->renderSmarty("admin/home.tpl", [
                'single_mode' => true,
                'width'       => '100%',
                'main'        => 'raw_html',
                'content'     =>  $this->render($view, $params),
            ]);
        }
    }

}

