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
            $name = preg_replace('/[^a-zA-Z0-9\s]/iu', '', (string)$distributor_model);
            $name = "[$distributor_model->code] $name";

            $active_products = ProductModel::without_group()->filter(['manufacturerid' => $distributor_model->pk, 'forsale' => 'Y'])->count();
            $ad_products = $distributor_model->products_active->filter(['google_ads__shopping_status' => GoogleProductsModel::SHOPPING_STATUS_APPROVED])->count();

            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_active', $active_products, [
                'dx_code' => $name
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_google_ads', $ad_products, [
                'dx_code' => $name
            ]);
        }
        foreach (SiteModel::getAllEnabled() as $site_model) {
            $products_count = $site_model->products->filter(['is_group_root' => false, 'forsale' => 'Y'])->count();
            $products_ads_count = $site_model->products->filter(['google_ads__shopping_status' => GoogleProductsModel::SHOPPING_STATUS_APPROVED])->count();
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_site', $products_count, [
                'site' => (string)$site_model
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_site_google_ads', $products_ads_count, [
                'site' => (string)$site_model
            ]);
        }
        MetricsDataHelper::pushMetrics('products', "$str_result\n");
    }
}