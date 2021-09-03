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
use Modules\User\Models\UserModel;
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
        $action = 'ack';
        try {
            if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)) {
                if (!$data['to']) {
                    return;
                }

                if($data['date'] !== null && $data['date'] > time())
                {
                    echo ('nack');
                    $action = 'nack';
                    return;
                }

                $userId = 'vr@s3stores.com';
                $client = GmailHelper::getClient($userId);

                $mailService = new Google_Service_Gmail($client);
                $googleMessage = new Google_Service_Gmail_Message();

                $recipients = implode(',', $data['to']);

                $mime = new Mail_mime(['head_charset'=>'UTF-8', 'html_charset'=>'UTF-8','text_charset'=>'UTF-8']);
                $mime->addTo($recipients);
                $mime->setFrom($userId);
                $mime->setHTMLBody($data['body']);
                $mime->setSubject($data['subject']);
                foreach ($data['files'] as $file) {
                    $mime->addAttachment(base64_decode($file['content']), $file['type'], $file['name'], false);
                }

                /** @var UserModel $user */
                $user = UserModel::objects()->get(['id' => $data['user_id']]);

                $mailMessage = base64_encode(
                    $mime->getMessage(
                        null,
                        null,
                        $user->email ? [
                            'Sender' => $user->email,
                            'X-Google-Sender-Delegation' => $user->email,
                            'X-Original-Sender' => $user->email
                        ] : null
                    )
                );

                $googleMessage->setRaw($mailMessage);
                if (!empty($data['threadId'])) {
                    $googleMessage->setThreadId($data['threadId']);
                }
                $request =  $mailService->users_messages->send(
                    $userId,
                    $googleMessage,
                );

                $label_id = GmailHelper::getOrCreateLabel($mailService, $userId, $user->getShortSurname());
                if ($label_id) {
                    GmailHelper::updateMessage($mailService, $userId, $request->id, [$label_id]);
                }

                GetNewMessagesHelper::getNewMessage($mailService, $userId, $request);
            }
        } catch (JsonException $e)
        {
            echo "Error:{$e->getMessage()}\n";
        } finally {
            $message->$action();
        }
    }
}