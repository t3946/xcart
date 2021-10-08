<?php

namespace Modules\Goods\Controllers\Api;

use Modules\Account\Models\ProductReviewsModel;
use Modules\Account\Models\RatingsModel;
use Modules\Account\Models\ReviewRatingsModel;
use Modules\Account\Models\TotalProductRatingsModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\TotalProductReviewsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Q\QOr;

class ReviewsApi extends FrontendController
{
    private $data;
    private const SORT_TOP = 'top';
    private const SORT_HAS_ATTACHMENTS = 'has-attachments';
    private const SORT_MOST_RECENT = 'most-recent';
    private const SORT_DEFAULT = self::SORT_TOP;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->data = json_decode(file_get_contents('php://input'), true);
    }

    private function createRating($review_id, $rating_slug, $rating)
    {
        $rating_id = (int)RatingsModel::objects()->get(['slug' => $rating_slug])->getAttribute('rating_id');

        (new ReviewRatingsModel([
            'review_id' => $review_id,
            'rating_id' => $rating_id,
            'rating' => $rating,
        ]))->save();
    }

    public function getRatings()
    {
        $total_product_ratings = TotalProductRatingsModel::objects()->all(['product_id' => $this->data['productId']]);
        $ratings = array_map(function ($total_model) {
            $rating_model = $total_model->rating->getAttributes();

            $result = $total_model->getAttributes();
            $result['rating'] = $rating_model;

            if ($rating_model['slug'] === 'overall') {
                $rates = ProductReviewsModel::objects()
                    ->select([
                        'rating__rating_id',
                        'rating__rating',
                        'totalRates' => 'count(review_id)',
                    ])
                    ->filter([
                        'product_id' => $this->data['productId'],
                        'rating__rating_id' => $rating_model['rating_id'],
                        'rating__rating__isnull' => false,
                    ])
                    ->group(['rating__rating', 'rating__rating_id'])
                    ->asArray()
                    ->all();

                $result['rates'] = $rates;
            }

            return $result;
        }, $total_product_ratings);

        $overall_rating = null;
        $features_ratings = [];

        array_walk($ratings, function ($rating) use (&$overall_rating, &$features_ratings) {
            if ($rating['rating']['slug'] === 'overall') {
                $overall_rating = $rating;
            } else {
                $features_ratings[] = $rating;
            }
        });

        return [
            'overall' => $overall_rating,
            'features' => $features_ratings,
        ];
    }

    public function getRatingsAction()
    {
        $this->jsonResponse($this->getRatings());
    }

    public function createReview()
    {
        $review_data = [
            'header' => $this->data['header'],
            'body' => $this->data['body'],
            'product_id' => $this->data['product_id'],
        ];
        $review_data['user_id'] = (int)Xcart::app()->getUser()->user_id;
        $ip = Xcart::app()->request->getUserIP();
        $review_data['location'] = GeoIpHelper::getGeoipLocation($ip)->country;
        $review = new ProductReviewsModel($review_data);
        $review->save();

        $product_review_id = $review->getAttribute('product_review_id');

        foreach ($this->data['ratings'] as $slug => $rating) {
            $this->createRating($product_review_id, $slug, $rating);
        }

        $this->jsonResponse($review->toArray());
    }

    /**
     * get all reviews for product by product id
     */
    public function getReviews()
    {
        $product_id = $this->data['productId'];
        $sort = $this->data['sort'];
        $offset = $this->data['offset'];

        $overall_rating_id = RatingsModel::objects()->get(['slug' => 'overall'])['rating_id'];

//        $query_filter = ;
        $query_manager = ProductReviewsModel::objects()
            ->select([
                '*',
                'overall_rating' => 'rating__rating',
                'user_public_name' => 'user__public_name',
                'user_avatar' => 'user__avatar_image',
            ])
            ->filter([
                'product_id' => $product_id,
                new QOr(['rating__rating_id' => $overall_rating_id, 'rating__rating__isnull' => true]),
            ]);

        //select reviews and their overall rating
        return ProductReviewsModel::objects()
            ->select([
                '*',
                'overall_rating' => 'rating__rating',
                'user_public_name' => 'user__public_name',
                'user_avatar' => 'user__avatar_image',
            ])
            ->asArray()
            ->limit(3)
            ->offset(3)
            ->filter([
                'product_id' => $product_id,
                new QOr(['rating__rating_id' => $overall_rating_id, 'rating__rating__isnull' => true]),
            ])
            ->order(['-product_review_id'])
            ->all();
    }

    public function getReviewsAction()
    {
        $this->jsonResponse($this->getReviews());
    }

    private function getTotalReviews()
    {
        $product_id = $this->data['productId'];
        return TotalProductReviewsModel::objects()->asArray()->get(['product_id' => $product_id])['total'];
    }

    public function getReviewsAndRatingsAction()
    {
        $this->jsonResponse([
            'ratings' => $this->getRatings(),
            'reviews' => $this->getReviews(),
            'totalReviews' => $this->getTotalReviews(),
            'reviewsOrders' => [
                [
                    "name" => "Top reviews",
                    "value" => self::SORT_TOP,
                ],
                [
                    "name" => "Reviews with images",
                    "value" => self::SORT_HAS_ATTACHMENTS,
                ],
                [
                    "name" => "Most recent",
                    "value" => self::SORT_MOST_RECENT,
                ],
            ],
        ]);
    }
}
