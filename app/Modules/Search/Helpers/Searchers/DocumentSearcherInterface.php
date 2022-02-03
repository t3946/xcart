<?php

namespace Modules\Search\Helpers\Searchers;

use Elastic\EnterpriseSearch\AppSearch\Schema\SimpleObject;

interface DocumentSearcherInterface
{
    public function getSearchObject(): SimpleObject;
}