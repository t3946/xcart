<?php

namespace Modules\Amazon\ClientPack;

use CaponicaAmazonMwsComplete\ClientPack\CaponicaClientPack;
use CaponicaAmazonMwsComplete\ClientPack\MwsFeedAndReportClientPack;
use CaponicaAmazonMwsComplete\ClientPool\MwsClientPoolConfig;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottleAwareClientPackInterface;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestLogCollection;
use CaponicaAmazonMwsComplete\Domain\Throttle\ThrottledRequestManager;

class MwsFeedAndReportClient extends MwsFeedAndReportClientPack implements ThrottleAwareClientPackInterface
{
    public const  METHOD_SUBMIT_FEED   = 'submitFeed';
    public const METHOD_REQUEST_REPORT   = 'RequestReport';
    private $throttleManager;

    public function __construct(MwsClientPoolConfig $poolConfig) {
        parent::__construct($poolConfig);

        $this->initThrottleManager();
    }

    private function getServiceUrlSuffix() {
        return '/';
    }

    public function callSubmitFeed($feedType, $feedContent) {
        $contentHash = base64_encode(md5(stream_get_contents($feedContent), true));
        rewind($feedContent);

        $parameters = [
            self::PARAM_FEED_CONTENT        => $feedContent,
            self::PARAM_FEED_CONTENT_MD5    => $contentHash,
            self::PARAM_FEED_TYPE           => $feedType,
            self::PARAM_MARKETPLACE_ID_LIST => array('Id' => $this->marketplaceId),
            self::PARAM_MERCHANT            => $this->sellerId,
        ];
        return AmazonClientPack::throttledCall($this, self::METHOD_SUBMIT_FEED, $parameters, 1);
    }

    public function callReqReport($reportType, $startDate = null, $endDate = null):\MarketplaceWebService_Model_RequestReportResponse {

        $parameters = [
            self::PARAM_MARKETPLACE         => $this->marketplaceId,
            self::PARAM_REPORT_TYPE         => $reportType,
            self::PARAM_MARKETPLACE_ID_LIST => array('Id' => $this->marketplaceId),
            self::PARAM_MERCHANT            => $this->sellerId,
        ];
        if (!empty($startDate)) {
            $parameters[self::PARAM_START_DATE] = $startDate;
        }
        if (!empty($endDate)) {
            $parameters[self::PARAM_END_DATE] = $endDate;
        }
        return AmazonClientPack::throttledCall($this, self::METHOD_REQUEST_REPORT, $parameters, 1);
    }

    public function initThrottleManager() {
        $this->throttleManager = new ThrottledRequestManager(
            [
                self::METHOD_SUBMIT_FEED   => [15, 0.008, ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
                self::METHOD_REQUEST_REPORT   => [15, 0.008, ThrottledRequestLogCollection::RESTORE_BASIS_WEIGHT],
            ]
        );
    }

    public function getThrottleManager() {
        return $this->throttleManager;
    }
}