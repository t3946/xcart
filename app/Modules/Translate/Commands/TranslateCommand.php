<?php


namespace Modules\Translate\Commands;

use Modules\Translate\Helpers\TranslateHelper;
use Xcart\App\Commands\Command;

class TranslateCommand extends Command
{

    public function handle($arguments = [])
    {
        (new TranslateHelper)->collect();
    }
}