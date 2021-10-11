<?php

namespace Modules\Reviews\Controllers\Api;

use Modules\Reviews\Models\ProductReviewsModel;
use Modules\Reviews\Models\RatingsModel;
use Modules\Reviews\Models\ReviewRatingsModel;
use Modules\Reviews\Models\TotalProductRatingsModel;
use Modules\Reviews\Models\HelpfulReviewsModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\TotalProductReviewsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Aggregation\Count;
use Xcart\App\QueryBuilder\Expression;
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
                    ->group(['rating__rating', 'rating__rating_id', 'user_id'])
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
     * get reviews for product by product id with sorting
     */
    public function getReviews(): array
    {
        $product_id = $this->data['productId'];
        $sort = $this->data['sort'] || self::SORT_DEFAULT;
        $offset = $this->data['offset'] ?: 0;
        $limit = $this->data['limit'] ?: 10;
        $overall_rating_id = RatingsModel::objects()->get(['slug' => 'overall'])['rating_id'];

        $query_set = ProductReviewsModel::objects()
            ->select([
                '*',
                'helpful__user_id',
                new Count('helpful__user_id', 'helpful_count'),
                'overall_rating' => 'rating__rating',
                'user_public_name' => 'user__public_name',
                'user_avatar' => 'user__avatar_image',
            ])
            ->asArray()
            ->limit($limit)
            ->offset($offset)
            ->filter([
                'product_id' => $product_id,
                new QOr(['rating__rating_id' => $overall_rating_id, 'rating__rating__isnull' => true]),
            ]);

        switch ($sort) {
            case self::SORT_TOP:
                $qs = ProductReviewsModel::objects()->getQuerySet();
                $group = (new Expression("IFNULL({$qs->getTableAlias()}.product_review_id,UUID())"))->toSql();
                $query_set->group([$group]);
                break;

            case self::SORT_HAS_ATTACHMENTS:
                break;

            case self::SORT_MOST_RECENT:
                $query_set = $query_set->order(['-product_review_id']);
                break;
        }

        return $query_set->all();
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

    public function markHelpfulAction()
    {
        $user = $this->getUser();

        (new HelpfulReviewsModel([
            'review_id' => $this->data['reviewId'],
            'user_id' => $user->user_id,
        ]))->save();

        $this->jsonResponse(["result" => "ok"]);
    }

    public function unmarkHelpfulAction()
    {
        $user = $this->getUser();

        HelpfulReviewsModel::objects()->delete([
            'review_id' => $this->data['reviewId'],
            'user_id' => (int)$user->user_id,
        ]);

        $this->jsonResponse(["result" => "ok"]);
    }
}
