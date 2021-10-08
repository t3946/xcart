<?php

namespace Modules\Metrics\Helpers;

use Google\Service\AnalyticsReporting\GetReportsResponse;

class GoogleAnalyticsMetrics
{
    public const METRICS_COUNTRY = 'ga:country';
    public const METRICS_SESSION = 'ga:sessions';
    public const METRICS_USERS = 'ga:newUsers';
    public const METRICS_SESSION_BOUNCE = 'ga:bounceRate';
    public const METRICS_CITY = 'ga:city';
    public const METRICS_LATITUDE = 'ga:latitude';
    public const METRICS_LONGITUDE = 'ga:longitude';
    public const METRICS_SOURCE = 'ga:source';
    public const METRICS_REFERRAL = 'ga:referralPath';

    private \Google_Client $client;
    private \Google_Service_AnalyticsReporting $service_reporting;
    public array $sites;

    public function __construct(string $app_name = 's3')
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . 'app/Modules/Metrics/Commands/keyAnalytics.json';
        $this->client = new \Google_Client();
        $this->client->setApplicationName("s3");
        $this->client->setAuthConfig($path);
        $this->client->setScopes(\Google_Service_Analytics::ANALYTICS_READONLY);
        $this->service_reporting = new \Google_Service_AnalyticsReporting($this->client);

        $account = new \Google_Service_Analytics($this->client);
        $this->initSites($account);
    }

    private function initSites(\Google_Service_Analytics $service_analytics)
    {
        $accounts = $service_analytics->management_accounts->listManagementAccounts();
        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            $properties = $service_analytics->management_webproperties->listManagementWebproperties($firstAccountId);
            foreach ($properties as $property) {
                $this->sites[] = ['name' => $property->name, 'id' => $property->defaultProfileId];
            }
        }
    }

    public function getMultiMetrics(string $name_metrics, string $google_code_metrics, string $name_field): string
    {
        $str_result = '';
        $reports = $this->getMetrics($google_code_metrics, true);
        foreach ($reports as $site => $report) {
            $ar_value = $this->getReportValue($report, true);
            foreach ($ar_value as $value) {
                $str_result .= MetricsDataHelper::convertToMetricsWithParams($name_metrics, $value['value'], [
                    'site' => $site,
                    $name_field => $value['dimension'][$google_code_metrics]
                ]);
            }
        }
        return $str_result;
    }

    public function getSingleMetrics(string $name_metrics, string $google_code_metrics): string
    {
        $str_result = '';
        $reports = $this->getMetrics($google_code_metrics);
        foreach ($reports as $site => $report) {
            $value = $this->getReportValue($report);
            $str_result .= MetricsDataHelper::convertToMetricsWithParams($name_metrics, $value ?: 0, [
                'site' => $site,
            ]);
        }
        return $str_result;
    }

    /** Get Google Analytics data during day
     * @param string $google_metrics - example: ga:newUsers, ga:bounceRate (read https://ga-dev-tools.web.app/dimensions-metrics-explorer/)
     * @return array
     */
    public function getMetrics(string $google_metrics, $is_dimensions = false): array
    {
        $ar_metrics = [];
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate((new \DateTime('-1 days'))->format('Y-m-d'));
        $dateRange->setEndDate((new \DateTime())->format('Y-m-d'));

        foreach ($this->sites as $site) {
            $request = new \Google_Service_AnalyticsReporting_ReportRequest();
            if (!$is_dimensions) {
                $metrics = new \Google_Service_AnalyticsReporting_Metric();
                $metrics->setExpression($google_metrics);
                $request->setMetrics([$metrics]);
            } else {
                $dimension = new \Google_Service_AnalyticsReporting_Dimension();
                $dimension->setName($google_metrics);
                $request->setDimensions([$dimension]);
            }
            $request->setViewId($site['id']);
            $request->setDateRanges($dateRange);

            $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
            $body->setReportRequests([$request]);
            $ar_metrics[$site['name']] = $this->service_reporting->reports->batchGet($body);
        }
        return $ar_metrics;
    }

    private function getReportValue(GetReportsResponse $reports, bool $is_multi = false)
    {
        $ar_result = [];
        foreach ($reports as $report) {
            $header = $report->getColumnHeader();
            $dimensionHeaders = $header->getDimensions();
            $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
            $rows = $report->getData()->getRows();
            foreach ($rows as $row) {
                $dimensions = $row->getDimensions();
                $metrics = $row->getMetrics();
                foreach ($metrics as $metric) {
                    $values = $metric->getValues();
                    foreach ($values as $value) {
                        if (!$is_multi) {
                            return $value;
                        }
                        $ar_result[] = ['dimension' => array_combine($dimensionHeaders, $dimensions), 'value' => $value];

                    }
                }
            }
        }
        return $ar_result;
    }

}