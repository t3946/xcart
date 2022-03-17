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
         $menu_items = [];

         foreach ($menu->menu_items as $items) {
             $menu_item = $items->getAttributes();
             $menu_item['answer'] = html_entity_decode($items->answer);
             $menu_items[] = $menu_item;
         }

         $menu_base = $menu->getAttributes();
         $menu_base['icon'] = $menu->icon->getUrl();
         $menu_base['active_icon'] = $menu->active_icon->getUrl();

        $menus[] = array_merge(
            $menu_base, [
            'items' => $menu_items
        ]);
     }

     $this->jsonResponse($menus ?? []);
    }
}