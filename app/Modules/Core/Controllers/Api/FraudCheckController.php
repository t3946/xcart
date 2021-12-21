<?php

namespace Modules\Core\Controllers\Api;

use Exception;
use Modules\Core\Models\FraudCheckColumnModel;
use Modules\Core\Models\FraudFAQuestionModel;
use Modules\Core\Models\GlobalConfigModel;
use Modules\Order\Models\FraudCheckBaseQuestionModel;
use Modules\Order\Models\FraudStatusModel;
use Modules\Sites\Models\SiteModel;
use Modules\User\Models\UserModel;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Exceptions\UnknownPropertyException;
use Xcart\App\Main\Xcart;

class FraudCheckController extends Controller
{

    public function getAllFAQuestions(): array
    {
        $ar_result = [];
        /** @var FraudFAQuestionModel $question */
        foreach (FraudFAQuestionModel::objects()->all() as $question) {
            $ar_result[$question->f_fraud->type]['data'][] = [
                'value' => number_format($question->weight, 2),
                'f_fraud' => $question->f_fraud->name,
                't_fraud' => $question->t_fraud->name,
                'template' => $question->template,
                'questionId' => $question->question_id,
                'questionCode' => (string)$question
            ];
        }
        /** @var FraudCheckColumnModel $column */
        foreach (FraudCheckColumnModel::objects()->all() as $column) {
            $ar_result[$column->type]['columns'][] = $column->name;
        }
        return $ar_result;
    }

    /**
     * @throws UnknownPropertyException
     */
    public function getFraudCheckSettings(): void
    {
        $ar_settings = [
            'faQuestions' => $this->getAllFAQuestions(),
            'baseQuestions' => $this->getAllBaseQuestions(),
            'settings' => $this->getBaseSettings(),
        ];
        $this->jsonResponse($ar_settings);
    }

    public function getAllBaseQuestions(): array
    {
        $ar_result = [];
        /** @var FraudCheckBaseQuestionModel $question */
        foreach (FraudCheckBaseQuestionModel::objects()->order(['orderby'])->all() as $question) {
            $ar_result[] = [
                'questionId' => $question->question_id,
                'questionCode' => $question->question_code,
                'auto' => $question->auto,
                'template' => $question->question_template_body,
                'weight' => $question->weight,
                'type' => $question->type,
                'orderBy' => $question->orderby,
                'avail' => (int)$question->avail,
            ];
        }
        return $ar_result;
    }

    /**
     * @throws UnknownPropertyException
     * @throws Exception
     */
    public function getBaseSettings(): array
    {
        global $fraud_Google_address_search_exclusions, $fraud_Google_phone_search_exclusions, $fraud_Google_email_search_exclusions;
        $ar_settings = [];
        /** @var SiteModel $site */
        $site = Xcart::app()->getModule('Sites')->getSite();
        $config = $site->getGlobalConfig();

        $fraud_status = FraudStatusModel::objects()->order(['order_by'])->valuesList(['code', 'name']);
        $ar_settings['data'] = [
            'fraud_domains_free_email_provider' => $config['fraud_domains_free_email_provider'] ?? '',
            'Overall_RS_threshold_for_Clear_status' => (float)$config['Overall_RS_threshold_for_Clear_status'] ?? 0,
            'Risk_Score_Threshold_status' => $config['Risk_Score_Threshold_status'] ?? '',
            'Overall_FC_threshold_for_Clear_status' => (float)$config['Overall_FC_threshold_for_Clear_status'] ?? 0,
            'Threshold_status' => $config['Threshold_status'] ?? '',
            'below_threshold_status' => $config['below_threshold_status'] ?? '',
            'fraud_Google_address_search_exclusions' => $fraud_Google_address_search_exclusions ?? '',
            'fraud_Google_phone_search_exclusions' => $fraud_Google_phone_search_exclusions ?? '',
            'fraud_Google_email_search_exclusions' => $fraud_Google_email_search_exclusions ?? '',
            'fraudulent_domains' => $config['fraudulent_domains'],
            'Under_review_users' => !empty($config['Under_review_users'])
                ? explode(',', $config['Under_review_users'])
                : [],
        ];
        $ar_settings['settings'] = [
            'users' => UserModel::admins()->valuesList(['id', 'firstname']),
            'status' => $fraud_status
        ];
        return $ar_settings;
    }

    public function updateFraudSettings(): void
    {
        $fraud_settings = json_decode(file_get_contents('php://input'), true);
        try {
            foreach ($fraud_settings as $attr => $value) {
                switch ($attr) {
                    case 'Under_review_users':
                        GlobalConfigModel::objects()->updateOrCreate(['name' => $attr], ['value' => implode(',', $value)]);
                        break;
                    default:
                        GlobalConfigModel::objects()->updateOrCreate(['name' => $attr, 'category' => 'Fraud_check'], ['value' => $value]);
                        break;
                }
            }
            $this->jsonResponse(['update' => true]);
        } catch (Throwable $exception) {
            $this->jsonResponse(['message' => $exception->getMessage()], 400);
        }

    }

    public function updateFAQuestion(): void
    {
        try {
            $update_data = json_decode(file_get_contents('php://input'), true);
            /** @var FraudFAQuestionModel $question_model */
            $question_model = FraudFAQuestionModel::objects()->get(['question_id' => $update_data['questionId']]);
            $question_model->template = $update_data['template'];
            $question_model->weight = $update_data['weight'];
            $this->jsonResponse(['update' => $question_model->save()]);
        } catch (Throwable $exception) {
            $this->jsonResponse(['message' => $exception->getMessage()], 400);
        }
    }

    public function updateBaseQuestion(): void
    {
        $update_data = json_decode(file_get_contents('php://input'), true);
        /** @var FraudCheckBaseQuestionModel $question_model */
        $question_model = FraudCheckBaseQuestionModel::objects()->get(['question_id' => $update_data['questionId']]);
        $question_model->orderby = $update_data['orderBy'];
        $question_model->question_template_body = $update_data['template'];
        $question_model->weight = $update_data['weight'];
        $question_model->avail = $update_data['avail'];
        $this->jsonResponse(['update' => $question_model->save()]);
    }
}