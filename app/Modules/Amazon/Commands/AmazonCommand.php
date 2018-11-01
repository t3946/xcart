<?php

namespace Modules\Amazon\Commands;


use Modules\Amazon\Helpers\AmazonAWSHelper;
use Modules\Amazon\Helpers\AmazonOfferHelper;
use Modules\Amazon\Models\AmazonOfferCompetitorsModel;
use Modules\Amazon\Models\AmazonOfferModel;
use Xcart\App\Commands\Command;

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

                            $listing->save();

                            $listing->competitors->delete();

                            $offers = isset($anyOfferChangedNotification['Offers']['Offer']['SellerId'])
                                ? [$anyOfferChangedNotification['Offers']['Offer']] : $anyOfferChangedNotification['Offers']['Offer'];

                            foreach ($offers as $off) {
                                if ($off['SubCondition'] === 'new') {
                                    $listing->offers++;
                                    /** @var AmazonOfferCompetitorsModel $offer */
                                    [$offer, $is_cmp_new] = AmazonOfferCompetitorsModel::objects()->getOrNew(['seller' => $off['SellerId'], 'offer_id' => $listing->id]);

                                    $offer->setAttributes([
                                        'rating' => $off['SellerFeedbackRating']['SellerPositiveFeedbackRating'],
                                        'LandingPrice' => (float)$off['ListingPrice']['Amount'] + (float)$off['Shipping']['Amount'],
                                        'ListingPrice' => (float)$off['ListingPrice']['Amount'],
                                        'Shipping' => (float)$off['Shipping']['Amount'],
                                        'country' => $off['ShipsFrom']['Country'] ?: '',
                                        'state' => $off['ShipsFrom']['State'] ?: '',
                                        'channel' => $off['IsFulfilledByAmazon'] === 'true' ? 'FBA' : 'MFN',
                                        'is_buybox' => $off['IsBuyBoxWinner'] === 'true'
                                    ]);
                                    $offer->save();

                                    if (AmazonOfferHelper::OUR_MERCHANT_ID === $off['SellerId']) {
                                        $listing->myPrice = $offer->LandingPrice;
                                        $listing->is_buybox_my = $offer->is_buybox;
                                    }

                                    if ($offer->is_buybox) {
                                        $listing->buybox_Channel = $offer->channel;
                                    }
                                }
                            }
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