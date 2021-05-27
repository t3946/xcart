<?php


namespace Modules\Forms\Controllers\Api;


use Modules\Forms\Models\EmailActionModel;
use Modules\Forms\Models\EmailFavoriteModel;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\EmailViewedModel;
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

        $qs = EmailModel::objects()->getQuerySet()->order(['-id']);

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
                $email['action'] = $model->getAction();
                $email['favorite'] = $model->isFavorite();
                $email['body'] = (string)$model->body;
                $emails[] = $email;
            }

            $meta = $pagination->toJson()['meta'];
        } catch (Throwable $exception) {
            $this->jsonResponse(['error' => $exception->getMessage()]);
            return;
        }

        $this->jsonResponse(['objects' => $emails, 'meta' => $meta]);
    }

    public function editFavorite()
    {
        $favorite = json_decode(file_get_contents('php://input'));

        foreach ($favorite as $item) {
            $favoriteItem = ['email_id' => $item, 'user_id' => Xcart::app()->user->id];
            $isFavorite = EmailFavoriteModel::objects()->filter($favoriteItem)->count() > 0;

            if($isFavorite)
            {
                EmailFavoriteModel::objects()->delete( $favoriteItem);
                continue;
            }
            EmailFavoriteModel::objects()->getOrCreate( $favoriteItem);
        }
        $this->jsonResponse('success');
    }

    public function editAction()
    {
        $action = json_decode(file_get_contents('php://input'));

        foreach ($action as $item) {

            $actionItem = ['email_id' => $item, 'user_id' => Xcart::app()->user->id];
            $isActionTaken = EmailActionModel::objects()->filter($actionItem)->count() > 0;

            if($isActionTaken)
            {
                EmailActionModel::objects()->delete( $actionItem);
                continue;
            }
            EmailActionModel::objects()->getOrCreate( $actionItem);
        }
        $this->jsonResponse('success');
    }

    public function setViewed()
    {
        $viewed = json_decode(file_get_contents('php://input'));
        

        $actionItem = ['email_id' => $viewed, 'user_id' => Xcart::app()->user->id];
        EmailViewedModel::objects()->getOrCreate( $actionItem);
        $this->jsonResponse('success');
    }
}