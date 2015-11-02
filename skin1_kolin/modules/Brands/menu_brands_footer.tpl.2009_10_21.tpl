{* menu_brands_footer.tpl, random *}
{if $brands_menu ne ''}
{strip}
<strong>{$lng.lbl_brands}</strong>
{section name=mid loop=$brands_menu}
&nbsp;::&nbsp;<a href="brands.php?brandid={$brands_menu[mid].brandid}" class="NavigationPath">{$brands_menu[mid].brand}</a>
{/section}
{if $show_other_brands}
&nbsp;::&nbsp;<a href="brands.php" class="NavigationPath">{$lng.lbl_other_brands}</a>
{/if}
{/strip}
{/if}
