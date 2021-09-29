<?php

namespace Modules\Goods\Commands;

use Modules\Goods\Models\GoogleProductsModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Commands\Command;
use Xcart\External_Marketplaces\Marketplaces\GMC;
use Xcart\External_Marketplaces\StoreFrontMarketPlace;

class GoogleShoppingProductStatusCommand extends Command
{

    private const BACK_PROCESS_LOG_NAME = 'google_product_statuses';

    public function handle($arguments = [])
    {
        $start_time = time();

        $log_text = " * * *  Cron started  * * * ";
        func_backprocess_log(self::BACK_PROCESS_LOG_NAME, $log_text);

        GoogleProductsModel::objects()->delete();

        foreach (SiteModel::objects() as $site) {
            foreach (StoreFrontMarketPlace::getMarketPlacesByStoreFront($site->pk) as $oMarketPlace) {
                if ($oMarketPlace instanceof GMC) {
                    func_backprocess_log(self::BACK_PROCESS_LOG_NAME, sprintf('---Storefront %d---', $site->pk));
                    $oMarketPlace->getProductStatuses();
                }
            }
        }

        $current_time = time();

        $pid_diff = $current_time - $start_time;

        $hour = intval($pid_diff / (60 * 60));
        $minutes = intval(($pid_diff - $hour * 60 * 60) / 60);
        $seconds = ($pid_diff - $hour * 60 * 60 - $minutes * 60);
        $str_time = sprintf("%02d:%02d:%02d", $hour, $minutes, $seconds);

        $log_text = "Cron completed. Processing time: $str_time";
        func_backprocess_log(self::BACK_PROCESS_LOG_NAME, $log_text);

        die("DONE!");
    }
}