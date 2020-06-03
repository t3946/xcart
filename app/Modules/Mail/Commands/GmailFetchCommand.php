<?php


namespace Modules\Mail\Commands;


use DateTime;
use Google_Service_Gmail;
use Modules\Forms\Models\EmailModel;
use Modules\Mail\Helpers\GmailHelper;
use Xcart\App\Commands\Command;

class GmailFetchCommand extends Command
{

    public function handle($arguments = [])
    {
        $userId = 'vr@s3stores.com';
        $client = GmailHelper::getClient($userId);

        $service = new Google_Service_Gmail($client);

        $messages = GmailHelper::listMessages($service, $userId);
        foreach ($messages as $message) {
            if ($single_message =  GmailHelper::getMessage($service, $userId, $message->id)) {
                $body = GmailHelper::getBody($single_message);
                $headers = $single_message->getPayload()->getHeaders();
                $subject = GmailHelper::getHeader($headers, 'Subject');

                $to = GmailHelper::getHeader($headers, 'To');

                $type = in_array('SENT', $single_message->getLabelIds(), true) ? 'sent' : 'inbox';

                $internalDate = (new DateTime())->setTimestamp($single_message->getInternalDate() / 1000);

                EmailModel::objects()->getOrCreate([
                    'message_id' => $message->id,
                    'body' => $body,
                    'subject' => $subject,
                    'snippet' => html_entity_decode($single_message->getSnippet()),
                    'type' => $type,
                    'delivered_to_address' => GmailHelper::getHeader($headers,'Delivered-To'),
                    'to_address' => $to,
                    'from_address' => GmailHelper::getHeader($headers, 'From'),
                    'date' => $internalDate
                    /*'reply_to' => GmailHelper::getHeader($headers, 'Reply-To'),*/
                ]);

                echo("{$message->id} : {$subject} {$single_message->getSnippet()} {$body} \n");
            }
        }
    }
}