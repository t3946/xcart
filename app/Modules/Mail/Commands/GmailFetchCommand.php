<?php


namespace Modules\Mail\Commands;


use DateTime;
use Google_Service_Gmail;
use Modules\Forms\Models\EmailBodyModel;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\LabelModel;
use Modules\Mail\Helpers\GmailHelper;
use Xcart\App\Commands\Command;
use Xcart\App\Storage\Files\ResourceFile;

class GmailFetchCommand extends Command
{

    public function handle($arguments = [])
    {
        $userId = 'vr@s3stores.com';
        $client = GmailHelper::getClient($userId);

        $service = new Google_Service_Gmail($client);

        foreach ($labels = GmailHelper::listLabels($service, $userId) as $label) {
            [$labelModel] = LabelModel::objects()->getOrNew(['label_id' => $label->getId()]);
            $labelModel->setAttributes([
                'name' => $label->getName(),
                'background_color' => $label->getColor() ? $label->getColor()->getBackgroundColor() : '',
                'color' => $label->getColor() ? $label->getColor()->getTextColor() : '',
                'type' => $label->getType(),
            ]);
            $labelModel->save();
        }

        $messages = GmailHelper::listMessages($service, $userId);
        foreach ($messages as $message) {
            if ($single_message =  GmailHelper::getMessage($service, $userId, $message->id)) {
                $body = GmailHelper::getBody($single_message);
                $headers = $single_message->getPayload()->getHeaders();
                $subject = GmailHelper::getHeader($headers, 'Subject');

                $to = GmailHelper::getHeader($headers, 'To');

                $type = in_array('SENT', $single_message->getLabelIds(), true) ? 'sent' : 'inbox';

                $internalDate = (new DateTime())->setTimestamp($single_message->getInternalDate() / 1000);
                [$model, $new] = EmailModel::objects()->getOrNew([
                    'message_id' => $message->id,
                ]);

                if ($new) {
                    $model->setAttributes([
                        'account_id' => 1,
                        'subject' => $subject,
                        'thread_id' => $message->getThreadId(),
                        'snippet' => html_entity_decode($single_message->getSnippet()),
                        'type' => $type,
                        'delivered_to_address' => GmailHelper::getHeader($headers,'Delivered-To'),
                        'to_address' => $to,
                        'from_address' => GmailHelper::getHeader($headers, 'From'),
                        'date' => $internalDate,
                        'labels' => LabelModel::objects()->all(['label_id__in' => $single_message->getLabelIds() ?? []]),
                        'reply_to' => GmailHelper::getHeader($headers, 'Reply-To'),
                    ]);
                    $model->save();

                    [$body, $new] = EmailBodyModel::objects()->getOrNew([
                        'email_id' => $model->id,
                        'email_body' => new ResourceFile($body, "body{$model->id}.html")
                    ]);

                    $body->save();
                }

                echo("{$message->id} : {$subject} {$single_message->getSnippet()} \n");
            }
        }
    }
}