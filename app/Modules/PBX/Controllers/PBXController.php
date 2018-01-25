<?php

namespace Modules\PBX\Controllers;

use Modules\PBX\Helpers\AnveoAssignCalls;
use Modules\PBX\Models\PbxAnveoCallModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class PBXController extends Controller
{
    public function actionCallback()
    {
        /** @var PbxAnveoCallModel $model */
        $request = $this->getRequest();
        $session = $this->getCallSession();
        $now = new \DateTime();

        if ($_GET){
            $string = "";
            foreach ($_GET as $k => $v){
                $string .= "  {$k} => {$v}  ";
            }
            $log_category = "Calls_Record_Anveo";
            $log_text = "Звонок: {$string}";
            func_backprocess_log($log_category, $log_text);
        }

        if ($_POST){
            $string = "";
            foreach ($_POST as $k => $v){
                $string .= "  {$k} => {$v}  ";
            }
            $log_category = "Calls_Record_Anveo";
            $log_text = "Звонок: {$string}";
            func_backprocess_log($log_category, $log_text);
        }



        if ($session) {
            list($model, $isNew) = PbxAnveoCallModel::objects()->getOrCreate(['session' => $session]);

            $model->is_voice_mail = $model->is_voice_mail ?: $request->get->has('vm');

            if ($request->get->has('incoming_flow_start') || $request->get->has('outgoing_flow_start')) {
                $model->is_outgoing = $request->get->has('outgoing_flow_start');
                $model->start_at = $now;
                $model->e164 = $model->e164 ?: $request->get['ee'];
            }

            if ($request->get->has('incoming_flow_end') || $request->get->has('outgoing_flow_end')) {
                $model->is_outgoing = $model->is_outgoing ?: $request->get->has('outgoing_flow_end');
                $model->end_at = $now;
            }

            if ($request->get->has('lost_call'))
            {
                $model->is_lost = true;

                if ($request->get->has('ee')) {
                    $model->e164 = $model->e164 ?: $request->get['ee'];
                }

                if ($request->get->has('rdnis')) {
                    $model->rdnis = $request->get['rdnis'];
                }

                if ($request->get->has('cname')) {
                    $model->cname = $request->get['cname'];
                }
            }

            if ($request->getIsPost())
            {
                $e164 = '';

                if ($request->post->has('file')) {
                    $model->file = $request->post['file'];
                }

                if ($request->post->has('uacc')) {
                    $model->anveo_account = $request->post['uacc'];
                }

                if ($request->post->has('cnam')) {
                    $model->cname = $request->post['cnam'];
                }

                if ($request->post->has('ee')) {
                    $e164 = $request->post['ee'];
                }

                if (!empty($model->file)) {
                    $e164 = $e164 ?: AnveoAssignCalls::parseE164($model->file);
                    $account = AnveoAssignCalls::parseAccount($model->file);

                    if ($account && $model->anveo_account != $account) {
                        $model->anveo_account = $account;
                    }
                }

                if ($e164) {
                    $model->e164 = $e164;
                }
            }

            if ($request->get['outgoing_flow_start'] || $request->get['outgoing_flow_end']){
                $log_category = "Calls_Record_Anveo";

                if ($request->get->has('outgoing_flow_start')) {
                    $log_text = "Исходящий звонок начался со след.сессией: {$request->get['ss']}";
                }
                elseif ($request->get->has('outgoing_flow_end')){
                    $log_text = "Исходящий звонок закончился со след.сессией: {$request->get['ss']}";
                }
                func_backprocess_log($log_category, $log_text);
            }

            $model->save();
            Xcart::app()->event->trigger('anveo:call', ['model' => $model]);
        }
    }

    private function getCallSession()
    {
        if ( $this->getRequest()->getIsPost() ) {
            return $this->getRequest()->post['ss'];
        }
        else {
            return $this->getRequest()->get['ss'];
        }
    }
}