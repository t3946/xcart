<?php


namespace Modules\Faxes\Commands;


use DateTime;
use DateTimeZone;
use GuzzleHttp\Client;
use Modules\Faxes\Models\FaxModel;
use Modules\Forms\Models\EmailAttachmentModel;
use Modules\Forms\Models\EmailBodyModel;
use Modules\Forms\Models\EmailModel;
use Xcart\App\Commands\Command;
use Xcart\App\Storage\Files\ResourceFile;

class FaxageCommand extends Command
{
    public const FAXAGE_URI = 'https://api.faxage.com/httpsfax.php';
    public const FAXAGE_USERNAME = 'vwf100';
    public const FAXAGE_COMPANY = '1309';
    public const FAXAGE_PASSWORD = '4yXjxBqRQbv62c';
    public const FAXAGE_LIST_OPERATION = 'listfax';
    public const FAXAGE_GET_OPERATION = 'getfax';

    public function handle($arguments = [])
    {
        $client = new Client();
        $response = $client->post(self::FAXAGE_URI, ['form_params' => [
            'username' => self::FAXAGE_USERNAME,
            'company' => self::FAXAGE_COMPANY,
            'password' => self::FAXAGE_PASSWORD,
            'operation' => self::FAXAGE_LIST_OPERATION,
            'begin' => '2020-01-01 00:00:00',
            'pagecount' => true,
            'filename' => true,
            'idasc' => true,
        ]]);

        if ($lines = explode("\n", (string)$response->getBody())) {
            foreach ($lines as $line) {
                if ($fax = array_combine(['fax_id', 'date', 'from', 'to', 'filename', 'pagecount'], explode("\t", $line))) {
                    $date = DateTime::createFromFormat('Y-m-d H:i:s', $fax['date'], new DateTimeZone('EST'));
                    [$faxModel, $isNew] = FaxModel::objects()->getOrCreate([
                        'fax_id' => $fax['fax_id'],
                        'fax_date' => $date,
                        'fax_from' => $fax['from'],
                        'fax_to' => $fax['to'],
                        'filename' => $fax['filename'],
                        'pagecount' => $fax['pagecount']
                    ]);
                    if ($isNew) {
                        echo "New fax from {$faxModel->fax_from} to {$faxModel->fax_to}" . PHP_EOL;
                        $response = $client->post(self::FAXAGE_URI, ['form_params' => [
                            'username' => self::FAXAGE_USERNAME,
                            'company' => self::FAXAGE_COMPANY,
                            'password' => self::FAXAGE_PASSWORD,
                            'operation' => self::FAXAGE_GET_OPERATION,
                            'faxid' => $faxModel->fax_id,
                        ]]);
                        //$faxModel->path = new ResourceFile((string)$response->getBody(), $faxModel->filename);
                        //$faxModel->save();

                        [$model, $new] = EmailModel::objects()->getOrNew([
                            'message_id' => "fax:{$faxModel->id}",
                        ]);
                        if ($new) {
                            $model->setAttributes([
                                'thread_id' => $model->message_id,
                                'from_address' => 'FAXAGE <support@faxage.com>',
                                'to_address' => 'faxage800@s3stores.com',
                                'date' => $date,
                                'type' => 'inbox',
                                'account_id' => 1,
                                'subject' => "FAXAGE - New {$faxModel->pagecount} page fax received from {$faxModel->fax_from} {$date->format('Y-m-d H:i:s')}",
                            ]);
                            $model->save();

                            [$bodyModel, $isNew] = EmailBodyModel::objects()->getOrNew([
                                'email_id' => $model->id,
                            ]);
                            $body = <<<HTML
Dear Incoming Line {$faxModel->fax_to}:

You have received a new {$faxModel->pagecount} page fax on FAXAGE from {$faxModel->fax_from}. A copy is attached for your
reference. You may also visit http://www.faxage.com to log in and work with your faxes.

Thank you for using FAXAGE,
FAXAGE Support Team
support@faxage.com
(303)991-6020
HTML;

                            if ($isNew) {
                                $bodyModel->email_body = new ResourceFile(nl2br($body), "body{$model->id}.html");
                                $bodyModel->save();
                            }

                            [$emailAttach, $isNew] = EmailAttachmentModel::objects()->getOrNew([
                                'email_id' => $model->id,
                                'filename' => $faxModel->filename,
                            ]);
                            if ($isNew) {
                                $emailAttach->attachment_content = new ResourceFile((string)$response->getBody(), $faxModel->filename);
                                $emailAttach->save();
                            }
                        }
                    }
                }
            }
        }
    }
}