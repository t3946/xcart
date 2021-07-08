<?php


namespace Modules\Mail\Commands;


use Google_Service_Gmail;
use Modules\Forms\Models\LabelModel;
use Modules\Mail\Helpers\GetNewMessagesHelper;
use Modules\Mail\Helpers\GmailHelper;
use Xcart\App\Commands\Command;

class GmailFetchCommand extends Command
{

    public function handle($arguments = [])
    {
        $userId = 'vr@s3stores.com';
        $client = GmailHelper::getClient($userId);

        $service = new Google_Service_Gmail($client);

        GmailHelper::fetchLabels($service, $userId);

        foreach (GmailHelper::listMessages($service, $userId) as $message) {
            GetNewMessagesHelper::getNewMessage($service, $userId, $message);
        }
    }
}