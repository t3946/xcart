<?php


namespace Modules\Goods\Commands;


use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;
use Xcart\App\QueryBuilder\Q\QOrNot;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;

class GoogleShoppingProductCommand extends Command
{
    public const BATCH_SIZE = 100;

    public function handle($arguments = []): void
    {

        /** @var SiteModel $site */
        foreach (SiteModel::objects()->filter(['marketplaces__marketplace_id' => 1])->order(['storefrontid']) as $site) {

            echo "Storefront: $site->domain Storefrontid: $site->storefrontid\n";

            if (!$site->marketplaces) {
                continue;
            }

            $entries = [];

            /** @var UpdatedProductModel[] $up */
            while ($up = UpdatedProductModel::objects()
                ->select(['*', 'product__forsale', 'utype' => new Expression('MIN(type)')])
                ->filter(['product__sites__storefrontid' => $site->storefrontid, 'type' => 1, new QOr(['mask__isnull' => true, new QOrNot(['mask' => 0])])])
                ->group(['resourceid'])
                ->paginate(1, 10000)->all()) {

                foreach ($up as $model) {
                    if (($product = $model->product) && !$product->isGroupRoot()) {

                        $entries[] = $product->pk;

                        if (count($entries) === self::BATCH_SIZE) {

                            self::sendMessage($entries, $site);

                            $entries = [];
                        }
                    }
                    $model->delete();
                }
            }
            self::sendMessage($entries, $site);
        }
    }

    private static function sendMessage(array $entries, SiteModel $site): void
    {
        if ($entries) {
            $message = [
                'products' => $entries,
                'site' => $site->pk
            ];

            Xcart::app()->queue->send('google_shopping_products', json_encode($message, JSON_THROW_ON_ERROR));
        }
    }

}