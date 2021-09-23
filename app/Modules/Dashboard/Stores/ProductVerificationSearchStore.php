<?php

namespace Modules\Dashboard\Stores;

use Xcart\App\QueryBuilder\Q\QAnd;
use Xcart\App\QueryBuilder\Q\QOr;
use Modules\Goods\Admin\ProductVerificationAdmin;
use Modules\PBX\Helpers\PBXHelper;
use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Orm\Manager;
use Xcart\App\Store\BaseStore;

class ProductVerificationSearchStore extends BaseStore
{
    public const VIEW_TEMPLATE = '/admin/all.tpl';

    /**
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function populate(array $data)
    {
        $this->qs = (new ProductVerificationAdmin())->getQuerySet();
    }

    protected function getCacheCountKey($prefix = 'verification_search_store_count_', array $params = [])
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
}