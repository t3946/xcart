<?php


namespace Modules\Distributor\Controllers\Api;


use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Xcart\App\Controller\Controller;

class ApiDxController extends Controller
{
    public function getDxInfo($code): void
    {
        /** @var DistributorModel $dx */
        /** @var SupplierFeedModel $feed */

        if ($dx = DistributorModel::objects()->get(['code' => $code])) {
            $feedData = [];
            foreach ($dx->feeds as $feed) {
                $feedData[] = [
                    'type' => ($type = $feed->getField('type')) ? $type->toText() : null,
                    'feed_source' => $feed->feed_source,
                    'feed_file_name' => $feed->feed_file_name,
                    'md5' => $feed->last_md5,
                    'enabled' => $feed->enabled,
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