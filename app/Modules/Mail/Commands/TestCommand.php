<?php
namespace Modules\Mail\Commands;


use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class TestCommand extends Command
{
    public function handle($arguments = [])
    {
        try {
            $res = Xcart::app()->mail->template(
                'team@s3stores.com',
                'Test sending email',
                'mail/log_template.tpl',
                ['message' => "Email test: PASS"]
            );
        }
        catch (\Exception $e) {
            d($e);
        }

        dd($res);
    }
}