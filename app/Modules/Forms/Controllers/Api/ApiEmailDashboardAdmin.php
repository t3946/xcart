<?php


namespace Modules\Forms\Controllers\Api;


use Modules\Forms\Helpers\SnippetHelper;
use Modules\Forms\Models\EmailActionLogModel;
use Modules\Forms\Models\EmailActionModel;
use Modules\Forms\Models\EmailEntityModel;
use Modules\Forms\Models\EmailFavoriteModel;
use Modules\Forms\Models\EmailLabelModel;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\EmailViewedModel;
use Modules\Forms\Models\LabelModel;
use Modules\Forms\Models\TemplateCategoryModel;
use Modules\Forms\Models\TemplateModel;
use Modules\Help\Models\HelpListModel;
use Modules\Mail\Helpers\GmailHelper;
use Throwable;
use Xcart\App\Controller\Controller;
use Xcart\App\Main\Xcart;
use Xcart\App\Pagination\DataSource\QuerySetDataSource;
use Xcart\App\Pagination\Pagination;

class ApiEmailDashboardAdmin extends Controller
{
    public function actionGetEmails(int $page)
    {
        $emails = [];

        $actionItem = [];

        $searchParams = json_decode(file_get_contents('php://input'));

        $searchParams = $searchParams->searchParams;

        $qs = EmailModel::objects()->getQuerySet()->order(['-date']);
        if ($searchParams->hasAttachment) {
            $actionItem = array_merge($actionItem, ['attachments__attachment__isnull' => false]);
            $actionItem = array_merge($actionItem, ['attachments__cid__isnull' => true]);
        }

        if ($searchParams->from) {
            $actionItem = array_merge($actionItem, ['from_address__contains' => $searchParams->from]);
        }
        if ($searchParams->label) {
            $actionItem = array_merge($actionItem, ['labels__label_id__in' => $searchParams->label]);
        }

        if ($searchParams->to) {
            $actionItem = array_merge($actionItem, ['to_address__contains' => $searchParams->to]);
        }

        if ($searchParams->subject) {
            $actionItem = array_merge($actionItem, ['subject__contains' => $searchParams->subject]);
        }
        if ($searchParams->dateAfter) {
            $actionItem = array_merge($actionItem, ['date__gte' => $searchParams->dateAfter]);
        }
        if ($searchParams->dateBefore) {
            $actionItem = array_merge($actionItem, ['date__lte' => $searchParams->dateBefore]);
        }
        if ($searchParams->distributorId) {
            $qs = $qs->filter(["dx_models__manufacturerid" => $searchParams->distributorId]);
        }

        $qs = $qs->filter($actionItem);

        $pagination = new Pagination($qs, [
            'page' => $page,
            'pageSize' => 20,
        ], new QuerySetDataSource());

        try {
            foreach ($pagination->paginate() as $model) {
                /** @var EmailModel $model */
                $email = $model->getAttributes();
                $email['viewed'] = $model->isViewed();
                $email['action'] = $model->getAction($model->id);
                $email['favorite'] = $model->isFavorite();
                $email['attachment'] = $model->getAttachment();
                $email['body'] = (string)$model->getBody();
                $email['labels'] = $model->getLabels();
                $email['emailType'] = $model->getEmailType($model->id);
                $email['contains_action'] = $model->isContainsAction();
                $emails[] = $email;
            }

            $meta = $pagination->toJson()['meta'];
            $userInfo = Xcart::app()->user->getAttributes();
        } catch (Throwable $exception) {
            $this->jsonResponse(['error' => $exception->getMessage()]);
            return;
        }

        $this->jsonResponse(['objects' => $emails, 'meta' => $meta, 'userInfo' => $userInfo, 'labelList' => $this->actionGetLabels()]);
    }

    public function actionGetEmailInfo(string $id, $by_message_id = false)
    {
        $params = $by_message_id ? ['message_id' => $id] : ['id' => $id];
        /** @var EmailModel $email */
        $email = EmailModel::objects()->get($params);
        $emailInfo = $email->getAttributes();
        $emailInfo['viewed'] = $email->isViewed();
        $emailInfo['action'] = $email->getAction($email->id);
        $emailInfo['favorite'] = $email->isFavorite();
        $emailInfo['attachment'] = $email->getAttachment();
        $emailInfo['labels'] = $email->getLabels();
        $emailInfo['body'] = (string)$email->body;
        $emailInfo['labelList'] = $this->actionGetLabels();

        $this->jsonResponse($emailInfo);
    }


    public function editFavorite()
    {
        $favorite = json_decode(file_get_contents('php://input'));

        $isFavorite = $favorite->value;

        foreach ($favorite->itemsId as $item) {
            $favoriteItem = ['email_id' => $item, 'user_id' => Xcart::app()->user->id];

            if ($isFavorite) {
                EmailFavoriteModel::objects()->getOrCreate($favoriteItem);
                continue;
            }
            EmailFavoriteModel::objects()->delete($favoriteItem);

        }
        $this->jsonResponse('success');
    }

    public function editAction()
    {
        $action = json_decode(file_get_contents('php://input'));

        foreach ($action as $item) {

            $actionItem = ['email_id' => $item];
            $isActionTaken = EmailActionModel::objects()->filter($actionItem)->count() > 0;

            if ($isActionTaken) {
                EmailActionModel::objects()->delete($actionItem);
                $actionItem['user_id'] = Xcart::app()->user->id;
                $actionItem['action_value'] = true;
                EmailActionLogModel::objects()->create($actionItem);
                continue;
            }
            $actionItem['user_id'] = Xcart::app()->user->id;
            EmailActionModel::objects()->getOrCreate($actionItem);
            $actionItem['action_value'] = false;
            EmailActionLogModel::objects()->create($actionItem);
        }
        $this->jsonResponse('success');
    }

    public function setViewed()
    {
        $viewed = json_decode(file_get_contents('php://input'));

        $isEmailViewed = $viewed->value;

        foreach ($viewed->emailId as $view) {

            $actionItem = ['email_id' => $view, 'user_id' => Xcart::app()->user->id];

            if ($isEmailViewed) {
                EmailViewedModel::objects()->getOrCreate($actionItem);
                continue;
            }

            EmailViewedModel::objects()->delete($actionItem);

        }
        $this->jsonResponse('success');
    }

    public function actionGetTemplates()
    {
        $result = [];
        $allRoot = TemplateCategoryModel::objects()->filter(['level' => 1])->all();
        foreach ($allRoot as $root) {
            $categories = $root->getObjects()->descendants(true)->asTree()->all();
            $categories = $this->addTemplate($categories);
            $result[] = $categories;
        }
        $this->jsonResponse($result);
    }

    public function addTemplate($categories)
    {
        if ($categories === []) {
            return $categories;
        }
        foreach ($categories as $key => $category) {
            $categories[$key]['templates'] = TemplateModel::objects()->filter([
                'category_id' => $category['id'],
                'active' => 'Y'
            ])->order(['pos'])->asArray()->all();

            foreach ($categories[$key]['templates'] as $i => $template) {
                $categories[$key]['templates'][$i]['message_body'] = html_entity_decode($template['message_body']);
            }
            $categories[$key]['items'] = $this->addTemplate($category['items']);
        }
        return $categories;
    }

    public function actionSendEmail(): void
    {
        $files = self::diverse_array($_FILES['files']);
        $convert_files = [];
        foreach ($files as $file) {
            $convert_files[] = [
                'name' => $file['name'],
                'type' => $file['type'],
                'content' => base64_encode(file_get_contents($file['tmp_name'])),
            ];
        }
        $email = $this->getRequest()->post->all();
        $email['files'] = $convert_files;
        $email['user_id'] = Xcart::app()->user->id;

        $site = Xcart::app()->getModule('Sites')->getSelectedSite();
        $params = [
            'site' => $site,
            'user' => Xcart::app()->user,
        ];

        $email['body'] = SnippetHelper::render($email['body'], $params);

        Xcart::app()->queue->send('emails', json_encode($email, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), true);

        $this->jsonResponse('success');
    }

    public function getTemplate()
    {
        $template = TemplateModel::objects()->get([]);
    }

    public static function diverse_array($vector)
    {
        $result = array();
        foreach ($vector as $key1 => $value1) {
            foreach ($value1 as $key2 => $value2) {
                $result[$key2][$key1] = $value2;
            }
        }
        return $result;
    }

    public function actionRemoveMailLabel()
    {
        $userId = 'vr@s3stores.com';
        $data = json_decode(file_get_contents('php://input'));
        try {
            $client = GmailHelper::getClient($userId);
            $mailService = new \Google_Service_Gmail($client);
            $mods = new \Google_Service_Gmail_ModifyMessageRequest();
            $mods->setRemoveLabelIds($data->labelId);
            $mailService->users_messages->modify('me', $data->messageId, $mods);

            $label_model = EmailLabelModel::objects()->get(['label__label_id' => $data->labelId, 'email__message_id' => $data->messageId]);
            if ($label_model) {
                $label_model->delete();
            }
            $this->jsonResponse(['status' => true]);

        } catch (\Exception $exception) {
            $this->jsonResponse(['error' => $exception->getMessage()]);
        }

    }

    public function actionAddLabelMail()
    {
        $data = json_decode(file_get_contents('php://input'));
        try {
            $label_model = LabelModel::objects()->get(['label_id' => $data->labelId, 'type' => LabelModel::LABEL_TYPE_USER]);
            $message_model = EmailModel::objects()->get(['message_id' => $data->messageId]);
            $message_model->labels = array_merge($message_model->labels->all(), [$label_model]);
            $message_model->save();
            $this->actionGetEmailInfo($data->messageId, true);
        } catch (\Exception $exception) {
            $this->jsonResponse(['error' => $exception->getMessage()]);
        }
    }

    public function actionCreateMailLabel()
    {
        $userId = 'vr@s3stores.com';
        $data = json_decode(file_get_contents('php://input'));
        $client = GmailHelper::getClient($userId);
        $mailService = new \Google_Service_Gmail($client);
        $label = new \Google_Service_Gmail_Label();

        try {
            $label->setName($data->name);
            $label_color = new \Google_Service_Gmail_LabelColor();
            $label_color->setTextColor($data->color->color);
            $label_color->setBackgroundColor($data->color->background);
            $label->setColor($label_color);
            $label = $mailService->users_labels->create("me", $label);

            $label_model = new LabelModel();
            $label_model->label_id = $label->getId() ?? $data->name;
            $label_model->background_color = $data->color->background;
            $label_model->color = $data->color->color;
            $label_model->name = $data->name;
            $label_model->type = LabelModel::LABEL_TYPE_USER;
            $label_model->save();

            $mess = EmailModel::objects()->get(['message_id' => $data->messageId]);
            $mess->labels = array_merge($mess->labels->all(), [$label_model]);
            $mess->save();

            $mods = new \Google_Service_Gmail_ModifyMessageRequest();
            $mods->setAddLabelIds($label->getId());
            $message = $mailService->users_messages->modify("me", $data->messageId, $mods);
            $this->jsonResponse($label_model->getAttributes());
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()]);
        }

    }

    public function actionGetLabels(): array
    {
        $label_list = LabelModel::objects()->filter(['type' => LabelModel::LABEL_TYPE_USER])->order('name')->asArray(true)->all();
        return $label_list;
    }
}