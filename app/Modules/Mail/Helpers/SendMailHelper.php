<?php


namespace Modules\Mail\Helpers;


use Modules\Forms\Helpers\SnippetHelper;
use Modules\Forms\Models\TemplateModel;
use Modules\Order\Helpers\OrderTagEventHelper;
use Modules\Order\Models\OrderGroupModel;
use Modules\User\Models\UserModel;
use Xcart\App\Main\Xcart;

class SendMailHelper
{
    public static function sendTemplate(string $to, TemplateModel $template, OrderGroupModel $group): void
    {
        $order = $group->order;
        $site = $order->site;

        $params = [
            'order' => $order,
            'site' => $site,
            'user' => new UserModel(['firstname' => 'Amy']),
            'distributor' => $group->manufacturer,
        ];

        $message = SnippetHelper::render($template->message_body, $params);
        $subject = SnippetHelper::render($template->subject_line, $params);

        $config = $site->getGlobalConfig();
        $from = trim($config['orders_department']);

        $toa = array_unique(array_filter(array_map('trim', explode(',' , $to))));

        Xcart::app()->mail->raw(
            $toa,
            $subject,
            $message,
            [
                'from' => $from,
                'bcc' => ['romann@s3stores.com' => ''],
                'headers' => [
                    'X-Xcart-Label' => 'order-communication'
                ]
            ]
        );
        if ($tag = $template->status) {
            OrderTagEventHelper::orderTagEvent($tag->status_id, $order->orderid);
        }
    }
}