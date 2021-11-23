<?php

namespace Modules\Reviews\Controllers\Api;

use Modules\Account\Controllers\AccountController;

use Modules\Core\Models\CountryModel;
use Modules\Media\Models\VideosModel;
use Modules\Reviews\Models\ProductReviewsModel;
use Modules\Reviews\Models\RatingsModel;
use Modules\Reviews\Models\ReviewRatingsModel;
use Modules\Reviews\Models\ReviewsImagesModel;
use Modules\Reviews\Models\ReviewsVideosModel;
use Modules\Reviews\Models\TotalProductRatingsModel;
use Modules\Reviews\Models\HelpfulReviewsModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Modules\Goods\Models\TotalProductReviewsModel;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;
use Xcart\App\QueryBuilder\Aggregation\Count;
use Xcart\App\QueryBuilder\Expression;
use Xcart\App\QueryBuilder\Q\QOr;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xcart\App\Storage\Files\RemoteFile;

class ReviewsApi extends FrontendController
{
    private $data;
    public const SORT_TOP = 'top';
    public const SORT_HAS_IMAGES = 'has-images';
    public const SORT_HAS_VIDEOS = 'has-videos';
    public const SORT_NEW = 'most-recent';
    private const SORT_DEFAULT = self::SORT_TOP;
    private const SUPPORTED_IMAGE_FORMATS = ['jpg', 'jpeg', 'png'];
    private const SUPPORTED_VIDEO_FORMATS = ['mp4'];

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
                        'rating__rating__gt' => 0,
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
        $location = GeoIpHelper::getGeoipLocation($ip)->country;
        $default_location = 'US';
        $review_data['location'] = $location ?: $default_location;

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
            $extension = pathinfo($files['name'][$i])['extension'];

            //is not supported file
            if (!in_array($extension, self::SUPPORTED_IMAGE_FORMATS) &&
                !in_array($extension, self::SUPPORTED_VIDEO_FORMATS)) {
                continue;
            }

            $uploaded_file = new UploadedFile(
                $files['tmp_name'][$i],
                $files['name'][$i],
                $files['type'][$i],
                (int)$files['size'][$i],
                (int)$files['error'][$i],
            );
            $image_name = $uploaded_file->getPath() . '/' . $uploaded_file->getFilename();

            //is image
            if (in_array($extension, self::SUPPORTED_IMAGE_FORMATS)) {
                list($width, $height) = getimagesize($image_name);

                (new ReviewsImagesModel())->saveImage($review_id, [
                    'path' => $uploaded_file,
                    'width' => $width,
                    'height' => $height,
                ]);
            }

            //is video
            if (in_array($extension, self::SUPPORTED_VIDEO_FORMATS)) {
                (new ReviewsVideosModel())->saveVideo($review_id, [
                    'video' => $uploaded_file,
                    'provider' => 'local',
                    'name' => pathinfo($files['name'][$i])['filename'],
                ]);
            }
        }

        $video_file_url = $_POST['videoLink'];

        if ($video_file_url) {
            $errors = $this->checkVideoFile($video_file_url)['errors'];

            if (count($errors) === 0){
                $file = new RemoteFile($video_file_url);

                (new ReviewsVideosModel())->saveVideo($review_id, [
                    'video' => $file,
                    'provider' => 'local',
                    'name' => pathinfo($file->getBasename())['filename'],
                ]);
            }
        }

        $this->jsonResponse($review->getAttributes());
    }

    /**
     * get reviews for product by product id with sorting
     */
    public function getReviews($product_id, $limit, $offset, $sort, $location): array
    {
        $overall_rating_id = RatingsModel::objects()->get(['slug' => 'overall'])['rating_id'];

        $ratings_alias = HelpfulReviewsModel::objects()->getQuerySet()->getTableAlias();
        $reviews_images_alias = ReviewsImagesModel::objects()->getQuerySet()->getTableAlias();
        $reviews_videos_alias = ReviewsVideosModel::objects()->getQuerySet()->getTableAlias();

        $user_id = $this->getUser()->user_id;
        $select_fields = [
            '*',
            'helpful__user_id',
            new Count('helpful__user_id', 'helpful_count'),
            'overall_rating' => 'rating__rating',
            'user_public_name' => 'user__public_name',
            'user_avatar' => 'user__avatar_image',
            'marked_helpful' => new Expression("IF($ratings_alias.user_id, true, false)"),
            'created_timestamp' => 'UNIX_TIMESTAMP(created)',
            //_no_distinct need for generate join
            //count images
            new Count('images__distinct__image_id', 'images_count_no_distinct'),
            "images_count" => "COUNT(DISTINCT `$reviews_images_alias`.`image_id`)",
            //count videos
            new Count('videos__distinct__video_id', 'videos_count_no_distinct'),
            "videos_count" => "COUNT(DISTINCT `$reviews_videos_alias`.`video_id`)",
        ];
        $filter_fields = [
            'product_id' => $product_id,
            new QOr([
                'rating__rating_id' => $overall_rating_id,
                'rating__rating__isnull' => true,
            ]),
            'location' => $location,
        ];

        // select user marked helpful if user authorised
        if ($user_id) {
            $select_fields['marked_helpful'] = new Expression("IF($ratings_alias.user_id, true, false)");
            $filter_fields[] = new QOr([
                'helpful__user_id' => $user_id,
                'helpful__user_id__isnull' => true,
            ]);
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
                $query_set = $query_set->order(['-marked_helpful']);
                break;


            case self::SORT_HAS_IMAGES:
                $query_set = $query_set->order(['-images_count']);
                $query_set->group(["product_review_id"]);
                break;

            case self::SORT_HAS_VIDEOS:
                $query_set = $query_set->order(['-videos_count']);
                $query_set->group(["product_review_id"]);
                break;

            case self::SORT_NEW:
                $query_set = $query_set->order(['-created_timestamp']);
                $query_set->group(["product_review_id"]);
                break;
        }

        $reviews = $query_set->all();

        for ($i = 0; $i < count($reviews); $i++) {
            $reviews[$i]['marked_helpful'] = $reviews[$i]['marked_helpful'] !== 0;
            $filter = [
                "review_id" => $reviews[$i]["product_review_id"]
            ];
            $reviews[$i]['files'] = [
                'images' => ReviewsImagesModel::objects()->select(['images__*'])->asArray()->all($filter),
                'videos' => ReviewsVideosModel::objects()->select(['videos__*'])->asArray()->all($filter),
            ];
        }

        return $reviews;
    }

    public function getReviewsAction()
    {
        $product_id = $this->data['productId'];
        $sort = $this->data['sort'] ?: self::SORT_DEFAULT;
        $offset = $this->data['offset'];
        $limit = $this->data['limit'];
        $ip = Xcart::app()->request->getUserIP();
        $default_location = 'US';
        $location = GeoIpHelper::getGeoipLocation($ip)->country ?: $default_location;
        $this->jsonResponse([
            'reviews' => $this->getReviews($product_id, $limit, $offset, $sort, $location),
            'country' => CountryModel::objects()->get(['code' => $location])->name,
        ]);
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
        $ip = Xcart::app()->request->getUserIP();
        $default_location = 'US';
        $location = GeoIpHelper::getGeoipLocation($ip)->country ?: $default_location;

        $this->jsonResponse([
            'ratings' => $this->getTotalRatings(),
            'reviews' => $this->getReviews($product_id, $limit, $offset, $sort, $location),
            'country' => CountryModel::objects()->get(['code' => $location])->name,
            'totalReviews' => $this->getTotalReviews(),
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

    public function checkVideoFile($url): array
    {
        $headers = get_headers($url, true);

        if (stripos($headers[0], '200 OK') === false) {
            return ['errors' => ['File not found']];
        }

        $fileSizeB = $headers['Content-Length'];
        $fileSizeMB = round($fileSizeB / 1024 / 1024, 2);
        $maxFileSizeMB = VideosModel::getMaxSizeMb();

        if ($fileSizeMB > $maxFileSizeMB) {
            $err = sprintf('This file has %sMB size. ', $fileSizeMB) .
                sprintf('Max acceptable file size is %sMB.', $maxFileSizeMB);
            return ['errors' => [$err]];
        }

        $file_ext = explode('/', $headers['Content-Type'])[1];

        if (!in_array($file_ext, VideosModel::ACCEPTABLE_FORMATS)) {
            $err = sprintf('Unsupported video format %s. Acceptable formats only: ', $file_ext) .
                implode(', ', VideosModel::ACCEPTABLE_FORMATS);
            return ['errors' => [$err]];
        }

        return ['errors' => []];
    }

    public function checkVideoFileAction()
    {
        $this->jsonResponse($this->checkVideoFile($this->data['videoFileUrl']));
    }
}
