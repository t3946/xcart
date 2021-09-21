<?php


namespace Modules\Distributor\Controllers\Api;


use Cron\CronExpression;
use Modules\Core\Helpers\CoreHelper;
use Modules\Distributor\Models\DistributorModel;
use Modules\Distributor\Models\SupplierFeedModel;
use Modules\Sites\Models\SiteModel;
use Xcart\App\Controller\Controller;

class ApiDxController extends Controller
{

    public function getDxInfo($code, $sfId = null): void
    {
        /** @var DistributorModel $dx */
        /** @var SupplierFeedModel $feed */

        if ($dx = DistributorModel::objects()->get(['code' => $code])) {
            $feedData = [];
            if ($sfId !== null) {
                $filter = ['storefront_id' => $sfId];
            }
            foreach ($dx->feeds->filter($filter ?? []) as $feed) {
                $feedData[$feed->storefront_id] = [
                    'type' => ($type = $feed->getField('feed_type')) ? $type->toText() : null,
                    'feed_source' => $feed->feed_source,
                    'feed_file_name' => $feed->feed_file_name,
                    'dont_update_fields' => $feed->dont_update_fields,
                    'md5' => $feed->last_md5,
                    'enabled' => $feed->enabled,
                    'source_date' => $feed->feed_source_date,
                ];
            }

            $this->jsonResponse([
                'id' => $dx->manufacturerid,
                'name' => $dx->manufacturer,
                'prefix' => $dx->code . '-',
                'source' => $dx->url,
                'authenticate' => [
                    'login' => $dx->d_login ? CoreHelper::cipherText($dx->d_login) : '',
                    'password' => $dx->d_password ? CoreHelper::cipherText($dx->d_password) : ''
                ],
                'feeds' => $feedData
            ]);
        }
    }
    public function getDxInfoSfCode($code, $sfCode = null): void
    {
        if ($sfCode && $site = SiteModel::objects()->get(['code' => $sfCode])) {
            $this->getDxInfo($code, $site->storefrontid);
        }
    }

}