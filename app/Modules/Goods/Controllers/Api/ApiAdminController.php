<?php

namespace Modules\Goods\Controllers\Api;

use Modules\Goods\Helpers\ProductVerificationHelper;
use Modules\Goods\Models\ProductModel;
use Modules\Goods\Models\VerificationStatusModel;
use Xcart\App\Controller\Controller;

class ApiAdminController extends Controller
{
    public function verify(): void
    {
        /** @var ProductModel $product */
        /** @var VerificationStatusModel $status */

        $result = ['result' => false];
        $post = $this->getRequest()->post;

        $product_id = $post->get('product_id');
        $status_id = $post->get('status_id');
        $note_text = $post->get('note_text') ?? '';

        $product = ProductModel::objects()->get(['productid' => $product_id]);
        $status = VerificationStatusModel::objects()->get(['statusid' => $status_id]);

        if ($product && $status) {
            ProductVerificationHelper::changeVerificationStatus($product, $status, $note_text);
            $result = ['result' => true];
        }
        $this->jsonResponse($result);
    }
}