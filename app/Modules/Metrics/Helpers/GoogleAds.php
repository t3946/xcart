<?php

namespace Modules\Metrics\Helpers;

use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class GoogleAds
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

    public const API_VERSION = 8;
    public string $customer_id;
    public string $manager_id;
    private string $refresh_token = '1//0cj2_yHjt34KVCgYIARAAGAwSNwF-L9IrY6Fx8AN3mkHGW4jS1RCSQJC1uo3Rrf4FngePP2Xx2kD8lGI2NDQ-rKlBSU9tUQkME8Y';
    private string $developer_token = 'wtOWB16hC0UlGT2NQcge4w';
    private string $client_id = '193950091514-p4980nf62bu5phb6rsiktj1utn5dimoj.apps.googleusercontent.com';
    private string $client_secret = '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';
    private Client $client;
    private string $authorization_token;

    public function __construct(string $customer_id, string $manager_id)
    {
        $this->customer_id = $customer_id;
        $this->manager_id = $manager_id;
        $this->client = new Client(['verify' => false, 'timeout' => 30]);
        $this->getAuthorizationToken();
    }

    public function getAllCampaigns()
    {
        $query = "SELECT campaign.name, campaign_budget.amount_micros, campaign.status, campaign.optimization_score, " .
            "campaign.advertising_channel_type, metrics.clicks, metrics.impressions, metrics.ctr, metrics.average_cpc, " .
            "metrics.cost_micros, campaign.bidding_strategy_type FROM campaign";
        $result_query = $this->querySearch($query);
    }

    public function getBudgetIdByCampaignId(int $campaign_id)
    {
        $query = "SELECT campaign_budget.id FROM campaign WHERE campaign.id = $campaign_id";
        $campaign_list = $this->querySearch($query);
        foreach ($campaign_list as $campaign) {
            if (!empty($campaign['campaignBudget'])) {
                return (int)$campaign['campaignBudget']['id'];
            }
        }
    }

    private function querySearch(string $query): array
    {
        $api_version = self::API_VERSION;
        $header = $this->getHeaders();
        $result = $this->query("https://googleads.googleapis.com/v$api_version/customers/{$this->customer_id}/googleAds:search", [
            'query' => ['query' => $query],
            'headers' => $header
        ]);
        return $result['results'] ?? [];
    }

    private function query(string $url, array $params): array
    {
        if ($response = $this->client->request('POST', $url, $params)) {
            if ($result = json_decode($response->getBody(), true)) {
                return $result;
            }
        }
        return [];
    }

    public function updateCampaignBudgetById(int $id_campaign, array $field_update)
    {
        $api_version = self::API_VERSION;
        $header = $this->getHeaders();
        $url = "https://googleads.googleapis.com/v{$api_version}/customers/{$this->customer_id}/campaignBudgets:mutate";

        $fields = array_keys($field_update);
        $result = $this->query($url, [
            "body" => json_encode([
                'operations' => [
                    [
                        'update' => array_merge([
                            'resourceName' => "customers/$this->customer_id/campaignBudgets/$id_campaign",
                        ], $field_update),
                        'updateMask' => implode(',', $fields),
                    ]
                ]
            ], JSON_UNESCAPED_SLASHES),
            'headers' => $header
        ]);

    }

    private function getHeaders(): array
    {
        return [
            "Content-Type" => "application/json",
            "developer-token" => $this->developer_token,
            "login-customer-id" => $this->manager_id,
            "Authorization" => "Bearer $this->authorization_token"
        ];
    }

    private function getAuthorizationToken()
    {
        if ($response = $this->client->request("POST", "https://www.googleapis.com/oauth2/v3/token", [
            "query" => [
                "client_id" => $this->client_id,
                "client_secret" => $this->client_secret,
                "refresh_token" => $this->refresh_token,
                "grant_type" => "refresh_token"
            ]
        ])) {
            $data = json_decode($response->getBody(), true);
            if (!empty($data['access_token'])) {
                $this->authorization_token = $data['access_token'];
            }
        }
    }

    public static function generateRefreshToken()
    {
        try {
            $clientId = trim("193950091514-p4980nf62bu5phb6rsiktj1utn5dimoj.apps.googleusercontent.com");
            $clientSecret = trim("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");

            /*            $redirectUrl = str_replace('tcp:', 'http:', str_replace('127.0.0.1', 'localhost', $socket->getAddress()));*/
            $redirectUrl = 'http://localhost';
            /*            $redirectUrl = str_replace('tcp:', 'http:', $socket->getAddress());*/
            $oauth2 = new OAuth2(
                [
                    'clientId' => $clientId,
                    'clientSecret' => $clientSecret,
                    'authorizationUri' => self::AUTHORIZATION_URI,
                    'redirectUri' => $redirectUrl . self::OAUTH2_CALLBACK_PATH,
                    'tokenCredentialUri' => CredentialsLoader::TOKEN_CREDENTIAL_URI,
                    'scope' => self::SCOPE,
                    'state' => sha1(openssl_random_pseudo_bytes(1024))
                ]
            );

            printf(
                'Log into the Google account you use for Google Ads and visit the following URL '
                . 'in your web browser: %1$s%2$s%1$s%1$s',
                PHP_EOL,
                $oauth2->buildFullAuthorizationUri(['access_type' => 'offline'])
            );
        } catch (\Throwable $exception) {
            printf($exception->getMessage());
        }
    }
}