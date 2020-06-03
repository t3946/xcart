<?php


namespace Modules\Mail\Commands;


use Google_Service_Gmail;
use Modules\Forms\Models\EmailModel;
use Modules\Mail\Helpers\GmailHelper;
use Xcart\App\Commands\Command;

class GmailFetchCommand extends Command
{

    public function handle($arguments = [])
    {
        $userId = 'vrtest@s3stores.com';
        $client = GmailHelper::getClient('vrtest@s3stores.com');

        $service = new Google_Service_Gmail($client);

        $messages = GmailHelper::listMessages($service, $userId);
        foreach ($messages as $message) {
            $single_message =  GmailHelper::getMessage($service, $userId, $message->id);
            $body = GmailHelper::getBody($single_message);
            $headers = $single_message->getPayload()->getHeaders();
            $subject = GmailHelper::getHeader($headers, 'Subject');
            $type = in_array('INBOX', $single_message->getLabelIds()) ? 'inbox' : 'sent';
            EmailModel::objects()->getOrCreate([
                'message_id' => $message->id,
                'body' => $body,
                'subject' => $subject,
                'snippet' => $single_message->getSnippet(),
                'type' => $type,
                'to_address' => GmailHelper::getHeader($headers, 'To'),
                'from_address' => GmailHelper::getHeader($headers, 'From'),
                /*'reply_to' => GmailHelper::getHeader($headers, 'Reply-To'),*/
            ]);

            echo("{$message->id} : {$subject} {$single_message->getSnippet()} {$body} \n");
        }
    }
}