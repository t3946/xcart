<?php

namespace Modules\Reviews\Controllers\Api;

use Modules\Account\Controllers\AccountController;

use Modules\Reviews\Models\ProductReviewsModel;
use Modules\Reviews\Models\RatingsModel;
use Modules\Reviews\Models\ReviewRatingsModel;
use Modules\Reviews\Models\TotalProductRatingsModel;
use Modules\Reviews\Models\HelpfulReviewsModel;
use Modules\Reviews\Models\ReviewFileModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\TotalProductReviewsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Aggregation\Count;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ReviewsApi extends FrontendController
{
    private $data;
    public const SORT_TOP = 'top';
    public const SORT_HAS_ATTACHMENTS = 'has-attachments';
    public const SORT_NEW = 'most-recent';
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

    public function getTotalRatings(): array
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
        $this->jsonResponse($this->getTotalRatings());
    }

    public function createReview()
    {
        $review_data = [
            'header' => $_POST['header'],
            'body' => $_POST['body'],
            'product_id' => $_POST['productId'],
        ];
        $review_data['user_id'] = (int)Xcart::app()->getUser()->user_id;
        $ip = Xcart::app()->request->getUserIP();
        $review_data['location'] = GeoIpHelper::getGeoipLocation($ip)->country;
        $review = new ProductReviewsModel($review_data);
        $review->save();

        $review_id = $review->pk;
        $ratings = json_decode($_POST['ratings']);

        foreach ($ratings as $slug => $rating) {
            $this->createRating($review_id, $slug, $rating);
        }

        //save files
        for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
            $files = $_FILES['files'];

            $uploadedFile = new UploadedFile(
                $files['tmp_name'][$i],
                $files['name'][$i],
                $files['type'][$i],
                (int)$files['size'][$i],
                (int)$files['error'][$i],
            );

            (new ReviewFileModel(['review_id' => $review_id, 'image_path' => $uploadedFile]))->save();
        }

        $this->jsonResponse($review->getAttributes());
    }

    /**
     * get reviews for product by product id with sorting
     */
    public function getReviews($product_id, $limit, $offset, $sort): array
    {
        $overall_rating_id = RatingsModel::objects()->get(['slug' => 'overall'])['rating_id'];

        $qs = HelpfulReviewsModel::objects()->getQuerySet();
        $ratings_alias = $qs->getTableAlias();

        $qs = ReviewFileModel::objects()->getQuerySet();
        $files_alias = $qs->getTableAlias();

        $user_id = $this->getUser()->user_id;
        $select_fields = [
            '*',
            'helpful__user_id',
            'files__review_id',
            new Count('helpful__user_id', 'helpful_count'),
            'overall_rating' => 'rating__rating',
            'user_public_name' => 'user__public_name',
            'user_avatar' => 'user__avatar_image',
            'markedHelpful' => new Expression("IF($ratings_alias.user_id, true, false)"),
            'created_timestamp' => 'UNIX_TIMESTAMP(created)',
            'files' => new Expression("GROUP_CONCAT($files_alias.image_path)"),
        ];
        $filter_fields = [
            'product_id' => $product_id,
            new QOr(['rating__rating_id' => $overall_rating_id, 'rating__rating__isnull' => true]),
        ];

        // select user marked helpful if user authorised
        if ($user_id) {
            $select_fields['markedHelpful'] = new Expression("IF($ratings_alias.user_id, true, false)");
            $filter_fields[] = new QOr(['helpful__user_id' => $user_id, 'helpful__user_id__isnull' => true]);
        }

        $query_set = ProductReviewsModel::objects()
            ->select($select_fields)
            ->asArray()
            ->limit($limit)
            ->offset($offset)
            ->filter($filter_fields);

        switch ($sort) {
            case self::SORT_TOP:
                $qs = ProductReviewsModel::objects()->getQuerySet();
                $group = (new Expression("IFNULL({$qs->getTableAlias()}.product_review_id,UUID())"))->toSql();
                $query_set->group([$group]);
                $query_set = $query_set->order(['-markedHelpful']);
                break;

            case self::SORT_HAS_ATTACHMENTS:
                break;

            case self::SORT_NEW:
                $query_set = $query_set->order(['-created_timestamp']);
                $query_set->group(["product_review_id"]);
                break;
        }

        $reviews = $query_set->all();

        for ($i = 0; $i < count($reviews); $i++) {
            $reviews[$i]['markedHelpful'] = !($reviews[$i]['markedHelpful'] === "0");
        }

        return $reviews;
    }

    public function getReviewsAction()
    {
        $product_id = $this->data['productId'];
        $sort = $this->data['sort'] ?: self::SORT_DEFAULT;
        $offset = $this->data['offset'];
        $limit = $this->data['limit'];
        $this->jsonResponse($this->getReviews($product_id, $limit, $offset, $sort));
    }

    private function getTotalReviews()
    {
        $product_id = $this->data['productId'];
        return TotalProductReviewsModel::objects()->asArray()->get(['product_id' => $product_id])['total'];
    }

    public function getReviewsAndRatingsAction()
    {
        $product_id = $this->data['productId'];
        $sort = $this->data['sort'] || self::SORT_DEFAULT;
        $offset = $this->data['offset'];
        $limit = $this->data['limit'];

        $this->jsonResponse([
            'ratings' => $this->getTotalRatings(),
            'reviews' => $this->getReviews($product_id, $limit, $offset, $sort),
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
                    "value" => self::SORT_NEW,
                ],
            ],
            'product' => AccountController::getProduct($this->data['productId']),
        ]);
    }

    public function markHelpfulAction()
    {
        $user = $this->getUser();

        $data = [
            'review_id' => $this->data['reviewId'],
            'user_id' => $user->user_id,
        ];
        $object = HelpfulReviewsModel::objects()->get($data);

        if (!$object) {
            (new HelpfulReviewsModel([
                'review_id' => $this->data['reviewId'],
                'user_id' => $user->user_id,
            ]))->save();
        }

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
