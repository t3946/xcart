{* menu_brands_footer.tpl, random *}
{if $brands_menu ne '' && $brands_per_column > 0}
<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td style="vertical-align: top;">
<span class="ProductPrice">{$lng.lbl_brands}:</span><br />
{assign var=cell_counter value=1}
{section name=mid loop=$brands_menu}
{assign var=cell_counter value=$cell_counter+1}
<a href="brands.php?brandid={$brands_menu[mid].brandid}" class="NavigationPath">{$brands_menu[mid].brand}</a>
{if $cell_counter eq $brands_per_column}
{assign var=cell_counter value=0}
</td><td style="vertical-align: top;">
{else}
<br />
{/if}
{/section}
{if $show_other_brands}
<a href="brands.php" class="NavigationPath">{$lng.lbl_other_brands}</a>
{/if}
</td>
</tr>
</table>
{/if}
