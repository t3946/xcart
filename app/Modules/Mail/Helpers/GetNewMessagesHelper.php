<?php


namespace Modules\Mail\Helpers;


use DateTime;
use Google_Service_Gmail;
use Modules\Forms\Models\EmailAttachmentModel;
use Modules\Forms\Models\EmailBodyModel;
use Modules\Forms\Models\EmailModel;
use Modules\Forms\Models\LabelModel;
use Modules\Mail\Helpers\GmailHelper;
use Xcart\App\Commands\Command;
use Xcart\App\Storage\Files\ResourceFile;

class GetNewMessagesHelper
{
    public static function getNewMessage($service, $userId, $message): void
    {
        /** @var EmailModel $model */
        [$model, $new] = EmailModel::objects()->getOrNew(['message_id' => $message->id]);

        if ($new && $single_message = GmailHelper::getMessage($service, $userId, $message->id)) {
            $body = GmailHelper::getBody($single_message);
            $headers = $single_message->getPayload()->getHeaders();
            $subject = GmailHelper::getHeader($headers, 'Subject');

            $internalDate = (new DateTime())->setTimestamp($single_message->getInternalDate() / 1000);

            $model->setAttributes(
                [
                    'account_id' => 1,
                    'subject' => GmailHelper::getHeader($headers, 'Subject'),
                    'thread_id' => $message->getThreadId(),
                    'snippet' => strip_tags(html_entity_decode($single_message->getSnippet())),
                    'type' => in_array('SENT', $single_message->getLabelIds(), true) ? 'sent' : 'inbox',
                    'delivered_to_address' => GmailHelper::getHeader($headers, 'Delivered-To'),
                    'to_address' => GmailHelper::getHeader($headers, 'To'),
                    'from_address' => GmailHelper::getHeader($headers, 'From'),
                    'original_sender' => GmailHelper::getHeader($headers, 'X-Original-Sender'),
                    'date' => $internalDate,
                    'labels' => LabelModel::objects()->all(['label_id__in' => $single_message->getLabelIds() ?? []]),
                    'reply_to' => GmailHelper::getHeader($headers, 'Reply-To'),
                ]
            );
            $model->save();

            if ($model->isChild() && $parent = $model->parent) {
                $labels_ids = array_map(static fn($l) => $l->label_id, $parent->labels->filter(['type' => LabelModel::LABEL_TYPE_USER]));
                if ($labels_ids) {
                    GmailHelper::updateMessage($service, $userId, $model->message_id, $labels_ids);
                }
            }

            [$emailModel, $isNew] = EmailBodyModel::objects()->getOrNew(['email_id' => $model->id]);

            if ($isNew) {
                $emailModel->email_body = new ResourceFile($body, "body{$model->id}.html");
                $emailModel->save();
            }

            foreach (GmailHelper::getAttachments($service, $single_message) as $attachment) {
                [$emailAttach, $isNew] = EmailAttachmentModel::objects()->getOrNew(
                    [
                        'email_id' => $model->id,
                        'filename' => $attachment['filename'],
                    ]
                );
                if ($isNew) {
                    $emailAttach->cid = $attachment['cid'];
                    try {
                        $emailAttach->attachment_content = new ResourceFile(
                            $attachment['data'], $attachment['filename']
                        );
                        $emailAttach->save();
                    } catch (\Throwable $e) {
                        echo $e->getMessage() . "\n";
                    }
                }
            }

            echo("{$message->id} : {$subject} {$single_message->getSnippet()} \n");
        }
    }
}