<?php

namespace Modules\Amazon\ClientPack;


use CaponicaAmazonMwsComplete\AmazonClient\MwsSubscriptionClient;
use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottleAwareClientPackInterface;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestManager;
use MWSSubscriptionsService_Model_DestinationList;
use MWSSubscriptionsService_Model_ListRegisteredDestinationsResponse;
use MWSSubscriptionsService_Model_ListRegisteredDestinationsResult;

class MwsSubscriptionsClient extends MwsSubscriptionClient implements ThrottleAwareClientPackInterface
{
    private const PARAM_MERCHANT = 'SellerId';
    private const PARAM_MARKETPLACE_ID = 'MarketplaceId';
    private const METHOD_CREATE_DESTINATION = 'registerDestination';
    private const METHOD_CREATE_SUBSCRIPTION = 'createSubscription';
    private const METHOD_LIST_DESTINATION = 'listRegisteredDestinations';

    private $throttleManager;
    private $marketplaceId;
    private $sellerId;

    public function __construct(MwsClientPoolConfig $poolConfig)
    {
        $this->marketplaceId = $poolConfig->getMarketplaceId();
        $this->sellerId = $poolConfig->getSellerId();

        $this->initThrottleManager();

        parent::__construct(
            $poolConfig->getAccessKey(),
            $poolConfig->getSecretKey(),
            $poolConfig->getApplicationName(),
            $poolConfig->getApplicationVersion(),
            $poolConfig->getConfigForFinance($this->getServiceUrlSuffix())
        );
    }

    private static function getDestinationData($url): array
    {
        return [
            'DeliveryChannel' => 'SQS',
            'AttributeList' => ['member' => [
                'Key' => 'sqsQueueUrl',
                'Value' => $url,
            ]
            ]
        ];
    }

    public function callRegisterDestination($url)
    {

        $destination = self::getDestinationData($url);

        $requestArray = [
            self::PARAM_MERCHANT => $this->sellerId,
            self::PARAM_MARKETPLACE_ID => $this->marketplaceId,
            'Destination' => $destination
        ];

        return CaponicaClientPack::throttledCall($this, self::METHOD_CREATE_DESTINATION, $requestArray);
    }

    public function callCreateSubscription($params = [])
    {

        $requestArray = [
            self::PARAM_MERCHANT => $this->sellerId,
            self::PARAM_MARKETPLACE_ID => $this->marketplaceId,
            'Subscription' => [
                'NotificationType' => $params['type'],
                'Destination' => self::getDestinationData($params['url']),
                'IsEnabled' => true,
            ]
        ];

        return CaponicaClientPack::throttledCall($this, self::METHOD_CREATE_SUBSCRIPTION, $requestArray);
    }

    public function callListDestinations(): array
    {

        $requestArray = [
            self::PARAM_MERCHANT => $this->sellerId,
            self::PARAM_MARKETPLACE_ID => $this->marketplaceId,
        ];

        /** @var MWSSubscriptionsService_Model_ListRegisteredDestinationsResponse $response */
        $response = CaponicaClientPack::throttledCall($this, self::METHOD_LIST_DESTINATION, $requestArray);

        /** @var MWSSubscriptionsService_Model_ListRegisteredDestinationsResult $result */
        $result = $response->getListRegisteredDestinationsResult();

        return $result->getDestinationList()->getmember();
    }

    private function getServiceUrlSuffix()
    {
        return '/Subscriptions/' . self::SERVICE_VERSION;
    }

    public function initThrottleManager()
    {
        $this->throttleManager = new ThrottledRequestManager(
            [
                self::METHOD_CREATE_DESTINATION => [25, 2],
                self::METHOD_CREATE_SUBSCRIPTION => [25, 2],
                self::METHOD_LIST_DESTINATION => [25, 2],
            ]
        );
    }

    public function getThrottleManager()
    {
        return $this->throttleManager;
    }
}