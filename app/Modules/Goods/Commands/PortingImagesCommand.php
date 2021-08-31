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
        SELECT i1.*
FROM xcart_images_D AS i1
LEFT JOIN xcart_product_images i2 ON i1.md5 = i2.hash 
INNER JOIN xcart_products p ON i1.id = p.productid AND p.forsale = 'Y'
WHERE i2.image_id IS NULL
AND i1.avail = 'Y'
AND image_path NOT REGEXP '/D/[0-9]{1,3}_'
ORDER BY id desc, orderby
SQL;

        $stmt = Connection::getInstance()->executeQuery($sql);

        $images_data = $stmt->fetchAll();
        foreach ($images_data as $data) {
            /** @var ProductModel $product */

            $product = ProductModel::objects()->get(['productid' => $data['id']]);
            $image = ImageDModel::objects()->get(['imageid' => $data['imageid']]);

            $site = $product->sites->limit(1)->get();
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