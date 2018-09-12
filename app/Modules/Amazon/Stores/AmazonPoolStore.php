<?php

namespace Modules\Amazon\Stores;

use CaponicaAmazonMwsComplete\ClientPool\MwsClientPool;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use Modules\Amazon\ClientPack\MwsFbaInventoryClient;
use Modules\Amazon\ClientPack\MwsFbaOutboundClient;
use Modules\Amazon\ClientPack\MwsFeedAndReportClient;
use Modules\Amazon\ClientPack\MwsProductClientPackExt;
use Modules\Amazon\ClientPack\MwsRecommendationClient;

class AmazonPoolStore extends MwsClientPool
{
    const AWS_ACCESS_KEY_ID = 'AKIAJFLBZ4Y7BVG5Q22A';
    const AWS_SECRET_ACCESS_KEY = '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';
    const APPLICATION_NAME = 's3stores';
    const APPLICATION_VERSION = '1';
    const MERCHANT_ID = 'A2SWKX6V1OVQ89';
    const MARKETPLACE_ID = 'ATVPDKIKX0DER';

    protected $fbaOutboundClientPack;
    protected $fbaInventoryClientPack;
    protected $recommendationClientPack;

    public function __construct()
    {
        $this->setConfig([
            MwsClientPoolConfig::PARAM_ACCESS_KEY => self::AWS_ACCESS_KEY_ID,
            MwsClientPoolConfig::PARAM_SECRET_KEY => self::AWS_SECRET_ACCESS_KEY,
            MwsClientPoolConfig::PARAM_SELLER_ID => self::MERCHANT_ID,
            MwsClientPoolConfig::PARAM_APP_NAME => self::APPLICATION_NAME,
            MwsClientPoolConfig::PARAM_APP_VERSION => self::APPLICATION_VERSION,
            MwsClientPoolConfig::PARAM_AMAZON_SITE => MwsClientPoolConfig::SITE_USA,
        ]);
    }

    public function getFbaOutboundClientPack()
    {
        if (empty($this->fbaOutboundClientPack)) {
            $this->fbaOutboundClientPack = new MwsFbaOutboundClient($this->config);
        }
        return $this->fbaOutboundClientPack;
    }

    public function getFbaInventoryClientPack()
    {
        if (empty($this->fbaInventoryClientPack)) {
            $this->fbaInventoryClientPack = new MwsFbaInventoryClient($this->config);
        }
        return $this->fbaInventoryClientPack;
    }

    public function getProductClientPackExt()
    {
        if(empty($this->productClientPack)) {
            $this->productClientPack = new MwsProductClientPackExt($this->config);
        }
        return $this->productClientPack;
    }

    public function getFeedAndReportClientPack()
    {
        if(empty($this->feedAndReportClientPack)) {
            $this->feedAndReportClientPack = new MwsFeedAndReportClient($this->config);
        }
        return $this->feedAndReportClientPack;
    }

    public function getRecommendationClientPack()
    {
        if($this->recommendationClientPack === null) {
            $this->recommendationClientPack = new MwsRecommendationClient($this->config);
        }
        return $this->recommendationClientPack;
    }
}