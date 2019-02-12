<?php

namespace Modules\Goods\Commands;


use Aws\S3\S3Client;
use DateTime;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use Modules\Distributor\Models\DistributorFeedFieldModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Goods\Helpers\SupplierFeedHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Stores\SupplierFeedStore;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class ProductFeedCommand extends Command
{

    /**
     * @param array $arguments
     * @throws \Doctrine\DBAL\DBALException
     */
    public function handle($arguments = [])
    {
        $start_supplier_time = new DateTime();

        /** @var ProductModel $modelProduct */

        $client = new S3Client([
            'credentials' => [
                'key' => 'AKIAJPPHD4UZNVAWOBZQ',
                'secret' => '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'
            ],
            'region' => 'us-east-1',
            'version' => 'latest',
        ]);

        $adapter = new AwsS3Adapter($client, 's3-feeds');
        $filesystem = new Filesystem($adapter);

        $suppliers = SupplierFeedModel::objects()->filter(['enabled' => 'Y', 'feed_type' => 'P']);

        /** @var SupplierFeedModel $feed */
        foreach ($suppliers as $feed) {
            $info = pathinfo($feed->feed_file_name);

            try {
                $md5 = $filesystem->get("{$info['filename']}.md5");
            } catch (FileNotFoundException $e) {
                $log = "manufacturerid: {$feed->manufacturerid}. md5 file is not found. Skipped. \n";
                Xcart::app()->logger->error($log, [$e->getMessage()], 'feed');
                continue;
            }

            if (($md5_value = $md5->read()) === $feed->last_md5) {
                $log = "manufacturerid: {$feed->manufacturerid}. md5 = last_md5. Feed skipped. md5file: {$md5_value} - md5db: {$feed->last_md5} \n";
                Xcart::app()->logger->error($log, [], 'feed');
                continue;
            }

            $log = "md5file: {$md5_value} - md5db: {$feed->last_md5}\n";
            Xcart::app()->logger->debug($log, [], 'feed');

            try {
                $content = $filesystem->get($feed->feed_file_name);
            } catch (FileNotFoundException $e) {
                $log = "manufacturerid: {$feed->manufacturerid}. File read error. Skipped.\n";
                Xcart::app()->logger->error($log, [$e->getMessage()], 'feed');
                continue;
            }

            $supplierFeed = new SupplierFeedStore($feed, $content->read());

            /** @var SupplierFeedStore $supplierFeed */
            if (!$supplierFeed->isValid()) {
                $log = implode($supplierFeed->errors, PHP_EOL);
                Xcart::app()->logger->error($log, [], 'feed');
                continue;
            }

            $create_date_time_diff = time() - $supplierFeed->getFeedDate();

            $log = "manufacturerid: {$feed->manufacturerid}. Started. ({$feed->feed_type})\n";
            Xcart::app()->logger->debug($log, [], 'feed');

            $all_feed_productcodes = $duplicate_sku = $lastFeedFields = [];
            $inserted_products_count = $skippedProductsCount = $new_products_count = $updated_products_count = $discontinued_products_count = 0;

            foreach ($supplierFeed->products as $kp => $prod) {
                $products = [];

                if (isset($prod['is_group']) && $prod['is_group'] === true) {
                    if ($prod['child_products']) {
                        $products = SupplierFeedHelper::feedChilds($prod, $supplierFeed);
                    }
                } else {
                    $products[] = $prod;
                }

                foreach ($products as $aProduct) {

                    print($kp . ' --> ' . $aProduct['productcode'] . "\n");

                    if (!$aProduct['productcode'] || (isset($aProduct['cost_to_us']) && (float)$aProduct['cost_to_us'] <= 0)) {
                        print("Skip product -->' \n");
                        $skippedProductsCount++;
                        continue;
                    }

                    [$modelProduct, $is_created] = ProductModel::objects()->getOrNew(['productcode' => $aProduct['productcode']]);

                    if (\in_array($modelProduct->productcode, $all_feed_productcodes, true)) {
                        $duplicate_sku[] = $modelProduct->productcode;
                    } else {
                        $all_feed_productcodes[] = $modelProduct->productcode;
                    }

                    switch ($feed->feed_type) {
                        case 'I' :

                            if ($is_created) {
                                $new_products_count++;
                                continue 2;
                            }
                            $modelProduct->controlled_by_feed = $feed->feed_file_name;

                            break;
                        case 'P' :

                            if (!isset($aProduct['is_group'])) {
                                if (!isset($aProduct['cost_to_us'])) {
                                    print("Skip product --> 'No cost_to_us' \n");
                                    $skippedProductsCount++;
                                    continue 2;
                                }
                                if (!$is_created && $feed->add_new_only === "Y") {
                                    print("Skip product --> 'Add new only' \n");
                                    $skippedProductsCount++;
                                    continue 2;
                                }
                            }

                            if ($is_created) {
                                $modelProduct->save();
                                print 'Add product --> OK' . PHP_EOL;
                            }
                            break;
                    }

                    $modelProduct->setAttributes($aProduct);

                    $modelProduct = SupplierFeedHelper::feedProduct($modelProduct, $is_created, $feed, $aProduct, $supplierFeed->dont_update_fields, $supplierFeed->defaults);

                    if ($is_created) {
                        $new_products_count++;
                        $inserted_products_count++;
                        $modelProduct->save();
                    } else if ($changed = SupplierFeedHelper::getChanged($modelProduct)) {
                        print_r($changed);
                        $updated_products_count++;
                        $modelProduct->save();
                    }

                    $last_feed_fields_arr_vals = $aProduct;
                    $lastFeedFields = array_unique(array_merge($lastFeedFields, array_keys($aProduct)));
                }
            }

            $discontinued_products_count = SupplierFeedHelper::discontinueProducts($all_feed_productcodes, $feed);
            print "Discontinued {$discontinued_products_count} products \n";

            $params = [
                'md5' => $md5_value,
                'last_feed_fields_arr_vals' => $last_feed_fields_arr_vals,
                'new_products_count' => $new_products_count,
                'updated_products_count' => $updated_products_count,
                'inserted_products_count' => $inserted_products_count,
                'discontinued_products_count' => $discontinued_products_count,
                'skippedProductsCount' => $skippedProductsCount,
                'duplicate_sku' => $duplicate_sku,
                'start_supplier_time' => $start_supplier_time
            ];

            Xcart::app()->logger->debug(SupplierFeedHelper::feedStatistic($feed, $params), [], 'feed');;

            if (!empty($lastFeedFields)) {
                if (!empty($supplierFeed->dont_update_fields)) {
                    $lastFeedFields = array_diff($lastFeedFields, $supplierFeed->dont_update_fields);
                }

                DistributorFeedFieldModel::objects()->filter([
                    'manufacturerid' => $feed->manufacturerid,
                    'feed_id' => $feed->feed_id
                ])
                    ->update(['locked' => 'N']);

                foreach ($lastFeedFields as $fieldName) {

                    /** @var DistributorFeedFieldModel $FieldModel */

                    [$FieldModel] = DistributorFeedFieldModel::objects()->getOrNew([
                        'field_name' => $fieldName,
                        'manufacturerid' => $feed->manufacturerid,
                        'feed_id' => $feed->feed_id
                    ]);
                    $FieldModel->locked = 'Y';
                    $FieldModel->save();
                }
            }

        }
    }
}