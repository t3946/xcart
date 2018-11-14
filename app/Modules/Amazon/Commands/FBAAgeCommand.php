<?php

namespace Modules\Amazon\Commands;


use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use Modules\Amazon\Models\AmazonOfferModel;
use Modules\Amazon\Stores\AmazonPoolStore;
use Xcart\App\Commands\Command;

class FBAAgeCommand extends Command
{

    public function handle($arguments = [])
    {
        $log = " * * *  Cron started  * * * \n";
        func_backprocess_log('amazon_inventory_age', $log);
        echo $log;

        $start_time = new \DateTime('now');

        $amzPool = (new AmazonPoolStore())->getFeedAndReportClientPack();
        $result = $amzPool->callReqReport(MwsFeedAndReportClientPack::REPORT_FBA_INVENTORY_AGE)
                ->getRequestReportResult();

        /** @var \MarketplaceWebService_Model_ReportRequestInfo $info */
        $info = $result->getReportRequestInfo();

        $requets_id = $info->getReportRequestId();

        $process_status = null;

        while ($process_status !== MwsFeedAndReportClientPack::PROCESSING_STATUS_DONE) {
            /** @var \MarketplaceWebService_Model_GetReportRequestListResult $list_result */
            $list_result = $amzPool->callGetReportRequestList($requets_id, [MwsFeedAndReportClientPack::REPORT_FBA_INVENTORY_AGE])->getGetReportRequestListResult();

            /** @var \MarketplaceWebService_Model_ReportRequestInfo $list */
            $list = $list_result->getReportRequestInfoList()[0];
            $process_status = $list->getReportProcessingStatus();
            $report_id = $list->getGeneratedReportId();

            print $log = "ReportID {$report_id} | Status {$process_status}\n";
            func_backprocess_log('amazon_inventory_age', $log);

            if ($process_status === MwsFeedAndReportClientPack::PROCESSING_STATUS_DONE) {
                break;
            }
            sleep(60);
        }

        print $log = "Get Report Data | ReportID {$report_id} | Status {$process_status}\n";
        func_backprocess_log('amazon_inventory_age', $log);

        $feedHandle = @fopen('php://memory', 'rw+');

        $amzPool->callGetReport($report_id, $feedHandle);
        $report = stream_get_contents($feedHandle);
        @fclose($feedHandle);

        if ($report && ($rows = explode("\n", $report)) && array_shift($rows)) {
            foreach ($rows as $row) {
                $row_values = explode("\t", $row);

                if ($model = AmazonOfferModel::objects()->get(['ASIN' => $row_values[3]])) {
                    if ($row_values[12]) {
                        $days_supply = 365;
                    } elseif ($row_values[11]) {
                        $days_supply = 271;
                    } elseif ($row_values[10]) {
                        $days_supply = 181;
                    } elseif ($row_values[9]) {
                        $days_supply = 91;
                    } else {
                        $days_supply = 0;
                    }

                    $model->fba_days_of_supply = $days_supply;
                    $model->save();
                }
            }
        }


        $str_time = (new \DateTime('now'))->diff($start_time)->format('%H:%I:%S');

        func_backprocess_log('amazon_inventory_age', $log = "Cron completed. Processing time: {$str_time}\n");
        echo $log;
    }
}