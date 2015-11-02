{* menu_brands.tpl, random *}
{if $brands_menu ne ''}
{capture name=menu}
{section name=mid loop=$brands_menu}
<a href="brands.php?brandid={$brands_menu[mid].brandid}" class="VertMenuItems">{$brands_menu[mid].brand}</a><br />
{/section}
{if $show_other_brands}
<br />
<a href="brands.php" class="VertMenuItems">{$lng.lbl_other_brands}</a><br />
{/if}
{/capture}
{include file="menu.tpl" dingbats="dingbats_categorie.gif" menu_title=$lng.lbl_brands menu_content=$smarty.capture.menu}
<br />
{/if}
