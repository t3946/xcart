{*
$Id: search_sort_by.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $products|@count gt 1 && $sort_fields && ($url || $navigation_script)}
  <div class="ui-select">
    <div class="ui-btn ui-btn-corner-all ui-shadow ui-btn-up-b">
      <span class="ui-btn-inner ui-btn-corner-all">
        <span class="ui-btn-text">{$lng.lbl_sort_by}</span>
      </span>
      {func_mobile_prepare_sort_fields fields=$sort_fields assign="prepared_fields"}
      <select data-role="none" class="select-sort" onchange="javascript: $.mobile.changePage('{$current_location}/'+this.value);">
        {foreach from=$prepared_fields key=field item=name}
          <option value="{$field}"{if $field|has_string:$navigation_script} selected="selected"{/if}>{$name}</option>
        {/foreach}
      </select>
    </div>
  </div>
{/if}
