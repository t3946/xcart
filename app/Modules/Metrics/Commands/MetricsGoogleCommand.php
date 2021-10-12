<?php

namespace Modules\Metrics\Commands;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V7\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Util\EnvironmentalVariables;
use Google\Ads\GoogleAds\V8\Services\GoogleAdsRow;
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
















use GetOpt\GetOpt;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentNames;
use Google\Ads\GoogleAds\Examples\Utils\ArgumentParser;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsException;
use Google\Ads\GoogleAds\V8\Errors\GoogleAdsError;
use Google\ApiCore\ApiException;

/** This example gets all campaigns. To add campaigns, run AddCampaigns.php. */
class GetCampaigns
{
    private const CUSTOMER_ID = 'INSERT_CUSTOMER_ID_HERE';

    public static function main()
    {

        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        // Construct a Google Ads client configured from a properties file and the
        // OAuth2 credentials above.
        $googleAdsClient = (new GoogleAdsClientBuilder())
            ->fromFile()
            ->withOAuth2Credential($oAuth2Credential)
            ->build();

        try {
            self::runExample(
                $googleAdsClient,
                592-815-8810
            );
        } catch (GoogleAdsException $googleAdsException) {
            printf(
                "Request with ID '%s' has failed.%sGoogle Ads failure details:%s",
                $googleAdsException->getRequestId(),
                PHP_EOL,
                PHP_EOL
            );
            foreach ($googleAdsException->getGoogleAdsFailure()->getErrors() as $error) {
                /** @var GoogleAdsError $error */
                printf(
                    "\t%s: %s%s",
                    $error->getErrorCode()->getErrorCode(),
                    $error->getMessage(),
                    PHP_EOL
                );
            }
            exit(1);
        } catch (ApiException $apiException) {
            printf(
                "ApiException was thrown with message '%s'.%s",
                $apiException->getMessage(),
                PHP_EOL
            );
            exit(1);
        }
    }

    /**
     * Runs the example.
     *
     * @param GoogleAdsClient $googleAdsClient the Google Ads API client
     * @param int $customerId the customer ID
     */
    public static function runExample(GoogleAdsClient $googleAdsClient, int $customerId)
    {
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all campaigns.
        $query = 'SELECT campaign.id, campaign.name FROM campaign ORDER BY campaign.id';
        // Issues a search stream request.
        /** @var GoogleAdsServerStreamDecorator $stream */
        $stream =
            $googleAdsServiceClient->searchStream($customerId, $query);

        // Iterates over all rows in all messages and prints the requested field values for
        // the campaign in each row.
        foreach ($stream->iterateAllElements() as $googleAdsRow) {
            /** @var GoogleAdsRow $googleAdsRow */
            printf(
                "Campaign with ID %d and name '%s' was found.%s",
                $googleAdsRow->getCampaign()->getId(),
                $googleAdsRow->getCampaign()->getName(),
                PHP_EOL
            );
        }
    }
}