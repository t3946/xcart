<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12/13/2017
 * Time: 3:51 PM
 */

namespace Modules\PBX\Controllers\Admin;

use Mindy\QueryBuilder\Expression;
use Mindy\QueryBuilder\Q\QOr;
use Modules\Admin\Controllers\BackendController;
use Modules\PBX\Forms\CallsFilterForm;
use Modules\PBX\Helpers\PBXHelper;
use Modules\PBX\Models\PbxAnveoCallModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class PBXController extends BackendController
{
    public function index()
    {
        $form = new CallsFilterForm();
        $form->populate($_GET);

        $qs =  PbxAnveoCallModel::objects()->order(['-start_at']);
        if (!empty($_GET)) {

            /** @var \Xcart\App\Orm\QuerySet $qs */
            foreach ($form->getAttributes() as $key => $value){
                if ($value) {

                    if ($key == 'order') {
                        $qs->filter(['orders__orderid' => $value]);
                    }
                    elseif ($key == 'date_from') {
                        $date = PBXHelper::getClearDate($value);
                        $qs->filter(['start_at__gte' => $date->format('Y-m-d H:i:s')]);
                    }
                    elseif ($key == 'date_to') {
                        $date = PBXHelper::getClearDate($value);
                        $qs->filter(['end_at__lte' => $date->format('Y-m-d H:i:s')]);
                    }
                    elseif ($key == 'operator'){

                        if (!empty($value)) {
                            $qs->filter(['account__user__id__in' => $value]);
                        }
                    }
                    elseif ($key == 'e164'){
                        $qs->filter([$key => $value]);
                    }
                    elseif ($key == 'direction'){
                        if ($value == 'in'){
                            $qs->filter([
                                'is_outgoing' => 0,
                                'is_lost' => 0,
                                'is_voice_mail' => 0
                                        ]);
                        }
                        elseif ($value == 'out'){
                            $qs->filter(['is_outgoing' => 1]);
                        }
                        elseif ($value == 'lost'){
                            $qs->filter(['is_lost' => 1]);
                        }
                        elseif ($value == 'vm'){
                            $qs->filter(['is_voice_mail' => 1]);
                        }
                    }

                }

            }

        }

        $pager = new Pagination($qs,[], new QuerySetDataSource());

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

                if ($orders = $model->bind_calls->all()) {
                    $count = count($orders);
                    for($j = 0; $j < $count; $j++){
                        $order_id = $orders[$j]->order_id;
                            if(!empty($order_id)) {
                                $mass[ $i ]['order'][ $j ]['order_id'] = $model->orders->get(['orderid' => $order_id])
                                                                                       ->getOrderNumber();
                                $mass[ $i ]['order'][ $j ]['order_url'] = $model->orders->get(['orderid' => $order_id])
                                                                                        ->getAdminUrl();
                            }
                    }
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
            'pager' => $pager,
            'page_title' => $pageTitle,
            'form' => $form,
        ]);
    }
}