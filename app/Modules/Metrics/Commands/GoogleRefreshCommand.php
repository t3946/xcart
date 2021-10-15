<?php
namespace Modules\Metrics\Commands;
use Modules\Metrics\Helpers\GoogleAds;
use Xcart\App\Commands\Command;

class GoogleRefreshCommand extends Command
{

    public function handle($arguments = [])
    {
        GoogleAds::generateRefreshToken();
    }
}