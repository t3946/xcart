<?php

namespace Modules\PBX\Controllers;

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

        if ($session) {
            list($model, $isNew) = PbxAnveoCallModel::objects()->getOrCreate(['session' => $session]);

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

                if (empty($e164) && !empty($model->file)) {
                    $file_parts = explode('-', $model->file);

                    if (!empty($file_parts[5])) {

                        if (preg_match('/(.*)\..*/', $file_parts[5], $matches)) {
                            $e164 = $matches[1];
                        }
                        else {
                            $e164 = $file_parts[5];
                        }
                    }
                }

                if ($e164) {
                    $model->e164 = $e164;
                }
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