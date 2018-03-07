<?php

namespace Modules\Goods\Controllers\Api;


use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Controller\Controller;

class ApiAnalyticController extends Controller
{
    public $defaultAction = 'index';

    public function index()
    {
        if ( $url = $this->getRequest()->post->get('url') )
        {
            $url = parse_url($url);

            if (!empty($url['path'])) {
                $path = $url['path'];
                $type = null;
                $id = null;

                if (preg_match("/\/(\d+)\//", $path, $match)) {
                    $id = $match[1];
                }


                if (in_array($path, ['', '/'])) {
                    $type = SurfPathModel::GOAL_TYPE_HOME_PAGE;
                }
                elseif (strpos($path, 'product')) {
                    $type = SurfPathModel::GOAL_TYPE_PRODUCT;
                }
                elseif (strpos($path, 'category')) {
//                    $type = SurfPathModel::GOAL_TYPE_CATEGORY;
                }
                elseif (strpos($path, 'category')) {
//                    $type = SurfPathModel::GOAL_TYPE_BRAND;
                }

                if ($type && $id) {
                    SurfingHelper::logSurfPath([
                        'resource_type' => $type,
                        'resource_id' => $id,
                    ]);
                }
            }
        }
    }
}