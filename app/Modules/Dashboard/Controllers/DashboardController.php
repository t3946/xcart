<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 12.01.2017
 * Time: 17:34
 */

namespace Modules\Dashboard\Controllers;

use Xcart\App\Controller\AdminController;
use Xcart\App\Main\Xcart;
use Xcart\Connection;

class DashboardController extends AdminController
{
    public $defaultAction = 'index';

    public function index()
    {
        func_dump(Connection::getInstance()->fetchAssoc('select 1 as temp')) ;
        echo 123;
    }
}