<?php


namespace Modules\Goods\Commands;


use Exception;
use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\CustomAttribute;
use Google\Service\ShoppingContent\Error;
use Google\Service\ShoppingContent\Price;
use Google\Service\ShoppingContent\Product;
use Google\Service\ShoppingContent\ProductsCustomBatchRequest;
use Google\Service\ShoppingContent\ProductsCustomBatchRequestEntry;
use Google\Service\ShoppingContent\ProductShipping;
use Google\Service\ShoppingContent\ProductShippingDimension;
use Google\Service\ShoppingContent\ProductShippingWeight;
use Modules\Goods\Helpers\GoogleShoppingHelper;
use Modules\Goods\Models\ProductImageModel;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;
use Xcart\App\QueryBuilder\Q\QOrNot;
use Modules\Core\Models\StateModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Helpers\ProductHelper;
use Modules\Goods\Models\UpdatedProductModel;
use Modules\Shipping\Helpers\ShippingHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

class GoogleShoppingProductCommand extends Command
{
    public const BATCH_SIZE = 100;

    public function handle($arguments = []): void
    {

        /** @var SiteModel $site */
        foreach (SiteModel::objects()->filter(['marketplaces__marketplace_id' => 1])->order(['storefrontid']) as $site) {

            func_backprocess_log('incremental product feed', $l = "Storefront: $site->domain Storefrontid: $site->storefrontid");
            echo "$l\n";

            if (!$site->marketplaces) {
                continue;
            }
            $marketplace = $site->marketplaces->filter(['marketplace_id' => 1])->limit(1)->get();

            $lang = $site->lang->lang_code ?? 'en';

            $entries = [];

            /** @var UpdatedProductModel[] $up */
            while ($up = UpdatedProductModel::objects()
                ->select(['*', 'product__forsale', 'utype' => new Expression('MIN(type)')])
                ->filter(['product__sites__storefrontid' => $site->storefrontid, 'type' => 1, new QOr(['mask__isnull' => true, new QOrNot(['mask' => 0])])])
                ->group(['resourceid'])
                ->order(['-utype', '-product__forsale'])
                ->paginate(1, 100)->all()) {


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
        }
    }

    private static function sendMessage(array $entries, SiteModel $site): void
    {
        $message = [
            'products' => $entries,
            'site' => $site->pk
        ];

        Xcart::app()->queue->send('google_shopping_products', json_encode($message, JSON_THROW_ON_ERROR));
    }

}