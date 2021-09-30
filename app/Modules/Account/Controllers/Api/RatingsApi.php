<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ReviewModel;
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
        $result = ReviewModel::objects()
            ->select([
                'rating' => 'overall_rating',
                'ratings_number' => new Expression("COUNT(product_review_id)")
            ])
            ->filter(['product_id' => $this->data['product_id']])
            ->group(['overall_rating'])
            ->order('overall_rating')
            ->all();

        $overall_rates = [];

        for ($i = ReviewModel::MIN_RATING; $i <= ReviewModel::MAX_RATING; $i++) {
            $rate = [
                'rating' => $i,
                'ratingsNumber' => 0,
            ];

            for ($j = 0; $j < count($result); $j++) {
                if ((int)$result[$j]->getFromQueryAttribute('rating') === $i) {
                    $rate['ratingsNumber'] = (int)$result[$j]->getFromQueryAttribute('ratings_number');
                    break;
                }
            }

            array_push($overall_rates, $rate);
        }

        $this->jsonResponse([
            'overallRatings' => $overall_rates,
            'minRating' => ReviewModel::MIN_RATING,
            'maxRating' => ReviewModel::MAX_RATING
        ]);
    }
}
