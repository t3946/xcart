<?php


namespace Modules\Distributor\Controllers\Api;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Controller\Controller;

class ApiDxController extends Controller
{
    public function getDxInfo($code)
    {
        /** @var DistributorModel $dx */

        if ($dx = DistributorModel::objects()->get(['code' => $code])) {
            foreach ($feeds = $dx->feeds as $feed) {
                $feedData[] = [
                    'feed_source' => $feed->feed_source,
                    'feed_file_name' => $feed->feed_file_name,
                    'md5' => $feed->last_md5,
                    'enabled' => $feed->enabled === 'Y',
                    'source_date' => $feed->feed_source_date
                ];
            }
            $this->jsonResponse([
                'id' => $dx->manufacturerid,
                'name' => $dx->manufacturer,
                'prefix' => $dx->code . '-',
                'source' => $dx->url,
                'feeds' => $feedData
            ]);
        }
    }
}