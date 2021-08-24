<?php

namespace Modules\Core\Controllers\Api;

use Modules\Core\Models\FraudCheckColumnModel;
use Modules\Core\Models\FraudFAQuestionModel;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\User\Models\UserModel;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;

class FraudCheckController extends Controller
{
    public function getFraudFullName()
    {
        return FraudFAQuestionModel::objects()->filter(['type' => 'full_name']);
    }

    public function getFraudAddress()
    {
        return FraudFAQuestionModel::objects()->filter(['type' => 'address']);
    }

    public function getAll()
    {
        $ar_result = ['status' => false];
        foreach ($this->getFraudFullName() as $item) {
            $ar_result['full_name']['data'][] = [
                'section' => "{$item->f_fraud->fraud_name}:{$item->t_fraud->fraud_name}",
                'value' => $item->weight,
                'f_fraud' => $item->f_fraud->fraud_code,
                't_fraud' => $item->t_fraud->fraud_code,
            ];
        }
        $ar_result['full_name']['columns'] = FraudCheckColumnModel::objects()->filter(['type' => 'full_name'])->valuesList(['fraud_name'], true);
        foreach ($this->getFraudAddress() as $item) {
            $ar_result['address']['data'][] = [
                'section' => "{$item->f_fraud->fraud_name}:{$item->t_fraud->fraud_name}",
                'value' => $item->weight,
                'f_fraud' => $item->f_fraud->fraud_name,
                't_fraud' => $item->t_fraud->fraud_name,
            ];
        }
        $ar_result['address']['columns'] = FraudCheckColumnModel::objects()->filter(['type' => 'address'])->valuesList(['fraud_name'], true);
        $ar_result['status'] = true;
        $this->jsonResponse($ar_result);
    }

    public function updateWeight(): void
    {
        $post = Xcart::app()->request->post;
        $ar_result = ['status' => true];
        $update = json_decode($post['update'], true);
        try {
            foreach ($update as $fraud_group => $value) {
                $ar_fraud = explode(':', $fraud_group);
                $f_fraud_column = FraudCheckColumnModel::objects()->get(['fraud_name' => $ar_fraud[0]]);
                $t_fraud_column = FraudCheckColumnModel::objects()->get(['fraud_name' => $ar_fraud[1]]);
                /** @var FraudFAQuestionModel $fraud */
                $fraud = FraudFAQuestionModel::objects()->get(['f_fraud_id' => $f_fraud_column, 't_fraud_id' => $t_fraud_column]);
                $fraud->weight = $value;
                $fraud->save();
            }
        } catch (\Exception $exception) {
            $ar_result = [
                'status' => false,
                'error' => $exception->getMessage(),
            ];
        } finally {
            $this->jsonResponse($ar_result);
        }
    }

    public function getBaseSettings(): void
    {
        global $fraud_Google_address_search_exclusions, $fraud_Google_phone_search_exclusions, $fraud_Google_email_search_exclusions;
        $ar_result = ['status' => true];
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getGlobalConfig();

        $fraud_status = FraudStatusModel::objects()->order('order_by')->valuesList(['code', 'name']);
        $ar_result['data'] = [
            'fraud_domains_free_email_provider' => $config['fraud_domains_free_email_provider'] ?? '',
            'Overall_RS_threshold_for_Clear_status' => (float)$config['Overall_RS_threshold_for_Clear_status'] ?? 0,
            'Risk_Score_Threshold_status' => $config['Risk_Score_Threshold_status'] ?? '',
            'Overall_FC_threshold_for_Clear_status' => (float)$config['Overall_FC_threshold_for_Clear_status'] ?? 0,
            'Threshold_status' => $config['Threshold_status'] ?? '',
            'below_threshold_status' => $config['below_threshold_status'] ?? '',
            'fraud_Google_address_search_exclusions' => $fraud_Google_address_search_exclusions ?? '',
            'fraud_Google_phone_search_exclusions' => $fraud_Google_phone_search_exclusions ?? '',
            'fraud_Google_email_search_exclusions' => $fraud_Google_email_search_exclusions ?? '',
            'Under_review_users' => !empty($config['Under_review_users'])
                ? explode(',', $config['Under_review_users'])
                : [],
        ];
        $ar_result['settings'] = [
            'users' => UserModel::admins()->valuesList(['id', 'firstname']),
            'status' => $fraud_status
        ];
        $this->jsonResponse($ar_result);
    }

    public function updateFraudSettings(): void
    {
        $fraud_settings = json_decode(file_get_contents('php://input'), true);
        $ar_result = ['status' => true];
        try {
            foreach ($fraud_settings as $attr => $value) {
                switch ($attr) {
                    case 'Under_review_users':
                        GlobalConfigModel::objects()->updateOrCreate(['name' => $attr], ['value' => implode(',', $value)]);
                        break;
                    default:
                        GlobalConfigModel::objects()->updateOrCreate(['name' => $attr], ['value' => $value]);
                        break;
                }
            }
        } catch (\Exception $exception) {
            $ar_result = [
                'status' => false,
                'error' => $exception->getMessage()
            ];
        } finally {
            $this->jsonResponse($ar_result);
        }
    }
}