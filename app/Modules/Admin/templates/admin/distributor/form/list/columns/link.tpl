{set $val}{include 'admin/list/columns/default.tpl'}{/set}
<a href="{url 'admin:list_nested' params=['id' => $item->pk, 'admin' => $adminClass, 'module' => $moduleClass]}">{$val}</a>