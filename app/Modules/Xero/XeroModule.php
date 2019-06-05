<?php
namespace Modules\Xero;

use Xcart\App\Module\Module;

class XeroModule extends Module
{
    public function getConfig()
    {
        $xero_base_config = [
            'xero' => [
                // API versions can be overridden if necessary for some reason.
                //'core_version'     => '2.0',
                //'payroll_version'  => '1.0',
                //'file_version'     => '1.0'
            ],
            'oauth' => [
                'callback' => 'oob',
                'consumer_key' => 'SZ01EMJU43ETSV5ESTLCWFF6TS8FJC',
                'consumer_secret' => 'LU0DACQGIJJPV83HJWBODOND6EYVSV',
                //If you have issues passing the Authorization header, you can set it to append to the query string
                //'signature_location'    => \XeroPHP\Remote\OAuth\Client::SIGN_LOCATION_QUERY
                //For certs on disk or a string - allows anything that is valid with openssl_pkey_get_(private|public)
                'rsa_public_key' => <<<KEY
-----BEGIN CERTIFICATE-----
MIICoTCCAgqgAwIBAgIJALEIYu7c+hEhMA0GCSqGSIb3DQEBCwUAMGgxCzAJBgNV
BAYTAkNBMRAwDgYDVQQIDAdPbnRhcmlvMRAwDgYDVQQHDAdDaGF0aGFtMRIwEAYD
VQQKDAlTMyBTdG9yZXMxITAfBgkqhkiG9w0BCQEWEnhlcm8yQHMzc3RvcmVzLmNv
bTAeFw0xOTA2MDQxMjE2NTlaFw0yNDA2MDIxMjE2NTlaMGgxCzAJBgNVBAYTAkNB
MRAwDgYDVQQIDAdPbnRhcmlvMRAwDgYDVQQHDAdDaGF0aGFtMRIwEAYDVQQKDAlT
MyBTdG9yZXMxITAfBgkqhkiG9w0BCQEWEnhlcm8yQHMzc3RvcmVzLmNvbTCBnzAN
BgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEA3Zpe69ObnK8E/4Uqf+o6C1Elw+JPwqYA
NZ3TiDxhbZX08no6FMl6yJWNAZ9jmOf9nz8TCvweKqis7HEm7tTyqWdkx5ODu30V
TzeZIkTEvO5TBm97JFRQ9kPsw6IlY9p3ogjDaQEvip5VqEcZsIshC8RAB/O+CSBF
HqCf+uZEF6ECAwEAAaNTMFEwHQYDVR0OBBYEFCAG4oOerHi+VjSHtl9I1BOv4MyB
MB8GA1UdIwQYMBaAFCAG4oOerHi+VjSHtl9I1BOv4MyBMA8GA1UdEwEB/wQFMAMB
Af8wDQYJKoZIhvcNAQELBQADgYEAHq5ZD5UhmdRqhZKUVlLdTXVX+q8pslbMd6h1
T83IA+ocGllrlcfHNpkfLFggkn2FBjmqHamQ6ANQK7CU3ajWE3pTtLE5LNFvt8QR
czJ19L4ugCoXaVYJ9x4dPhuBeAkz92ZSOb/DVX9AOjJEUjoKz7MrvMnPTpJbC90M
FCjamFk=
-----END CERTIFICATE-----
KEY
,
                'rsa_private_key' => <<<KEY
-----BEGIN RSA PRIVATE KEY-----
MIICXwIBAAKBgQDdml7r05ucrwT/hSp/6joLUSXD4k/CpgA1ndOIPGFtlfTyejoU
yXrIlY0Bn2OY5/2fPxMK/B4qqKzscSbu1PKpZ2THk4O7fRVPN5kiRMS87lMGb3sk
VFD2Q+zDoiVj2neiCMNpAS+KnlWoRxmwiyELxEAH874JIEUeoJ/65kQXoQIDAQAB
AoGBANBYQsYVvUgihOB3ou2AmfHp6UexjqvCxo+iQy7jFfah9hnDMLg6dWYAtQHB
iTJJavo9ovnA79vLmkjyWVhSfZFnpvta31Ojevt2/uM0VqsiJGPwFO/KFBiLxC/U
PjHXGATk12sXaI3CGWcD63NeyuHJk2GVH5GXoc6TqdzT0R3BAkEA9yqZeoWtt/7O
CWrNYBDcmV6at867HZiHkoKtiL//68z+ecll8T68e6D7ZMLEe2JVofNaT1g2s1If
lcSHucOfwwJBAOWF4k6YJuLf/5wn+VeZWr6WZepbpJTEpzLKbb8QJH3yyEQ0xvj9
vyJX+DZ8+5MK9RGGqmp9/c39ikmTYzqreMsCQQDKSG77u1QFdeAiDOqwaJxiWO6+
585z1lV+LrzfYmONFGRgP9fSrMmNVJ7qRAyR/lSfCo8qyMNb1yZKaGG8QsIJAkEA
2cbR9q7JXsUY/Cq1nBdnEaUUaGyx2XJKDpghz4LajSKaQJUvIbtp6oU7fz/RWDCy
XQHAmrCW7CegWbVeFqHyRwJBAPBkXSS0ZY253N0j+nO/znW7ZbnjjbOoH8h0zP/8
5lEXDx4FE9ugvo0zfT6Gu00g+dGHbrqd8gzh+Ca6lR8tUQs=
-----END RSA PRIVATE KEY-----
KEY
,
            ],
            //These are raw curl options.  I didn't see the need to obfuscate these through methods
            'curl' => [
                CURLOPT_USERAGENT => 'Xcart Connector App',
                //Only for partner apps - unfortunately need to be files on disk only.
                //CURLOPT_CAINFO          => 'certs/ca-bundle.crt',
                //CURLOPT_SSLCERT         => 'certs/entrust-cert-RQ3.pem',
                //CURLOPT_SSLKEYPASSWD    => '1234',
                //CURLOPT_SSLKEY          => 'certs/entrust-private-RQ3.pem'
            ],
        ];
        return $xero_base_config;
    }
}