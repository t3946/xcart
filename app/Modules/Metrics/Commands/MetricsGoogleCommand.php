<?php

namespace Modules\Metrics\Commands;

use Modules\Metrics\Helpers\MetricsDataHelper;
use Xcart\App\Commands\Command;

class MetricsGoogleCommand extends Command
{

    // TODO: Implement handle() method.
    public function handle($arguments = [])
    {
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate((new \DateTime('-1 days'))->format('Y-m-d'));
        $dateRange->setEndDate((new \DateTime())->format('Y-m-d'));
        $key = __DIR__ . "/keyAnalytics.json";
        $client = new \Google_Client();
        $client->setApplicationName("s3");
        $client->setAuthConfig($key);
        $client->setScopes(\Google_Service_Analytics::ANALYTICS_READONLY);
        $analytics_reporting = new \Google_Service_AnalyticsReporting($client);
//        $analytics = new \Google_Service_Analytics($client);
//        $accounts = $analytics->management_accounts->listManagementAccounts();

/*        $analytics = new \Google_Service_Analytics($client);*/

        $metrics = new \Google_Service_AnalyticsReporting_Metric();
        // выражение показателя (в данном случае простое имя ga:pageviews – количество просмотров)
        $metrics->setExpression("ga:sessions");

//        //Create the Dimensions object.
//        $browser = new \Google_Service_AnalyticsReporting_Dimension();
//        $browser->setName("ga:sessionCount");

        // Create the ReportRequest object.
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId("76177945");
        $request->setMetrics([$metrics]);
        $request->setDateRanges($dateRange);
//        $request->setDimensions([$browser]);
//        $request->setMetrics(array($sessions));

        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);
        $a = $analytics_reporting->reports->batchGet($body);
        $this->printResults($a);
    }

    public function printResults($reports)
    {
        $results = '';
        for ($reportIndex = 0; $reportIndex < count($reports); $reportIndex++) {
            $report = $reports[$reportIndex];
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();

            for ($rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
                $row = $rows[$rowIndex];
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                foreach ($metrics as $metric) {
                    $values = $metric->getValues();
                    foreach ($values as $value) {
                        $results .= MetricsDataHelper::convertToMetricsWithParams('google_analytics_session', $value, [
                            'site' => ''
                        ]);
                    }
                }
            }
        }
    }
}