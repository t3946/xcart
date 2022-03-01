<?php

namespace Modules\Goods\Commands;

use Exception;
use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\ProductsCustomBatchRequest;
use Modules\Goods\Helpers\GoogleShoppingHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Sites\Models\SiteMarketplaceModel;
use Modules\Sites\Models\SiteModel;
use PhpAmqpLib\Message\AMQPMessage;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;
use Xcart\App\Main\Xcart;

class QueueGoogleShoppingCommand extends Command
{
    private ShoppingContent $service;

    public function handle($arguments = [])
    {
        $client = new Client(['verify' => false]);
        $client->setApplicationName('Google Feed');
        $client->setAuthConfig(Paths::get('www') . '/include/system/gapi-3c467d1a8e76.json');
        $client->addScope(ShoppingContent::CONTENT);

        $this->service = new ShoppingContent($client);

        Xcart::app()->queue->consume('google_shopping_products', [$this, 'consume']);
    }

    public function consume(AMQPMessage $message): void
    {
        $entries = [];

        try {
            if ($message->body) {
                $data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR);

                /** @var Sitemodel $site */
                $site = SiteModel::objects()->get(['pk' => $data['site']]);

                if (!$site) {
                    return;
                }

                $lang = $site->lang->lang_code ?? 'en';

                /** @var SiteMarketplaceModel $marketplace */
                $marketplace = $site->marketplaces->filter(['marketplace_id' => 1])->limit(1)->get();

                foreach ($data['products'] as $product_id) {
                    /** @var ProductModel $product */
                    $product = ProductModel::objects()->get(['pk' => $product_id]);

                    $entry = GoogleShoppingHelper::getGoogleShoppingEntity($product, $marketplace, $lang);

                    $entries[] = $entry;
                }

                if ($entries) {

                    $batchReq = new ProductsCustomBatchRequest();
                    $batchReq->setEntries($entries);

                    try {
                        echo "GB: tried to submit {$batchReq->count()} items as product feed ($site->code)\n";
                        $this->service->products->customBatch($batchReq);

                    } catch (Exception $e) {
                        echo "{$e->getMessage()}\n";
                    }
                }
            }
        } finally {
            $message->ack();
        }
    }
}