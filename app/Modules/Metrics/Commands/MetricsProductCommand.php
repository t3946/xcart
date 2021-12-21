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
        foreach (DistributorModel::objects()->filter(['avail' => 'Y'])->all() as $distributor_model) {
            $name = preg_replace('/[^a-zA-Z0-9\s]/iu', '', (string)$distributor_model);
            $name = "[$distributor_model->code] $name";
            $count_without_picture = $distributor_model->products->filter(['detail_images__image_id__isnull' => true, 'forsale' => 'Y'])->group(['productid'])->count();

            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_active', $distributor_model->active_products, [
                'dx_code' => $name
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_google_ads', $distributor_model->ads_products, [
                'dx_code' => $name
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_dx_without_picture', $count_without_picture, [
                'dx_code' => $name
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('distributor_shipping_value', $distributor_model->free_shipping_on_orders_over_value, [
               'dx_code' => $name
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('distributor_max_lead_time', $distributor_model->dx_leadtime_to, [
               'dx_code' => $name
            ]);
        }
        foreach (SiteModel::getAllEnabled() as $site_model) {
            $products_count = $site_model->products->filter(['is_group_root' => false, 'forsale' => 'Y'])->count();
            $products_ads_count = $site_model->products->filter(['google_ads__shopping_status' => GoogleProductsModel::SHOPPING_STATUS_APPROVED])->count();
            $count_without_picture = $site_model->products->filter(['detail_images__image_id__isnull' => true, 'forsale' => 'Y'])->group(['productid'])->count();

            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_site', $products_count, [
                'site' => (string)$site_model
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_site_google_ads', $products_ads_count, [
                'site' => (string)$site_model
            ]);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams('products_site_without_picture', $count_without_picture, [
               'site' => (string)$site_model
            ]);
        }
        MetricsDataHelper::pushMetrics('products', "$str_result\n");
    }
}