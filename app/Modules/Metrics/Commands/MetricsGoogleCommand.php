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
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_referrals', GoogleAnalyticsMetrics::METRICS_REFERRAL, 'referral');
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_source', GoogleAnalyticsMetrics::METRICS_SOURCE, 'source');
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_city', GoogleAnalyticsMetrics::METRICS_CITY, 'city');
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_countries', GoogleAnalyticsMetrics::METRICS_COUNTRY, 'country');
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_users', GoogleAnalyticsMetrics::METRICS_USERS);
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_session', GoogleAnalyticsMetrics::METRICS_SESSION);
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_session_bounce', GoogleAnalyticsMetrics::METRICS_SESSION_BOUNCE);
        if (!empty($str_result)) {
            MetricsDataHelper::pushMetrics('google-analytics', "$str_result\n");
        }
    }
}