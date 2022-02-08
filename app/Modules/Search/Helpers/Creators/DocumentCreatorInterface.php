<?php

namespace Modules\Search\Helpers\Creators;

use Modules\Goods\Models\UpdatedProductModel;
use Modules\Search\Helpers\Processors\QueueProcessorInterface;

interface DocumentCreatorInterface
{
    /**
     * @param UpdatedProductModel[] $update_models
     * @return QueueProcessorInterface[]
     */
    public function createDocuments(array $update_models): array;
}