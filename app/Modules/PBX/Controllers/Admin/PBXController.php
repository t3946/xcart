<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/13/2017
 * Time: 3:51 PM
 */

namespace Modules\PBX\Controllers\Admin;

use Mindy\QueryBuilder\Expression;
use Modules\Admin\Controllers\BackendController;
use Modules\PBX\Models\PbxAnveoCallModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class PBXController extends BackendController
{
    public function index()
    {
        $request = $this->getRequest();
        $keys = ["direction", "order_id", "date_from", "date_to"];
        if ($request->get->has('filter')) {
            if ($request->get('filter') == 1){

            }
        }

        $op = [];

        $filter = [
            'usertype' => 'A',
            'status' => 'Y',
            'login__isnt' => 'sergey2',
            new Expression("trim(pbx_extension) != '' ")
        ];

        $operators = UserModel::objects()
                              ->filter($filter)
                              ->all();

        foreach ($operators as $operator){
            $op[] = $operator->firstname;
        }

        $pager = new Pagination(PbxAnveoCallModel::objects(),[], new QuerySetDataSource());

        $mass = [];
        $i = 0;
        /** @var PbxAnveoCallModel $model */
        foreach ($pager->paginate() as $model) {
            if ($model) {

                $name = "Not defined";

                if ($model->anveo_account) {
                    if ($options = $model->options) {
                        if ($user = $model->options->user) {
                            $name = $user->firstname;
                        }
                    }
                }


                $mass[ $i ] = [
                    'call_id' => $model->id,
                    'name' => $name,
                    'cx_name' => $model->cname,
                    'e164' => $model->getFrontendE164(),
                    'start_at' => $model->start_at,
                ];

                if ( !empty($model->file) ) {
                    $mass[$i]['url'] = $model->getUrl();
                }

                if ($order = $model->bind_calls) {
                    $order_id = $order->order_id;
                    $mass[$i]['order_id'] = $model->orders->get(['orderid' => $order_id])->getOrderNumber();
                }

                if ($model->isOutgoing()) {
                    $mass[ $i ]['direction'] = "Outbound";
                }
                elseif ($model->isLost()) {
                    $mass[ $i ]['direction'] = "Miss call";
                }
                elseif ($model->isVoiceMail()) {
                    $mass[ $i ]['direction'] = "Voice mail";
                }
                else {
                    $mass[ $i ]['direction'] = "Inbound";
                }

                if (!is_null($model->end_at) && !is_null($model->start_at)) {
                    $datetime1 = new \DateTime($model->end_at);
                    $datetime2 = new \DateTime($model->start_at);
                    $interval = $datetime1->diff($datetime2);

                    $mass[ $i ]['diff'] = $interval->format('%H:%I:%S');

                    $i++;
                }
                else {
                    $mass[ $i ]['diff'] = "Not defined";
                }
            }
        }

        $pageTitle = "Call records";

        Xcart::app()->breadcrumbs->add('pbxcalls');
        echo $this->renderInSmarty("admin/pbxcall/index.tpl", [
            'mass' => $mass,
            'operators' => $op,
            'pager' => $pager,
            'page_title' => $pageTitle,
        ]);
    }
}