<?php

namespace Modules\Metrics\Commands;

use Modules\Metrics\Helpers\GoogleAnalyticsMetrics;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Xcart\App\Commands\Command;

class MetricsGoogleCommand extends Command
{

    // TODO: Implement handle() method.
    public function handle($arguments = [])
    {
        $str_result = '';
        $google_analytics = new GoogleAnalyticsMetrics();
        $str_result .= $google_analytics->getMetricsCountries('google_analytics_countries');
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_users', GoogleAnalyticsMetrics::METRICS_USERS);
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_session', GoogleAnalyticsMetrics::METRICS_SESSION);
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_session_bounce', GoogleAnalyticsMetrics::METRICS_SESSION_BOUNCE);
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_city', GoogleAnalyticsMetrics::METRICS_CITY);
        if (!empty($str_result)) {
            MetricsDataHelper::pushMetrics('google-analytics', "$str_result\n");
        }
//        foreach (self::METRICS_INFO as $name_metrics => $attr_metrics) {
//            if (count($accounts->getItems()) > 0) {
//                $items = $accounts->getItems();
//                $firstAccountId = $items[0]->getId();
//
//                $properties = $analytics->management_webproperties->listManagementWebproperties($firstAccountId);
//                foreach ($properties as $property) {
//                    $request = new \Google_Service_AnalyticsReporting_ReportRequest();
//                    if (!empty($attr_metrics['metrics'])) {
//                        $metrics = new \Google_Service_AnalyticsReporting_Metric();
//                        $metrics->setExpression($attr_metrics['metrics']);
//                        $request->setMetrics([$metrics]);
//                    }
//                    if (!empty($attr_metrics['dimensions'])) {
//                        $dimension = new \Google_Service_AnalyticsReporting_Dimension();
//                        $dimension->setName($attr_metrics['dimensions']);
//                        $request->setDimensions([$dimension]);
//                    }
//                    $name = $property->name;
//                    $request->setViewId($property->defaultProfileId);
//                    $request->setDateRanges($dateRange);
//
//                    $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
//                    $body->setReportRequests([$request]);
//                    $data = $analytics_reporting->reports->batchGet($body);
//                    $value = $this->getValue($data);
//                    if (!empty($value)) {
//                        $str_result .= MetricsDataHelper::convertToMetricsWithParams($name_metrics, $value, [
//                            'site' => $name
//                        ]);
//                    }
//                }
//            }
//        }
    }
}