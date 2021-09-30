<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ReviewModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Expression;

class ReviewApi extends FrontendController
{
    private $data;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->data = json_decode(file_get_contents('php://input'), true);
    }

    public function createReview()
    {
        $this->data['user_id'] = (int)Xcart::app()->getUser()->user_id;
        $ip = Xcart::app()->request->getUserIP();
        $this->data['location'] = GeoIpHelper::getGeoipLocation($ip)->country;
        $review = new ReviewModel($this->data);
        $review->save();
        $this->jsonResponse($review->toArray());
    }

    /**
     * get all reviews for product by product id
     */
    public function getProductReviews()
    {
        $product_id = $this->data['product_id'];
        $reviews = ReviewModel::objects()->all(['product_id' => $product_id]);
        $rates = $this->getProductRates($product_id);
        $this->jsonResponse(
            [
                'reviews' => array_map(fn($review) => $review->toArray(), $reviews),
                'rates' => $rates,
            ]
        );
    }

    private function getProductRates($product_id): array
    {
        $result = ReviewModel::objects()
            ->select([
                'rate' => 'overall_rate',
                'rates_number' => new Expression("COUNT(product_review_id)")
            ])
            ->filter(['product_id' => $product_id])
            ->group(['overall_rate'])
            ->order('overall_rate')
            ->all();

        $overall_rates = [];

        for ($i = ReviewModel::MIN_RATE; $i <= ReviewModel::MAX_RATE; $i++) {
            $rate = [
                'rate' => $i,
                'rates_number' => 0,
            ];

            for ($j = 0; $j < count($result); $j++) {
                if ((int)$result[$j]->getFromQueryAttribute('rate') === $i) {
                    $rate['rates_number'] = (int)$result[$j]->getFromQueryAttribute('rates_number');
                    break;
                }
            }

            array_push($overall_rates, $rate);
        }

        return ['overall' => $overall_rates];
    }
}
