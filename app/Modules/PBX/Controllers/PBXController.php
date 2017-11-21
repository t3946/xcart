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
        $now = date('Y-m-d H:i:s');

        if ($session) {
            $model = PbxAnveoCallModel::objects()->getOrCreate(['session' => $session]);

            if ($request->get->has('incoming_flow_start') || $request->get->has('outgoing_flow_start')) {
                $model->start_at = $now;
                $model->e164 = $request->get['ee'];

                if ($request->get->has('outgoing_flow_start')) {
                    $model->is_outgoing = true;
                }
            }

            if ($request->get->has('incoming_flow_end') || $request->get->has('outgoing_flow_end')) {
                $model->end_at = $now;
            }

            if ($request->get->has('lost_call'))
            {
                $model->is_lost = true;

                if ($request->get->has('ee')) {
                    $ee = $request->get['ee'];
                    $model->e164 = $ee;
                }

                if ($request->get->has('rdnis')) {
                    $rdnis = ($request->get['rdnis']);
                    $model->rdnis = $rdnis;
                }

                if ($request->get->has('cname')) {
                    $cname = ($request->get['cname']);
                    $model->cname = $cname;
                }
            }

            if ($request->getIsPost())
            {
                $file = '';

                if ($request->post->has('file')) {
                    $file = $request->post['file'];
                    $model->file = $file;
                }

                if ($request->post->has('uacc')) {
                    $uacc = $request->post['uacc'];
                    $model->anveo_account = $uacc;
                }

                if ($request->post->has('cnam')) {
                    $cname = $request->post['cnam'];
                    $model->cname = $cname;
                }

                if ($request->post->has('ee')) {
                    $e164 = $request->post['ee'];

                    if ( empty($e164) && $file)
                    {
                        $file_parts = explode('-', $file);

                        if (!empty($file_parts[5])) {

                            if (preg_match('/(.*)\..*/', $file_parts[5], $matches)) {
                                $e164 = $matches[1];
                            }
                            else {
                                $e164 = $file_parts[5];
                            }
                        }
                    }

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