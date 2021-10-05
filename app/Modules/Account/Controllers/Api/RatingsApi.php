<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ProductReviewsModel;
use Modules\Account\Models\ReviewRatingsModel;
use Modules\Account\Models\TotalProductRatingsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\QueryBuilder\Expression;

class RatingsApi extends FrontendController
{
    private $data;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->data = json_decode(file_get_contents('php://input'), true);
    }

    public function getProductRatings()
    {
        $total_product_ratings = TotalProductRatingsModel::objects()->all(['product_id' => $this->data['productId']]);
        $ratings = array_map(function ($total_model) {
            $rating_model = $total_model->rating->getAttributes();

            $result = $total_model->getAttributes();
            $result['rating'] = $rating_model;

            if ($rating_model['slug'] === 'overall') {
                $rates = ProductReviewsModel::objects()
                    ->select([
                        'review__rating_id',
                        'review__rating',
                        'totalRates' => 'count(review_id)',
                    ])
                    ->filter([
                        'product_id' => $this->data['productId'],
                        'review__rating_id' => $rating_model['rating_id'],
                        'review__rating__isnull' => false,
                    ])
                    ->group(['review__rating', 'review__rating_id'])
                    ->asArray()
                    ->all();

                $result['rates'] = $rates;
            }

            return $result;
        }, $total_product_ratings);

        $overall_rating = null;
        $features_ratings = [];

        array_walk($ratings, function ($rating) use(&$overall_rating, &$features_ratings) {
            if ($rating['rating']['slug'] === 'overall') {
                $overall_rating = $rating;
            } else {
                $features_ratings[] = $rating;
            }
        });

        $this->jsonResponse([
            'overall' => $overall_rating,
            'features' => $features_ratings,
        ]);
    }
}
