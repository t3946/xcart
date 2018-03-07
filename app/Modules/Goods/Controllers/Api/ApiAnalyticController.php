<?php

namespace Modules\Goods\Controllers\Api;


use Modules\User\Helpers\SurfingHelper;
use Xcart\App\Controller\Controller;

class ApiAnalyticController extends Controller
{
    public $defaultAction = 'index';

    public function index()
    {

        if ( $url = $this->getRequest()->post->get('url') )
        {
            $url = parse_url($url);

            d($url);

            SurfingHelper::logSurfPath([
                'resource_type' => $this->getRequest()->get->get('type'),
                'resource_id' => $this->getRequest()->get->get('id'),
            ]);
        }
    }
}