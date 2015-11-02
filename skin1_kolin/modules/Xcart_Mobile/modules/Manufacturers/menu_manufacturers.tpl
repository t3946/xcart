{*
$Id: menu_manufacturers.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<div class="content-primary">
  <ul data-role="listview" data-theme="c" data-type="categories-list">
    {foreach from=$manufacturers_menu item=m}
      <li><a href="manufacturers.php?manufacturerid={$m.manufacturerid}">{$m.manufacturer|amp}</a></li>
    {/foreach}
    {if $show_other_manufacturers}
      <li data-icon="plus"><a href="manufacturers.php">{$lng.lbl_other_manufacturers}</a></li>
    {/if}
  </ul>
</div>