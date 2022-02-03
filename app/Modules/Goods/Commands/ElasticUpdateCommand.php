<?php

namespace Modules\Goods\Commands;

use Dariuszp\CliProgressBar;
use DateTime;
use DateTimeInterface;
use Modules\Goods\Models\CategoryModel;
use Modules\Goods\Models\ProductImageModel;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Search\SearchModule;
use Modules\Sites\Models\SiteModel;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class ElasticUpdateCommand extends Command
{

    public function handle($arguments = [])
    {

        foreach (SiteModel::getAllEnabled() as $site) {
            echo "update $site->code products\n";

            $updated_products = UpdatedProductModel::objects()->filter([
                'type__in' => [6, 61],
                'product__sites__storefrontid' => $site->pk
            ]);

            $bar = new CliProgressBar($updated_products->count());

            $i = 0;

            Xcart::app()->getModule('Sites')->setSite($site);

            $engine_name = SearchModule::getEngine($site->code);

            Xcart::app()->elastic->checkEngine(
                $engine_name,
                strtolower($site->lang->lang_code ?? 'en')
            );

            while ($models = $updated_products->paginate(++$i, 100)->all()) {
                $to_index = $to_delete = [];

                /** @var UpdatedProductModel $update_model */

                foreach ($models as $update_model) {
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
                    switch ((int)$update_model->type) {
                        case 6:
                            $to_index[] = $document;
                            break;
                        case 61:
                            $to_delete[] = $document;
                            break;
                    }

                    $update_model->delete();
                }

                try {

                    if ($to_index) {
                        Xcart::app()->elastic->index($engine_name, $to_index);
                    }

                    if ($to_delete) {
                        Xcart::app()->elastic->delete($engine_name, array_map(static fn($doc) => $doc->id, $to_delete));
                    }

                } catch(Throwable $exception){
                    echo $exception->getMessage()."\n";
                }

                $bar->progress(100);
            }
            $bar->end();
        }
    }
}