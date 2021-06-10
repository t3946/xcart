<?php


namespace Modules\Forms\Commands;


use Google\Model;
use Google_Http_MediaFileUpload;
use Google_Service_Gmail;
use Google_Service_Gmail_Draft;
use Google_Service_Gmail_Message;
use Mail_mime;
use Modules\Goods\Models\ProductModel;
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

//   public function createMessage($sender, $to, $subject, $messageText) {
//        $message = new Google_Service_Gmail_Message();
//        $rawMessageString = "From: <{$sender}>\r\n";
//        $rawMessageString .= "To: <{$to}>\r\n";
//        $rawMessageString .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
//        $rawMessageString .= "MIME-Version: 1.0\r\n";
//        $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n";
//        $rawMessageString .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
//        $rawMessageString .= "{$messageText}\r\n";
//        $rawMessage = strtr(base64_encode($rawMessageString), array('+' => '-', '/' => '_'));
//        $message->setRaw($rawMessage);
//        return $message;
//    }

    public function consume(AMQPMessage $message): void
    {
        /** @var ProductModel $product  */
        /** @var ProductModel $group_product  */

        if ($data = json_decode($message->body, true, 512, JSON_THROW_ON_ERROR)) {
            $userId = 'vr@s3stores.com';
            $client = GmailHelper::getClient($userId);

            $mailService = new Google_Service_Gmail($client);
            $googleMessage = new Google_Service_Gmail_Message;

            $data = $data['email'];

            $recipients = implode(',', $data['to']);

            $mime = new Mail_mime;
            $mime->addTo($recipients);
            $mime->setHTMLBody($data['body']);
            $mime->setSubject($data['subject']);
            foreach ($data['files'] as $file) {
                $mime_type = 'application/pdf'; // тут нужно определить mime type файла
                $mime->addAttachment($file['content'], $mime_type, $file['name'], false);
            }

            $mailMessage = base64_encode($mime->getMessage());
            $googleMessage->setRaw($mailMessage);

            $mailService->users_messages->send(
                $userId,
                $googleMessage,
            );
        }
        $message->ack();
    }


}