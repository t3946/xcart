<?php


namespace Modules\Forms\Commands;


use Google\Model;
use Google_Http_MediaFileUpload;
use Google_Service_Gmail;
use Google_Service_Gmail_Draft;
use Google_Service_Gmail_Message;
use JsonException;
use Mail_mime;
use Modules\Goods\Models\ProductModel;
use Modules\Mail\Helpers\GetNewMessagesHelper;
use Modules\Mail\Helpers\GmailHelper;
use PhpAmqpLib\Message\AMQPMessage;
use Xcart\App\Commands\Command;
use Xcart\App\Exceptions\Exception;
use Xcart\App\Main\Xcart;

class QueueProcessCommand extends Command
{
    public function handle($arguments = [])
    {
        Xcart::app()->queue->consume('emails', [$this, 'consume']);
    }


    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product  */
        /** @var ProductModel $group_product  */
        try {
            if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)) {
                if( $data['date'] > time() && $data['date'] !== null)
                {
                    echo ('nack');
                    $message->nack();
                    return;
                }

                $userId = 'vr@s3stores.com';
                $client = GmailHelper::getClient($userId);

                $mailService = new Google_Service_Gmail($client);
                $googleMessage = new Google_Service_Gmail_Message;

                $recipients = implode(',', $data['to']);

                $mime = new Mail_mime(['head_charset'=>'UTF-8', 'html_charset'=>'UTF-8','text_charset'=>'UTF-8']);
                $mime->addTo($recipients);
                $mime->setFrom($userId);
                $mime->setHTMLBody($data['body']);
                $mime->setSubject($data['subject']);
                foreach ($data['files'] as $file) {
                    $mime->addAttachment(base64_decode($file['content']), $file['type'], $file['name'], false);
                }

                $mailMessage = base64_encode($mime->getMessage());
                $googleMessage->setRaw($mailMessage);

                $request =  $mailService->users_messages->send(
                    $userId,
                    $googleMessage,
                );
                $message->ack();
                GetNewMessagesHelper::getNewMessages($mailService,$userId, $request);
            }
        } catch (JsonException $e)
        {
            $message->ack();
        }




    }


}