<?php

namespace Modules\Help\Controllers\Api;

use Xcart\App\Controller\Controller;
use Modules\Help\Models\HelpListModel;

class ApiHelpController extends Controller
{
    public function actionGetHelpItems()
    {
     foreach(HelpListModel::objects() as $menu)
     { 
        $menus[] = array_merge(
            $menu->getAttributes(), [
            'items'=>$menu->menu_items->asArray()->all()
        ]);
     }

     $this->jsonResponse($menus ?? []);
    }
}