<?php

namespace Modules\Amazon\Commands;


use Mindy\QueryBuilder\Q\QOrNot;
use Modules\Amazon\Helpers\AmazonAWSHelper;
use Modules\Amazon\Helpers\AmazonOfferHelper;
use Modules\Amazon\Models\AmazonOfferCompetitorsModel;
use Modules\Amazon\Models\AmazonOfferModel;
use Xcart\App\Commands\Command;
use Xcart\Connection;

class AmazonCommand extends Command
{

    public function handle($arguments = [])
    {

        func_backprocess_log('amazon_sqs_feed', $log = "Start processing SQS\n");
        echo $log;

        $cnt = 0;

        while ($messages = AmazonAWSHelper::getSQSMessages()) {
            foreach ($messages as $message) {
                $message_data = $message['NotificationMetaData'];

                $anyOfferChangedNotification = $message['NotificationPayload']['AnyOfferChangedNotification'];

                if ($message_data['NotificationType'] === 'AnyOfferChanged' && $anyOfferChangedNotification) {

                    $offer_info = $anyOfferChangedNotification['OfferChangeTrigger'];
                    $offer_summary = $anyOfferChangedNotification['Summary'];
                    $offer_change_time = \DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $offer_info['TimeOfOfferChange'], new \DateTimeZone( 'UTC' ));
                    $offer_change_time->setTimezone(new \DateTimeZone('EST'));

                    if ($offer_info['ItemCondition'] === 'new') {

                        /** @var AmazonOfferModel $listing */
                        [$listing, $is_new] = AmazonOfferModel::objects()->getOrNew(['ASIN' => $offer_info['ASIN']]);

                        if ($is_new || $listing->offer_change_time < $offer_change_time) {
                            $lowest = AmazonOfferHelper::getLowestPrice($offer_summary['LowestPrices']['LowestPrice'] ?? []);
                            $buy_box = AmazonOfferHelper::getLowestPrice($offer_summary['BuyBoxPrices']['BuyBoxPrice'] ?? []);
                            $sales_rank = AmazonOfferHelper::getSalesRank($offer_summary['SalesRankings']['SalesRank'] ?? []);
                            $listing->setAttributes([
                                'offer_change_time' => $offer_change_time,
                                'lowest_LandedPrice' => $lowest['LandedPrice'],
                                'lowest_ListingPrice' => $lowest['ListingPrice'],
                                'lowest_Shipping' => $lowest['Shipping'],
                                'lowest_Channel' => $lowest['fulfillmentChannel'],
                                'buybox_LandedPrice' => $buy_box['LandedPrice'],
                                'buybox_ListingPrice' => $buy_box['ListingPrice'],
                                'buybox_Shipping' => $buy_box['Shipping'],
                                'buybox_Channel' => $buy_box['fulfillmentChannel'],
                                'sales_rank' => $sales_rank,
                                'offers' => 0
                            ]);

                            if ($is_new) {
                                $listing->save();
                            }



                            $offers = isset($anyOfferChangedNotification['Offers']['Offer']['SellerId'])
                                ? [$anyOfferChangedNotification['Offers']['Offer']] : $anyOfferChangedNotification['Offers']['Offer'];

                            $max_offers_price = null;
                            $found_me = false;
                            $p_sellers = [];

                            foreach ($offers as $off) {
                                if ($off['SubCondition'] === 'new') {
                                    $listing->offers++;

                                    $p_sellers[] = $off['SellerId'];

                                    $query = 'call f_amazonInsertOfferCompetitor(:offer_id, :seller, :rating, :LandingPrice, :ListingPrice, :Shipping, :channel, :is_buybox, :country, :state)';
                                    $params = [
                                        'seller' => $off['SellerId'],
                                        'offer_id' => $listing->id,
                                        'channel' => $off['IsFulfilledByAmazon'] === 'true' ? 'FBA' : 'MFN',
                                        'rating' => $off['SellerFeedbackRating']['SellerPositiveFeedbackRating'],
                                        'LandingPrice' => (float)$off['ListingPrice']['Amount'] + (float)$off['Shipping']['Amount'],
                                        'ListingPrice' => (float)$off['ListingPrice']['Amount'],
                                        'Shipping' => (float)$off['Shipping']['Amount'],
                                        'country' => $off['ShipsFrom']['Country'] ?: null,
                                        'state' => $off['ShipsFrom']['State'] ?: null,
                                        'is_buybox' => (int) ($off['IsBuyBoxWinner'] === 'true')
                                    ];
                                    Connection::getInstance()->executeQuery($query, $params);

                                    /** @var AmazonOfferCompetitorsModel $offer */
                                    if ($offer = AmazonOfferCompetitorsModel::objects()->get([
                                        'seller' => $off['SellerId'],
                                        'offer_id' => $listing->id,
                                        'channel' => $off['IsFulfilledByAmazon'] === 'true' ? 'FBA' : 'MFN',
                                    ])) {
                                        if (AmazonOfferHelper::OUR_MERCHANT_ID === $off['SellerId']) {
                                            $listing->myPrice = $offer->LandingPrice;
                                            $listing->is_buybox_my = $offer->is_buybox;
                                            $found_me = true;
                                        }

                                        if ($offer->is_buybox) {
                                            $listing->buybox_Channel = $offer->channel;
                                        }

                                        $max_offers_price = $offer->LandingPrice;
                                    }
                                }
                            }

                            if ($offer && !$found_me && $max_offers_price) {
                                $listing->myPrice = $max_offers_price + 0.01;
                            }

                            if ($p_sellers) {
                                $f = [new QOrNot(['seller__in' => $p_sellers])];
                            }
                            $listing->competitors->delete($f ?? []);

                            $listing->save();
                        }
                    }
                }
                $cnt ++;
                if ($cnt % 1000 === 0) {
                    func_backprocess_log('amazon_sqs_feed', $log = "Processed {$cnt} messages\n");
                    echo $log;
                }
            }
        }

        func_backprocess_log('amazon_sqs_feed', $log = "SQS processing complete. ({$cnt} messages)\n");
        echo $log;
    }
}