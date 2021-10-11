<?php

namespace Modules\Metrics\Commands;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V7\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\Lib\V8\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Util\EnvironmentalVariables;
use Google\Ads\GoogleAds\V8\Services\GoogleAdsRow;
use Modules\Metrics\Helpers\GoogleAnalyticsMetrics;
use Modules\Metrics\Helpers\MetricsDataHelper;
use Google\AdsApi\AdWords\Reporting;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Xml;

class MetricsGoogleCommand extends Command
{

    // TODO: Implement handle() method.
    public function handle($arguments = [])
    {
        $str_result = '';
        $this->adWordsStats();
/*        $google_analytics = new GoogleAnalyticsMetrics();
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_source', GoogleAnalyticsMetrics::METRICS_SOURCE_AND_MEDIUM, 'source');
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_city', GoogleAnalyticsMetrics::METRICS_CITY, 'city');
        $str_result .= $google_analytics->getMultiMetrics('google_analytics_countries', GoogleAnalyticsMetrics::METRICS_COUNTRY, 'country');
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_users', GoogleAnalyticsMetrics::METRICS_USERS);
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_session', GoogleAnalyticsMetrics::METRICS_SESSION);
        $str_result .= $google_analytics->getSingleMetrics('google_analytics_session_bounce', GoogleAnalyticsMetrics::METRICS_SESSION_BOUNCE);
        if (!empty($str_result)) {
            MetricsDataHelper::pushMetrics('google-analytics', "$str_result\n");
        }*/
    }

    public function adWordsStats()
    {
/*        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();

        $googleAdsClient = (new GoogleAdsClientBuilder())
            ->fromFile()
            ->withOAuth2Credential($oAuth2Credential)
            ->build();
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();*/
        $a = AuthenticateInWebApplication::main();
/*        $res = GetCampaigns::main();*/

    }
}

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Message\Response;
use React\Http\Server;
use UnexpectedValueException;

/**
 * This example will create an OAuth2 refresh token for the Google Ads API using the Web application
 * flow.
 *
 * <p>This example will start a basic server that listens for requests at `http://localhost:PORT`,
 * where `PORT` is dynamically assigned.
 *
 * <p>IMPORTANT: You must add `http://localhost/oauth2callback` to the "Authorize redirect
 * URIs" list in your Google Cloud Console project before running this example.
 */
class AuthenticateInWebApplication
{

    /**
     * @var string the OAuth2 scope for the Google Ads API
     * @see https://developers.google.com/google-ads/api/docs/oauth/internals#scope
     */
    private const SCOPE = 'https://www.googleapis.com/auth/adwords';

    /**
     * @var string the Google OAuth2 authorization URI for OAuth2 requests
     * @see https://developers.google.com/identity/protocols/OAuth2InstalledApp#step-2-send-a-request-to-googles-oauth-20-server
     */
    private const AUTHORIZATION_URI = 'https://accounts.google.com/o/oauth2/v2/auth';

    /**
     * @var string the OAuth2 call back URL path.
     */
    private const OAUTH2_CALLBACK_PATH = '/oauth2callback';

    public static function main()
    {
        if (!class_exists(Server::class)) {
            echo 'Please install "react/http" package to be able to run this example';
            exit(1);
        }

        $loop = Factory::create();
        // Creates a socket for localhost with random port.
        $socket = new \React\Socket\Server(0, $loop);

        $clientId = trim("193950091514-p4980nf62bu5phb6rsiktj1utn5dimoj.apps.googleusercontent.com");
        $clientSecret = trim("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");

        $redirectUrl = str_replace('tcp:', 'http:', $socket->getAddress());
        $oauth2 = new OAuth2(
            [
                'clientId' => $clientId,
                'clientSecret' => $clientSecret,
                'authorizationUri' => self::AUTHORIZATION_URI,
                'redirectUri' => $redirectUrl . self::OAUTH2_CALLBACK_PATH,
                'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
                'scope' => self::SCOPE,
                // Create a 'state' token to prevent request forgery. See
                // https://developers.google.com/identity/protocols/OpenIDConnect#createxsrftoken
                // for details.
                'state' => sha1(openssl_random_pseudo_bytes(1024))
            ]
        );

        $authToken = null;

        $server = new Server(
            $loop,
            function (ServerRequestInterface $request) use ($oauth2, $loop, &$authToken) {
                // Stops the server after tokens are retrieved.
                if (!is_null($authToken)) {
                    $loop->stop();
                }

                // Check if the requested path is the one set as the redirect URI.
                if (
                    $request->getUri()->getPath()
                    !== parse_url($oauth2->getRedirectUri(), PHP_URL_PATH)
                ) {
                    return new Response(
                        404,
                        ['Content-Type' => 'text/plain'],
                        'Page not found'
                    );
                }

                // Exit if the state is invalid to prevent request forgery.
                $state = $request->getQueryParams()['state'];
                if (empty($state) || ($state !== $oauth2->getState())) {
                    throw new UnexpectedValueException(
                        "The state is empty or doesn't match expected one." . PHP_EOL
                    );
                };

                // Set the authorization code and fetch refresh and access tokens.
                $code = $request->getQueryParams()['code'];
                $oauth2->setCode($code);
                $authToken = $oauth2->fetchAuthToken();

                $refreshToken = $authToken['refresh_token'];
                print 'Your refresh token is: ' . $refreshToken . PHP_EOL;

                $propertiesToCopy = '[GOOGLE_ADS]' . PHP_EOL;
                $propertiesToCopy .= 'developerToken = "INSERT_DEVELOPER_TOKEN_HERE"' . PHP_EOL;
                $propertiesToCopy .=  <<<EOD
; Required for manager accounts only: Specify the login customer ID used to authenticate API calls.
; This will be the customer ID of the authenticated manager account. You can also specify this later
; in code if your application uses multiple manager account + OAuth pairs.
; loginCustomerId = "INSERT_LOGIN_CUSTOMER_ID_HERE"
EOD;
                $propertiesToCopy .= PHP_EOL . '[OAUTH2]' . PHP_EOL;
                $propertiesToCopy .= "clientId = \"{$oauth2->getClientId()}\"" . PHP_EOL;
                $propertiesToCopy .= "clientSecret = \"{$oauth2->getClientSecret()}\"" . PHP_EOL;
                $propertiesToCopy .= "refreshToken = \"$refreshToken\"" . PHP_EOL;

                print 'Copy the text below into a file named "google_ads_php.ini" in your home '
                    . 'directory, and replace "INSERT_DEVELOPER_TOKEN_HERE" with your developer '
                    . 'token:' . PHP_EOL;
                print PHP_EOL . $propertiesToCopy;

                return new Response(
                    200,
                    ['Content-Type' => 'text/plain'],
                    'Your refresh token has been fetched. Check the console output for '
                    . 'further instructions.'
                );
            }
        );

        $server->listen($socket);
        printf(
            'Log into the Google account you use for Google Ads and visit the following URL '
            . 'in your web browser: %1$s%2$s%1$s%1$s',
            PHP_EOL,
            $oauth2->buildFullAuthorizationUri(['access_type' => 'offline'])
        );

        $loop->run();
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