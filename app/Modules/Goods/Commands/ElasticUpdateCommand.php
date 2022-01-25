<?php

namespace Modules\Goods\Commands;

use Dariuszp\CliProgressBar;
use DateTime;
use DateTimeInterface;
use Elastic\AppSearch\Client\ClientBuilder;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;

class ElasticUpdateCommand extends Command
{

    public function handle($arguments = [])
    {
        $apiEndpoint   = 'http://68.168.211.58:3002/';
        $apiKey        = 'private-xfpuz5d7ruisj6rh8s1cmawz';

        $client = ClientBuilder::create($apiEndpoint, $apiKey)->build();

        $i = 0;

        $bar = new CliProgressBar(ProductModel::objects()->count());

        while ($models = ProductModel::objects()->paginate(++$i, 100)->all()) {

            $documents = [];

            foreach ($models as $model) {
                /** @var ProductModel $model */

                $documents[] = [
                    'id' => $model->pk,
                    'product'     => $model->getFrontendName(),
                    'productcode' => $model->productcode,
                    'fulldescr' => $model->getFrontendDescription(),
                    'price' => $model->getPrice(),
                    'url' => $model->getAbsoluteUrl(true),
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