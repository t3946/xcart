<?php


namespace Modules\Goods\Commands;


use Xcart\App\QueryBuilder\Q\QOr;
use Modules\Goods\Models\ImageDModel;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

class ImageGeneratorCommand extends Command
{

    public function handle($arguments = [])
    {
        $sleep_time = 2; //  seconds
        $cnt = 0;

        /** @var ImageDModel $image */
        foreach (ImageDModel::objects()->filter([
            new QOr(['product__thumbnail__imageid__isnull' => true, 'product__preview__imageid__isnull' => true]),
            'product__forsale' => 'Y',
        ])->limit(500) as $image) {
            $image_data = $image->getAttributes();
            $image_data['image_path'] = empty($image_data['image_path']) ?: Paths::get('www') . DIRECTORY_SEPARATOR . $image_data['image_path'];

            if (!file_exists($image_data['image_path'])) {
                $image->delete();
                continue;
            }

            $image_data = func_set_correct_det_img($image_data, true);
            $product = $image->product;

            print("Product: {$product->productcode} - Image ID: {$image->imageid}\n\r");

            if (!$product->thumbnail->count()) {
                if (func_generate_image($product->productid, 'D', 'T', false, false, $image->imageid)) {
                    func_save_product_thumb_image($product->productid, 'T');
                } else {
                    $log_text = "{$image->imageid} - Error generate thumbnail. Delete image file {$image_data['image_path']} from {$product['productcode']}";
                    if (file_exists($image_data['image_path'])) {
                        unlink($image_data['image_path']);
                    }
                    $image->delete();
                    func_backprocess_log("image generator", $log_text);
                }
            }
            if (!$product->preview->count()) {
                if (func_generate_image($product->productid, 'D', 'P', false, false, $image->imageid)) {
                    func_save_product_thumb_image($product->productid, 'P');
                } else {
                    $log_text = "{$image->imageid} - Error generate thumbnail. Delete image file {$image_data['image_path']} from {$product['productcode']}";
                    if (file_exists($image_data['image_path'])) {
                        unlink($image_data['image_path']);
                    }
                    $image->delete();
                    func_backprocess_log("image generator", $log_text);
                }
            }

            $cnt++;
            if ($cnt % 10 == 0) {
                func_flush(".");
                if($cnt % 500 == 0) {
                    func_flush("<br />\n");
                }
                func_flush();
            }

            sleep($sleep_time);
        }
    }
}