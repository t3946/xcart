<?php

namespace Modules\Mail\Commands;

use Goutte\Client;
use GuzzleHttp\Client as ClientAlias;
use Modules\Mail\Models\MailboxForwardingModel;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;
use Xcart\App\Storage\Files\RemoteFile;

class MailboxForwardingCommand extends Command
{

    public function handle($arguments = [])
    {
        try {
            $client = new Client();
            $guzzle_client = new ClientAlias(['verify' => false, 'cookies' => true]);
            $client->setClient($guzzle_client);
            $crawler = $client->request('GET', 'https://www.mailboxforwarding.com/manage/login.php');
            $form = $crawler->selectButton('Login')->form();

            $crawler = $client->submit($form, [
                'email' => 'mailboxforwarding@s3stores.com',
                'password' => 'PLFPfB5Cy7WDnc9R'
            ]);

            if (strpos($crawler->html(), 'logout.php') !== false) {
                $session_id = $client->getCookieJar()->get('PHPSESSID')->getValue();
                preg_match("~dummyData\s*=\s*(\[.*?);\s*</script>~s", $crawler->html(), $content);
                $text = $content[1];
                $text = str_replace(['"', "'"], ['\"', '"'], $text);
                $ar_elements = json_decode($text, true, 512, JSON_THROW_ON_ERROR);

                foreach ($ar_elements as $elements) {
                    $mail = null;
                    foreach ($elements as $key => $element) {
                        if (empty($element) || ($mail && !$mail->getIsNewRecord() && in_array($key, [1, 2], true))) {
                            continue;
                        }
                        $el_crawler = new Crawler($element);
                        switch ($key) {
                            case 0:
                                $mail_id = (int)$el_crawler->filter('body > p > input')->attr('value');
                                if (!$mail = MailboxForwardingModel::objects()->get(['unique_id' => $mail_id])) {
                                    $mail = new MailboxForwardingModel();
                                }
                                $mail->unique_id = $mail_id;
                                break;
                            case 1:
                                $mail->date = $el_crawler->filter('body > p')->attr('id');
                                break;
                            case 2:
                                $image_path = $el_crawler->filter('body > img')->attr('src');
                                $remote_file = new RemoteFile($image_path);
                                $mail->image_path = $remote_file;
                                break;
                            case 3:
                                $el_html = $el_crawler->filter('body > p')->html();
                                preg_match_all('~<b>(?<value>.*?)</*br*>~', $el_html, $all);
                                $weight = preg_replace('/[^0-9]/', '', $all['value'][1]);
                                if (!empty($weight)) {
                                    $mail->weight = (float)$weight;
                                }
                                $mail->type = $all['value'][0];
                                $mail->status = $all['value'][2];
                                break;
                            case 4:
                                $attr_href = $el_crawler->filter('body > p > a')->attr('href');
                                $pdf_file = new RemoteFile("https://www.mailboxforwarding.com/manage/$attr_href", "$mail_id.pdf", [
                                    'headers' => ['Cookie' => "PHPSESSID=$session_id"]
                                ]);
                                $mail->file = $pdf_file;
                                break;
                        }
                    }
                    $mail->source = 'auto';
//                    $mail->save();
                }
            }
        } catch (Throwable $e) {
            Xcart::app()->logger->error('Error parse mailbox', [$e->getMessage(), $e->getFile(), $e->getLine()], 'mailbox_parse');
        }
    }
}