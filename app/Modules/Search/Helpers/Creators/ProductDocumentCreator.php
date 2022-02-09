<?php

namespace Modules\Search\Helpers\Creators;

use DateTime;
use DateTimeInterface;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Search\Helpers\Processors\QueueDeleteProcessor;
use Modules\Search\Helpers\Processors\QueueIndexProcessor;
use Modules\Search\Helpers\Processors\QueueProcessorInterface;

class ProductDocumentCreator implements DocumentCreatorInterface
{

    public function createDocuments(array $update_models): array
    {
        $queue = [];

        $current_chunk = null;

        foreach ($update_models as $update_model) {
            $model = $update_model->product;

            if ($main_image = $model->getMainImage()) {
                $main_image_sizes = [
                    'detail' => $main_image->getCdnURL(ProductImageModel::IMAGE_SIZE_DETAIL),
                    'preview' => $main_image->getCdnURL(ProductImageModel::IMAGE_SIZE_PREVIEW),
                    'thumb' => $main_image->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB),
                ];
            }

            $document = (object)[
                'id' => $model->pk,
                'product' => $model->getFrontendName(),
                'productcode' => $model->productcode,
                'fulldescr' => $model->getFrontendDescription(),
                'price' => $model->getPrice(),
                'url' => $model->getAbsoluteUrl(true),
                'main_image' => $main_image_sizes ?? [],
                'is_group_root' => (int)$model->is_group_root,
                'brand' => $model->brand->brand ?? '',
                'upc' => $model->upc,
                'categories' => array_map(
                    static fn(CategoryModel $category) => $category->getFrontendName(),
                    $model->categories->all()
                ),
                'in_stock' => (int)!$model->isOutOfStockFrontend(),
                'forsale' => (int)($model->forsale === 'Y'),
                'created_at' => (new DateTime())->setTimestamp($model->add_date)->format(DateTimeInterface::RFC3339)
            ];

            $processor = null;

            switch ((int)$update_model->type) {
                case 6:
                    if ($model->forsale === 'Y') {
                        $processor = new QueueIndexProcessor();
                    } else {
                        $processor = new QueueDeleteProcessor();
                    }
                    break;
                case 61:
                    $processor = new QueueDeleteProcessor();
                    break;
            }

            if ($processor) {

                $class = get_class($processor);

                if ($current_chunk !== $class) {
                    $processor->addDocument($document);
                    $queue[] = $processor;
                } else {
                    $processor = $queue[count($queue) - 1];
                    $processor->addDocument($document);
                }
                $current_chunk = $class;
            }
        }

        return $queue;
    }
}