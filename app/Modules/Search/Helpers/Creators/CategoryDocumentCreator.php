<?php

namespace Modules\Search\Helpers\Creators;

use Modules\Search\Helpers\Processors\QueueDeleteProcessor;
use Modules\Search\Helpers\Processors\QueueIndexProcessor;

class CategoryDocumentCreator implements DocumentCreatorInterface
{

    public function createDocuments(array $update_models): array
    {
        $queue = [];

        foreach ($update_models as $update_model) {
            $model = $update_model->category;

            $document = (object)[
                'id' => $model->pk,
                'url' => $model->getAbsoluteUrl(true),
                'category' => $model->getFrontendName(),
                'root_category_id' => $model->root
            ];

            if ($model->avail === 'Y' && $model->active_product_count > 0) {
                $queue[] = new QueueIndexProcessor($document);
            } else {
                $queue[] = new QueueDeleteProcessor($document);
            }
        }
        return $queue;
    }
}