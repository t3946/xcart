<?php

namespace Modules\Account\Controllers\Api;

use Modules\Account\Models\ReviewModel;
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
        $reviews = ReviewModel::objects()->all(['product_id' => $this->data['product_id']]);
        $this->jsonResponse(array_map(fn($review) => $review->toArray(), $reviews));
    }
}