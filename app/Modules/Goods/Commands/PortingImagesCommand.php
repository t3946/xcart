<?php

namespace Modules\Goods\Commands;

use Modules\Goods\Models\ImageDModel;
use Modules\Goods\Models\ProductModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class PortingImagesCommand extends Command
{

    public function handle($arguments = [])
    {
        $sql = <<<SQL
        SELECT p.productid FROM xcart_products p
LEFT JOIN xcart_products_images i ON i.product_id = p.productid
WHERE forsale ='Y'
AND i.image_id IS NULL
AND EXISTS (SELECT 1 FROM xcart_images_D d WHERE d.id = p.productid)
SQL;

        $stmt = Connection::getInstance()->executeQuery($sql);

        $images_data = $stmt->fetchAll();
        foreach ($images_data as $data) {
            /** @var ProductModel $product */

            $product = ProductModel::objects()->get(['productid' => $data['productid']]);
            $site = $product->sites->limit(1)->get();

            foreach (ImageDModel::objects()->filter(['id' => $data['productid']])->all() as $image) {
                $pref = $site->Enable_CDN ? 'cdn.': 'www.';
                $domain = $site->getBaseDomain();
                $image_url = 'https://' .$pref . $domain . $image->getURL();

                $action = [
                    'product_id' => $product->pk,
                    'dx_code' => $product->distributor->code,
                    'image_position' => 0,
                    'image_link' => $image_url,
                    'action' => 'create'
                ];
                Xcart::app()->queue->send('images_action', json_encode($action, JSON_THROW_ON_ERROR));
                print_r($action);
            }
        }
    }
}