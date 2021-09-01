<?php

namespace Modules\Amazon\ClientPack;


use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottleAwareClientPackInterface;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestManager;
use MWSRecommendationsSectionService_Model_ListRecommendationsResponse;

class MwsRecommendationClient extends \CaponicaAmazonMwsComplete\AmazonClient\MwsRecommendationClient implements ThrottleAwareClientPackInterface
{
    private const METHOD_LIST_RECOMMENDATIONS       = 'listRecommendations';
    private const PARAM_MERCHANT                    = 'SellerId';
    private const PARAM_MARKETPLACE_ID              = 'MarketplaceId';
    private const PARAM_RECOMMENDATION_CATEGORY      = 'RecommendationCategory';


    private $throttleManager;
    private $marketplaceId;
    private $sellerId;

    public function __construct(MwsClientPoolConfig $poolConfig) {
        $this->marketplaceId    = $poolConfig->getMarketplaceId();
        $this->sellerId         = $poolConfig->getSellerId();

        $this->initThrottleManager();

        parent::__construct(
            $poolConfig->getAccessKey(),
            $poolConfig->getSecretKey(),
            $poolConfig->getApplicationName(),
            $poolConfig->getApplicationVersion(),
            $poolConfig->getConfigForFinance($this->getServiceUrlSuffix())
        );
    }

    public function callGetListRecommendations(): MWSRecommendationsSectionService_Model_ListRecommendationsResponse {

        $requestArray = [
            self::PARAM_MERCHANT            => $this->sellerId,
            self::PARAM_MARKETPLACE_ID      => $this->marketplaceId,
            self::PARAM_RECOMMENDATION_CATEGORY => 'Selection',
        ];

        return CaponicaClientPack::throttledCall($this, self::METHOD_LIST_RECOMMENDATIONS, $requestArray);
    }

    private function getServiceUrlSuffix() {
        return '/Recommendations/' . self::SERVICE_VERSION;
    }

    public function initThrottleManager()
    {
        $this->throttleManager = new ThrottledRequestManager(
            [
                self::METHOD_LIST_RECOMMENDATIONS => [8, 2],
            ]
        );
    }

    public function getThrottleManager()
    {
        return $this->throttleManager;
    }
}