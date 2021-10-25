<?php

namespace Modules\Metrics\Commands;

use Modules\Brand\Models\BrandModel;
use Modules\Distributor\Models\DistributorModel;
use Modules\Goods\Models\GoogleProductsModel;
use Modules\Goods\Models\ProductModel;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;

class MetricsProductCommand extends Command
{

    public function handle($arguments = [])
    {
        $str_result = '';
        /** @var DistributorModel $distributor_model */
        foreach (DistributorModel::objects()->all() as $distributor_model) {
            $active_products = ProductModel::without_group()->filter(['manufacturerid' => $distributor_model->pk, 'forsale' => 'Y'])->count();
            $ad_products = $distributor_model->products_active->filter(['google_ads__shopping_status' => GoogleProductsModel::SHOPPING_STATUS_APPROVED])->count();

            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_active', $active_products, [
                'dx_code' => $distributor_model->code
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_google_ads', $ad_products, [
                'dx_code' => $distributor_model->code
            ]);
        }
        /** @var BrandModel $brand_model */
        foreach (BrandModel::objects()->filter(['avail' => 'Y'])->all() as $brand_model) {
            $count_products = ProductModel::without_group()->filter(['brandid' => $brand_model->pk, 'forsale' => 'Y'])->count();
            $count_ads_products = $brand_model->products_active->filter(['google_ads__shopping_status' => GoogleProductsModel::SHOPPING_STATUS_APPROVED])->count();

            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_active_brand', $count_products, [
                'brand' => (string)$brand_model
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_google_ads', $count_ads_products, [
                'brand' => (string)$brand_model
            ]);
        }
    }
}