<?php

namespace Modules\Search\Helpers\Creators;

use Modules\Goods\Models\UpdatedProductModel;

class CategoryDocumentCreator implements DocumentCreatorInterface
{
    public array $to_index = [];
    public array $to_delete = [];

    /**
     * @param UpdatedProductModel[] $update_models
     */
    public function createDocuments(array $update_models): void
    {
        $this->to_delete = [];
        $this->to_index = [];

        foreach ($update_models as $update_model) {
            $model = $update_model->category;

            $document = (object)[
                'id' => $model->pk,
                'category' => $model->getFrontendName(),
                'root_category_id' => $model->root
            ];

            if ($model->avail === 'Y' && $model->active_product_count > 0) {
                $this->to_index[] = $document;
            } else {
                $this->to_delete[] = $document;
            }
        }
    }
}