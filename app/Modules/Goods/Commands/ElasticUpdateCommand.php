<?php

namespace Modules\Goods\Commands;

use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Elasticsearch\ClientBuilder;

class ElasticUpdateCommand extends Command
{

    public function handle($arguments = [])
    {
        $hosts = [
            'es01'
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();

        $params = [];
        /** @var ProductModel $model */
        foreach (ProductModel::forsale()->limit(100)->all() as $model) {
            $params['body'][] = [
                'index' => [
                    '_index' => 'product',
                    '_id'    => $model->pk
                ]
            ];
            $params['body'][] = [
                'product'     => $model->getFrontendName(),
                'productcode' => $model->productcode,
                'fulldescr' => $model->getFrontendDescription(),
                'price' => $model->getPrice(),
                'url' => $model->getAbsoluteUrl(true),
                'brand' => $model->brand->brand ?? '',
                'site' => array_map(static fn(SiteModel $site) => $site->code, $model->sites->all()),
                'categories' => array_map(static fn(CategoryModel $category) => $category->getFrontendName(), $model->categories->all())
            ];
        }
        $responses = $client->bulk($params);

    }
}