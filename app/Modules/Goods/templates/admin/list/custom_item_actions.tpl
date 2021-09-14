{var $actions = $admin->getListItemActions()}

{if "update" in $actions}
    <a {if $admin->isAjaxUpdate()}class="ajax"{/if} href="{$admin->getUpdateUrl($pk)}">
        <i class="icon-edit"></i>
    </a>
{/if}

{if ("view" in $actions) && $.php.method_exists($item, 'getAbsoluteUrl')}
    <a href="{$item->getAbsoluteUrl()}">
        <i class="icon-search_mark"></i>
    </a>
{/if}

{if "image" in $actions}
    <a class="ajax" title="Update images" href="{url 'api:updateImages' params=['id' => $pk]}">
        <i class="icon-upload_image"></i>
    </a>
{/if}

{if "remove" in $actions}
    <a href="{$admin->getRemoveUrl($pk)}" data-prevention data-title="Are You Sure?" data-trigger="list-update">
        <i class="icon-delete_in_table"></i>
    </a>
{/if}