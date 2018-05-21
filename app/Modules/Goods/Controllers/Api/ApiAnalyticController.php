<?php

namespace Modules\Goods\Controllers\Api;


use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class ApiAnalyticController extends Controller
{
    public $defaultAction = 'index';

    public function index(): void
    {
        if ( $url = $this->getRequest()->post->get('url') )
        {
            $url = parse_url($url);

            if (!empty($url['path'])) {
                $path = $url['path'];
                $type = null;
                $referrer = $this->getRequest()->post->get('referrer');
                $id = null;
                $advanced = '';

                if (preg_match("/\/(\d+)\//", $path, $match)) {
                    $id = $match[1];
                }

                if (\in_array($path, ['', '/'], true)) {
                    $type = SurfPathModel::GOAL_TYPE_HOME_PAGE;
                    $id = 1;
                }
                elseif (strpos($path, '/product/') !== false) {
                    $type = SurfPathModel::GOAL_TYPE_PRODUCT;
                }
                elseif (strpos($path, '/category/') !== false) {
                    $type = SurfPathModel::GOAL_TYPE_CATEGORY;
                }
                elseif (strpos($path, '/brand/') !== false) {
                    $type = SurfPathModel::GOAL_TYPE_BRAND;
                }
                elseif (strpos($path, '/keyword/') !== false) {
                    $type = SurfPathModel::GOAL_TYPE_SEARCH;
                    $id = Xcart::app()->request->session->get('e_search_data_orig_substring');
                }

                if ($type && $id) {
                    SurfingHelper::logSurfPath([
                        'resource_type' => $type,
                        'resource_id' => $id,
                        'referrer' => $referrer,
                        'additional_data' => $advanced
                    ]);
                }
            }
        }
    }
}