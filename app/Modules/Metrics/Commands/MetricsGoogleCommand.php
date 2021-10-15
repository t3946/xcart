<?php

namespace Modules\Metrics\Commands;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V7\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Util\EnvironmentalVariables;
use Google\Ads\GoogleAds\Util\V8\ResourceNames;
use Google\Ads\GoogleAds\V8\Common\ManualCpc;
use Google\Ads\GoogleAds\V8\Enums\AdGroupStatusEnum\AdGroupStatus;
use Google\Ads\GoogleAds\V8\Enums\AdGroupTypeEnum\AdGroupType;
use Google\Ads\GoogleAds\V8\Enums\AdvertisingChannelTypeEnum\AdvertisingChannelType;
use Google\Ads\GoogleAds\V8\Enums\BudgetDeliveryMethodEnum\BudgetDeliveryMethod;
use Google\Ads\GoogleAds\V8\Enums\CampaignStatusEnum\CampaignStatus;
use Google\Ads\GoogleAds\V8\Resources\AdGroup;
use Google\Ads\GoogleAds\V8\Resources\Campaign;
use Google\Ads\GoogleAds\V8\Resources\Campaign\NetworkSettings;
use Google\Ads\GoogleAds\V8\Resources\CampaignBudget;
use Google\Ads\GoogleAds\V8\Services\AdGroupOperation;
use Google\Ads\GoogleAds\V8\Services\CampaignBudgetOperation;
use Google\Ads\GoogleAds\V8\Services\CampaignOperation;
use Google\Ads\GoogleAds\V8\Services\GoogleAdsRow;
use Modules\Metrics\Helpers\GoogleAds;
use Modules\Metrics\Helpers\GoogleAnalyticsMetrics;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Xml;

class MetricsGoogleCommand extends Command
{

    // TODO: Implement handle() method.
    public function handle($arguments = [])
    {
        $str_result = '';
        $google_ads = new GoogleAds("2953867572", "1434727382");
        $budget_id = $google_ads->getBudgetIdByCampaignId(14931487913);
        $google_ads->updateCampaignBudgetById($budget_id, ['amount_micros' => 1300000]);
/*        $campaigns = $google_ads->getAllCampaigns();*/
        $google_analytics = new GoogleAnalyticsMetrics();
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_source', GoogleAnalyticsMetrics::METRICS_SOURCE_AND_MEDIUM, 'source');
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