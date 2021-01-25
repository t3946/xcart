<?php


namespace Modules\Order\Controllers\Admin;


use Modules\Core\Models\GlobalConfigModel;
use Xcart\App\Controller\Controller;

class OrderRelatedMessagesController extends Controller
{
    public function actionSetOrderNoteTag()
    {
        if ($tag = $this->getRequest()->post->get('order_note_tag')) {
            GlobalConfigModel::objects()->updateOrCreate(['name' => 'order_note_tag'], ['value' => $tag]);
        } else {
            GlobalConfigModel::objects()->updateOrCreate(['name' => 'order_note_tag'], ['value' => '']);
        }
        if ($users = $this->getRequest()->post->get('order_note_tag_users')) {
            GlobalConfigModel::objects()->updateOrCreate(['name' => 'order_note_tag_users'], ['value' => implode(',', $users)]);
        } else {
            GlobalConfigModel::objects()->updateOrCreate(['name' => 'order_note_tag_users'], ['value' => '']);
        }
        $this->redirect('/admin/configuration.php?option=Attention_tags_invoices');
    }
}