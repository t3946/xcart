<?php

namespace Modules\Search\Helpers\Searchers;

use Elastic\EnterpriseSearch\AppSearch\Schema\SimpleObject;

class CategoryDocumentSearcher implements DocumentSearcherInterface
{
    public function getSearchObject(): SimpleObject
    {
        return new SimpleObject();
    }
}