<?php
namespace Modules\Forms;

use Modules\Admin\Traits\AdminTrait;
use Xcart\App\Module\Module;

class FormsModule extends Module
{
    use AdminTrait;

    public static function getVerboseName()
    {
        return 'Emails';
    }

}