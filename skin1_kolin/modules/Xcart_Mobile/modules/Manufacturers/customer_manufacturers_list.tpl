{*
$Id: customer_manufacturers_list.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<h1>{$lng.lbl_manufacturers}</h1>
{capture name=dialog}
  {include file="customer/main/navigation.tpl" assign="manufacturers_nav"}
  {$manufacturers_nav}
  <ul data-role="listview" class="manufacturers-list list-item">
    {foreach from=$manufacturers item=v}
      <li><a href="manufacturers.php?manufacturerid={$v.manufacturerid|amp}">{$v.manufacturer|escape}</a></li>
    {/foreach}
  </ul>
  {$manufacturers_nav}
{/capture}
{include file="customer/dialog.tpl" title=$lng.lbl_manufacturers content=$smarty.capture.dialog noborder=true}
