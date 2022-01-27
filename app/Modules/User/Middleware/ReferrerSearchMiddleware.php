<?php

namespace Modules\User\Middleware;

use GuzzleHttp\Psr7\Query;
use Modules\User\Helpers\SurfingHelper;
use Modules\User\Models\SurfPathModel;
use Xcart\App\Middleware\Middleware;

class ReferrerSearchMiddleware extends Middleware
{
    public $isProcessRequest = true;

    public function processHttpRequest($request): void
    {

        if (!$request->getIsAjax()) {
            $url = $request->getRequestUri();

            $url = parse_url($url);
            if ($url && !empty($url['query'])) {
                $query = Query::parse($url['query']);

                if ($query && (!empty($query['q']) || !empty($query['qpvt']))) {

                    $query = !empty($query['q']) ? $query['q'] : ($query['qpvt'] ?: null);

                    $request->session->open();

                    SurfingHelper::logSurfPath(['resource_type' => SurfPathModel::GOAL_TYPE_SEARCH, 'additional_data' => $query]);
                }
            }
        }
    }
}
