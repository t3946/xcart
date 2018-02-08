{*
$Id: wishlist.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}

{include file="modules/Wishlist/wl_products.tpl"}

{if $active_modules.Gift_Registry}
  <br />
  <div class="ui-body ui-body-b">
  {include file="modules/Gift_Registry/events_list.tpl" is_internal=true}
  </div>
{/if}
