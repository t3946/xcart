<?php
namespace Modules\Metrics\Controllers;
use Google\Auth\CredentialsLoader;
use Google\Auth\OAuth2;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class MetricsController extends Controller
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


    public function generateRefresh()
    {
        $clientId = trim("193950091514-p4980nf62bu5phb6rsiktj1utn5dimoj.apps.googleusercontent.com");
        $clientSecret = trim("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");
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
                'state' => Xcart::app()->request->get->get('state')
            ]
        );

        $code = Xcart::app()->request->get->get('code');
        $oauth2->setCode($code);
        $authToken = $oauth2->fetchAuthToken();

        $refreshToken = $authToken['refresh_token'];

    }
}