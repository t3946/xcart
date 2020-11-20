<?php

namespace Modules\Dashboard\Stores;

use Mindy\QueryBuilder\Q\QAnd;
use Mindy\QueryBuilder\Q\QOr;
use Modules\PBX\Helpers\PBXHelper;
use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Orm\Manager;
use Xcart\App\Store\BaseStore;

class CallSearchStore extends BaseStore
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
        $qs = $this->getQuerySet();

        if (!empty($data['call'])) {
            $filter = PBXHelper::getCallDirectionFilter($data['call']['direction'] ?? []);
            $this->where = [new QOr(array_map(static fn($a) => new QAnd($a), $filter))];
        }
        $qs->filter($this->where)->having($this->having);
        $this->qs = $qs;
    }

    public static function getManager(): Manager
    {
        return PbxAnveoCallModel::objects();
    }

    protected function getCacheCountKey($prefix = 'call_search_store_count_', array $params = [])
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