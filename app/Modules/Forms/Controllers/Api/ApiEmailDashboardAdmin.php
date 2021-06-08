<?php


namespace Modules\Forms\Controllers\Api;


use Modules\Forms\Models\EmailActionLogModel;
use Modules\Forms\Models\EmailActionModel;
use Modules\Forms\Models\EmailFavoriteModel;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\EmailViewedModel;
use Modules\Forms\Models\TemplateCategoryModel;
use Modules\Forms\Models\TemplateModel;
use Modules\Help\Models\HelpListModel;
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


        if($searchParams->hasAttachment)
        {
            $actionItem =   array_merge($actionItem, ['attachments__attachment__isnull' => false]);
            $actionItem =   array_merge($actionItem, ['attachments__cid__isnull' => true]);
        }

        if($searchParams->from)
        {
            $actionItem =  array_merge($actionItem, ['from_address__contains' => $searchParams->from]);
        }

        if($searchParams->to)
        {
            $actionItem =  array_merge($actionItem, ['to_address__contains' => $searchParams->to]);
        }

        if($searchParams->subject)
        {
            $actionItem =   array_merge($actionItem, ['subject__contains' => $searchParams->subject]);
        }
        if($searchParams->dateAfter)
        {
            $actionItem =   array_merge($actionItem, ['date__gte' => $searchParams->dateAfter]);
        }
        if($searchParams->dateBefore)
        {
            $actionItem =   array_merge($actionItem, ['date__lte' => $searchParams->dateBefore]);
        }

        $qs = EmailModel::objects()->getQuerySet()->filter($actionItem)->order(['-id']);

        $pagination = new Pagination($qs, [
            'page' => $page,
            'pageSize' => 20,
        ], new QuerySetDataSource());

        try {
            foreach ($pagination->paginate() as $model)
            {
                /** @var EmailModel $model */
                $email = $model->getAttributes();
                $email['viewed'] = $model->isViewed();
                $email['action'] = $model->getAction($model->id);
                $email['favorite'] = $model->isFavorite();
                $email['attachment'] = $model->getAttachment();
                $email['body'] = (string)$model->body;
                $emails[] = $email;
            }

            $meta = $pagination->toJson()['meta'];
            $userInfo = Xcart::app()->user->getAttributes();
        } catch (Throwable $exception) {
            $this->jsonResponse(['error' => $exception->getMessage()]);
            return;
        }

        $this->jsonResponse(['objects' => $emails, 'meta' => $meta, 'userInfo'=>$userInfo]);
    }

    public function actionGetEmailInfo(string $id)
    {
        $email =  EmailModel::objects()->filter(['id' => $id])[0];
        $emailInfo = $email->getAttributes();
        $emailInfo['viewed'] = $email->isViewed();
        $emailInfo['action'] = $email->getAction($email->id);
        $emailInfo['favorite'] = $email->isFavorite();
        $emailInfo['attachment'] = $email->getAttachment();
        $emailInfo['body'] = (string)$email->body;

        $this->jsonResponse($emailInfo);
    }



    public function editFavorite()
    {
        $favorite = json_decode(file_get_contents('php://input'));

        $isFavorite =  $favorite->value;

        foreach ($favorite->itemsId as $item) {
            $favoriteItem = ['email_id' => $item, 'user_id' => Xcart::app()->user->id];

            if($isFavorite)
            {
                EmailFavoriteModel::objects()->getOrCreate( $favoriteItem);
                continue;
            }
            EmailFavoriteModel::objects()->delete( $favoriteItem);

        }
        $this->jsonResponse('success');
    }

    public function editAction()
    {
        $action = json_decode(file_get_contents('php://input'));

        foreach ($action as $item) {

            $actionItem = ['email_id' => $item];
            $isActionTaken = EmailActionModel::objects()->filter($actionItem)->count() > 0;

            if($isActionTaken)
            {
                EmailActionModel::objects()->delete( $actionItem);
                $actionItem[ 'user_id' ] = Xcart::app()->user->id;
                $actionItem['action_value'] = true;
                EmailActionLogModel::objects()->getOrCreate( $actionItem);
                continue;
            }
            $actionItem[ 'user_id' ] = Xcart::app()->user->id;
            EmailActionModel::objects()->getOrCreate( $actionItem);
            $actionItem['action_value'] = false;
            EmailActionLogModel::objects()->getOrCreate( $actionItem);
        }
        $this->jsonResponse('success');
    }

    public function setViewed()
    {
        $viewed = json_decode(file_get_contents('php://input'));

        $isEmailViewed = $viewed->value;

        foreach ($viewed->emailId as $view) {

            $actionItem = ['email_id' => $view, 'user_id' => Xcart::app()->user->id];

            if($isEmailViewed)
            {
                EmailViewedModel::objects()->getOrCreate( $actionItem);
                continue;
            }

            EmailViewedModel::objects()->delete( $actionItem);




        }
        $this->jsonResponse('success');
    }

    public function actionGetTemplates()
    {
        $result =[];
        $allRoot = TemplateCategoryModel::objects()->filter(['level' => 1])->all();
        foreach ($allRoot as $root) {
            $categories = $root->getObjects()->descendants(true)->asTree()->all();
            $categories =   $this->addTemplate($categories);
            $result[] = $categories;
        }
        $this->jsonResponse($result);
    }

    public function addTemplate($categories)
    {
        $result = [];
        if($categories === []){
            return $categories;
        }
        foreach ($categories as $key => $category) {
            $categories[$key]['templates'] = TemplateModel::objects()->filter([
                'category_id' => $category['id'],
                'active' => 'Y'
            ])->order(['pos'])->asArray()->all();

            foreach (   $categories[$key]['templates'] as $i => $template){
                $categories[$key]['templates'][$i]['message_body'] = html_entity_decode($template['message_body']);
            }
            $categories[$key]['items'] = $this->addTemplate($category['items']);
        }
        return $result = $categories;
    }

    public function actionSendEmail()
    {
        $email = file_get_contents('php://input');

        Xcart::app()->queue->send('emails', $email);

        $this->jsonResponse('success');
    }

    public function getTemplate()
    {
        $template = TemplateModel::objects()->get([]);
    }


}