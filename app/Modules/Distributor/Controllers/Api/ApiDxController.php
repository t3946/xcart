<?php


namespace Modules\Distributor\Controllers\Api;


use Modules\Distributor\Models\DistributorModel;
use Xcart\App\Controller\Controller;

class ApiDxController extends Controller
{
    public function getDxInfo($code)
    {
        if ($dx = DistributorModel::objects()->get(['code' => $code])) {
            $this->jsonResponse([
                'id' => $dx->manufacturerid,
                'name' => $dx->manufacturer,
                'prefix' => $dx->code . '-',
            ]);
        }
    }
}