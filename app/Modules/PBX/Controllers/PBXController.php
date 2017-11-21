<?php

namespace Modules\PBX\Controllers;

use Modules\PBX\Models\PbxAnveoModel;
use Xcart\App\Controller\Controller;

class PBXController extends Controller
{
    public function actionCallback()
    {
        /** @var PbxAnveoModel $model */
        $request = $this->getRequest();
        $now = date('Y-m-d H:i:s');

        if ($request->get->has('incoming_flow_start') || $request->get->has('outgoing_flow_start')) {

            $session = ($request->get['ss']);
            $model = new PbxAnveoModel(['session' => $session, 'start_at' => $now]);

            if ($request->get->has('outgoing_flow_start')) {
                $model->is_outgoing = true;
                $model->e164 = $request->get['ee'];
            }
            else {
                $model->e164 = $request->get['ee'];
            }

            $model->save();
        }

        if ($request->get->has('incoming_flow_end') || $request->get->has('outgoing_flow_end')) {
            $session = ($request->get['ss']);
            if ($model = PbxAnveoModel::objects()->get(['session' => $session])) {
                $model->end_at = $now;
                $model->save();
            }
        }

        if ($request->get->has('lost_call')) {
            sleep(3);
            $session = ($request->get['ss']);
            if ($model = PbxAnveoModel::objects()->get(['session' => $session])) {

                $model->is_lost = true;

                if ($request->get->has('ee')) {
                    $ee = ($request->get['ee']);
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

                $model->save();

            }
            else {

                (new PbxAnveoModel(['session' => $session,
                                    'is_lost' => true,
                                    'e164' => ($request->get['ee']),
                                    'rdnis' => ($request->get['rdnis']),
                                    'cname' => ($request->get['cname']),
                                   ]))->save();
            }
        }

        if ($request->post->has('ss')) {
            $session = ($_POST['ss']);
            if ($model = PbxAnveoModel::objects()->get(['session' => $session])) {

                if ($request->post->has('file')) {
                    $file = ($request->post['file']);
                    $model->file = $file;
                }

                if ($request->post->has('uacc')) {
                    $uacc = ($request->post['uacc']);
                    $model->anveo_account = $uacc;
                }
                if ($request->post->has('cnam')) {
                    $cname = ($request->post['cnam']);
                    $model->cname = $cname;
                }
                if ($request->post->has('ee')) {
                    if (!$e164 = ($request->post['ee'])) {
                        $file_parts = explode('-', $file);
                        if (!empty($file_parts[5])) {
                            $regexp = '/(.*)\..*/';
                            if (preg_match($regexp, $file_parts[5], $matches)) {
                                $e164 = $matches[1];
                            }
                            else {
                                $e164 = $file_parts[5];
                            }
                        }
                    }
                    $model->e164 = $e164;
                }

                $model->save();
            }
        }
    }


}