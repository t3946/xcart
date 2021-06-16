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
            GetNewMessagesHelper::getNewMessages($service,$userId,$message);
        }
    }
}