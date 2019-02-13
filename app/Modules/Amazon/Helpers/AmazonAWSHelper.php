<?php

namespace Modules\Amazon\Helpers;


use Aws\CloudFront\CloudFrontClient;
use Aws\Credentials\Credentials;
use Aws\Sqs\SqsClient;
use Modules\Amazon\Stores\AmazonPoolStore;
use Modules\Sites\Models\SiteModel;

class AmazonAWSHelper
{
    private const CLOUD_FRONT_ACCESS_KEY_ID = 'AKIAJWXSBK5QXOE35Z2A';
    private const CLOUD_FRONT_SECRET_ACCESS_KEY = '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';
    private const SQS_ACCESS_KEY_ID = 'AKIAJVW2L6GDOMYLCK4Q';
    private const SQS_SECRET_ACCESS_KEY = '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';

    public const AMAZON_SQS_URL = 'https://sqs.us-west-2.amazonaws.com/945265545713/amazon_seller_queue';

    public static function invalidateCDN()
    {
        if ($credentials = new Credentials(self::CLOUD_FRONT_ACCESS_KEY_ID, self::CLOUD_FRONT_SECRET_ACCESS_KEY)) {

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

                        $inv = ['/static/frontend/*'];

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
    public static function createDestionstion($arguments = [])
    {
        $amzPool = new AmazonPoolStore();
        $client = $amzPool->getSubscriptionsClientPack();
        $result = $client->callRegisterDestination($arguments['url']);
    }

    public static function listDestionstions()
    {
        $amzPool = new AmazonPoolStore();
        $client = $amzPool->getSubscriptionsClientPack();
        return $client->callListDestinations();
    }

    public static function createSubscription($arguments = [])
    {
        $amzPool = new AmazonPoolStore();
        $client = $amzPool->getSubscriptionsClientPack();
        $result = $client->callCreateSubscription($arguments);
    }

    public static function initSQS()
    {
        if (!$list = self::listDestionstions()) {
            self::createDestionstion(['url' => AmazonAWSHelper::AMAZON_SQS_URL]);
        }

        self::createSubscription(['type' => 'AnyOfferChanged', 'url' => AmazonAWSHelper::AMAZON_SQS_URL]);
    }

    public static function getSQSMessages(): array
    {
        if ($credentials = new Credentials(self::SQS_ACCESS_KEY_ID, self::SQS_SECRET_ACCESS_KEY)) {
            $client = new SqsClient(['credentials' => $credentials, 'region' => 'us-west-2']);
            $response = $client->receiveMessage([
                'AttributeNames' => ['SentTimestamp'],
                'MaxNumberOfMessages' => 10,
                'MessageAttributeNames' => ['All'],
                'QueueUrl' => self::AMAZON_SQS_URL,
                'WaitTimeSeconds' => 1,
                'version' => 'latest'
            ]);
            foreach ($response->getPath('Messages/*/Body') as $messageBody) {
                // Do something with the message
                $result[] = json_decode(json_encode((array)simplexml_load_string($messageBody)),1);
            }

        }
        return $result ?? [];
    }
}