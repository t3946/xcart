<?php


namespace Modules\PBX\Commands;


use Modules\PBX\Helpers\AnveoAssignCalls;
use Xcart\App\Commands\Command;

class AssignCallsCommand extends Command
{

    public function handle($arguments = [])
    {
        AnveoAssignCalls::reValidate();
    }
}