<?php
namespace Modules\Mail\Commands;


use Modules\Goods\Models\ProductModel;
use Xcart\App\Commands\Command;
use Xcart\App\Main\Xcart;

class TestCommand extends Command
{
    public function handle($arguments = [])
    {
        $product = ProductModel::objects()->get(['productid' => 1284916]);
        if ($brand = $product->brand) {
            foreach ($brand->markets_disabled as $dm) {
                if ($dm->marketplace_id == 3) {
                    $prevent_selling = 'MFN';
                }
            }
        }
        echo $prevent_selling; die();

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

    public function exception($arguments = [])
    {
        throw new \Exception();
    }
}