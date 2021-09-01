<?php

namespace Modules\Amazon\ClientPack;

use CaponicaAmazonMwsComplete\AmazonClient\FbaOutboundClient;
use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottleAwareClientPackInterface;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestManager;

class MwsFbaOutboundClient extends FbaOutboundClient implements ThrottleAwareClientPackInterface
{
    const PARAM_MERCHANT                    = 'SellerId';
    const PARAM_MARKETPLACE_ID              = 'MarketplaceId';
    const PARAM_AMAZON_ORDER_IDS            = 'SellerFulfillmentOrderId';

    const METHOD_GET_FBA_ORDER              = 'getFulfillmentOrder';

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
            $poolConfig->getConfigForOrder($this->getServiceUrlSuffix()),
            $poolConfig->getApplicationName(),
            $poolConfig->getApplicationVersion()
        );
    }

    public function callGetFulfillmentOrder($amazonOrderIds) {

        $requestArray = [
            self::PARAM_MERCHANT            => $this->sellerId,
            self::PARAM_MARKETPLACE_ID      => $this->marketplaceId,
            self::PARAM_AMAZON_ORDER_IDS    => $amazonOrderIds,
        ];

        return CaponicaClientPack::throttledCall($this, self::METHOD_GET_FBA_ORDER, $requestArray);
    }

    private function getServiceUrlSuffix() {
        return '/FulfillmentOutboundShipment/' . self::SERVICE_VERSION;
    }

    public function initThrottleManager()
    {
        $this->throttleManager = new ThrottledRequestManager(
            [
                self::METHOD_GET_FBA_ORDER => [30, 2],
            ]
        );
    }

    public function getThrottleManager()
    {
        return $this->throttleManager;
    }
}