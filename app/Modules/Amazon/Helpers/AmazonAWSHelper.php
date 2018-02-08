<?php

namespace Modules\Amazon\Helpers;


use Aws\CloudFront\CloudFrontClient;
use Aws\Common\Credentials\Credentials;
use Modules\Sites\Models\SiteModel;

class AmazonAWSHelper
{
    public static function invalidateCDN()
    {
        if ($credentials = new Credentials('AKIAJWXSBK5QXOE35Z2A', 'CSzQ6FGstVeMndYILaHg4GFzgS9boC3Dq+8xUA2F')) {

            $client = CloudFrontClient::factory(['credentials' => $credentials]);

            if ($list = $client->listDistributions()) {
                foreach ($list->get('Items') as $dn) {

                    if (isset($dn['Origins']['Items'])) {
                        $domains = array_map(function ($a) {
                            return $a['DomainName'];
                        }, $dn['Origins']['Items']);
                    }

                    if ($domains && $sites = SiteModel::objects()->filter([
                            'domain__in' => $domains,
                            'config__name' => 'shop_closed',
                            'config__value' => 'N',
                        ])->count()) {

                        $inv = ['/skin1_kolin/*'];

                        $result = $client->createInvalidation([
                            'DistributionId' => $dn['Id'],
                            'Paths' => [
                                'Quantity' => count($inv),
                                'Items' => $inv,
                            ],

                            'CallerReference' => uniqid(),
                        ]);

                        echo nl2br("Invalidate CDN " . reset($domains) . PHP_EOL);
                    }

                }
            }
        }
    }
}