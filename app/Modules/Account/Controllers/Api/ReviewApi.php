<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ProductReviewsModel;
use Modules\Account\Models\RatingsModel;
use Modules\Account\Models\ReviewRatingsModel;
use Modules\GeoIp\Helpers\GeoIpHelper;
use Xcart\App\Controller\FrontendController;
use Xcart\App\Main\Xcart;

class ReviewApi extends FrontendController
{
    private $data;

    public function __construct($request)
    {
        parent::__construct($request);
        $this->data = json_decode(file_get_contents('php://input'), true);
    }

    private function createRating($review_id, $rating_slug, $rating)
    {
        $rating_id = (int)RatingsModel::objects()->get(['slug'=>$rating_slug])->getAttribute('rating_id');

        (new ReviewRatingsModel([
            'review_id' => $review_id,
            'rating_id' => $rating_id,
            'rating' => $rating,
        ]))->save();
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
    public function getProductReviews()
    {
        $product_id = $this->data['productId'];

        $overall_rating_id = RatingsModel::objects()->get(['slug' => 'overall'])['rating_id'];

        $reviews = ProductReviewsModel::objects()
            ->select(['*', 'overall_rating' => 'rating__rating'])
            ->asArray()
            ->limit(3)
            ->order('created')
            ->filter([
                'product_id' => $product_id,
                'user_id' => 49,
                'rating__rating_id' => $overall_rating_id,
            ])
            ->all();


        $this->jsonResponse(
            [
                'reviews' => $reviews,
            ]
        );
    }
}
