<?php

namespace Modules\Dashboard\Stores;

use Modules\Forms\Models\EmailModel;

use Xcart\App\Orm\Manager;
use Xcart\App\Store\BaseStore;

class EmailSearchStore extends BaseStore
{
    public const VIEW_TEMPLATE = '/admin/email_all.tpl';

    /**
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function populate(array $data)
    {
        $qs = $this->getQuerySet();

        if (!empty($data['email'])) {
            $filter = [];
            $field = $data['email']['field'];
            if ($data['email']['condition'] === 'contains') {
                $field .= '__contains';
            }
            $filter[$field] = $data['email']['value'];
            $this->where = $filter;
        }
        $qs->filter($this->where)->having($this->having);
        $this->qs = $qs;
    }

    public static function getManager(): Manager
    {
        return EmailModel::objects();
    }

    protected function getCacheCountKey($prefix = 'email_search_store_count_', array $params = [])
    {
        if ($this->model) {
            $id = $this->model::classNameShort() . $this->model->pk;
        } else {
            $md5 = json_encode($this->where);
            $id = md5($md5);
        }

        if ($params) {
            $id .= '_';
            $id .= md5(serialize($params));
        }

        return $prefix . $id;
    }

    public function getQSWithSorting()
    {
        $qs = clone $this->qs;

        $qs = $this->sort ? $this->setSorting($this->sort, $qs) : $qs->order(['-date']);

        if ($this->order) {
            $qs->order($this->order);
        }

        return $qs;
    }
}