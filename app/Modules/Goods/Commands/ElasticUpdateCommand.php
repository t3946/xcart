<?php

namespace Modules\Goods\Commands;

use Dariuszp\CliProgressBar;
use DateTime;
use DateTimeInterface;
use Elastic\AppSearch\Client\ClientBuilder;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class ElasticUpdateCommand extends Command
{

    public function handle($arguments = [])
    {
        $client = Xcart::app()->elastic->getClient();

        $i = 0;

        $bar = new CliProgressBar(ProductModel::objects()->count());

        while ($models = ProductModel::objects()->paginate(++$i, 100)->all()) {

            $documents = [];

            foreach ($models as $model) {
                /** @var ProductModel $model */

                if ($main_image = $model->getMainImage()) {
                    $main_image_sizes = [
                        'detail' => $main_image->getCdnURL(ProductImageModel::IMAGE_SIZE_DETAIL),
                        'preview' => $main_image->getCdnURL(ProductImageModel::IMAGE_SIZE_PREVIEW),
                        'thumb' => $main_image->getCdnURL(ProductImageModel::IMAGE_SIZE_THUMB),
                    ];
                }

                $documents[] = [
                    'id' => $model->pk,
                    'product'     => $model->getFrontendName(),
                    'productcode' => $model->productcode,
                    'fulldescr' => $model->getFrontendDescription(),
                    'price' => $model->getPrice(),
                    'url' => $model->getAbsoluteUrl(true),
                    'main_image' => $main_image_sizes ?? [],
                    'is_group_root' => (int)$model->is_group_root,
                    'brand' => $model->brand->brand ?? '',
                    'upc' => $model->upc,
                    'sites' => array_map(static fn(SiteModel $site) => $site->code, $model->sites->all()),
                    'categories' => array_map(static fn(CategoryModel $category) => $category->getFrontendName(), $model->categories->all()),
                    'in_stock' => (int)!$model->isOutOfStockFrontend(),
                    'forsale' => (int)($model->forsale === 'Y'),
                    'created_at' => (new DateTime())->setTimestamp($model->add_date)->format(DateTimeInterface::RFC3339)
                ];
            }
            $client->indexDocuments('s3stores-products', $documents);

            $bar->progress(100);

        }

        $bar->end();
    }
}