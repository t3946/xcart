<?php


namespace Modules\Mail\Commands;


use Google_Service_Gmail;
use Modules\Mail\Helpers\GmailHelper;
use Xcart\App\Commands\Command;
use Xcart\App\Helpers\Paths;

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
            $subject = GmailHelper::getHeader($single_message->getPayload()->getHeaders(), 'Subject');

            echo("{$message->id} : {$subject} {$single_message->getSnippet()} {$body} \n");
        }
    }
}