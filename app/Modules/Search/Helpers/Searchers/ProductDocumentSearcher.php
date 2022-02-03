<?php

namespace Modules\Search\Helpers\Searchers;

use Elastic\EnterpriseSearch\AppSearch\Schema\SimpleObject;

class ProductDocumentSearcher implements DocumentSearcherInterface
{
    public function getSearchObject(): SimpleObject
    {
        $searchObject = new SimpleObject();

        $searchObject->all = [(object)['is_group_root' => 0], (object)['in_stock' => 1]];

        return $searchObject;
    }
}