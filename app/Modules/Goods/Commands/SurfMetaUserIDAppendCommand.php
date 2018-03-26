<?php
namespace Modules\Goods\Commands;

use Dariuszp\CliProgressBar;
use Modules\Goods\Models\CategoryModel;
use Modules\User\Models\SessionDataModel;
use Xcart\App\Commands\Command;

class SurfMetaUserIDAppendCommand extends Command
{
    private function getSessions()
    {
        SessionDataModel::objects()->filter(['data__contains' => 'login'])
    }

    public function handle($arguments = [])
    {
        $i = 0;

        /** @var SessionDataModel $session */
        foreach ($this->getDatas() as $session) {

        }
    }
}