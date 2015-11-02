{* menu_brands_footer.tpl, random *}

{if $cidev_letters_arr ne ''}
<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td style="vertical-align: top;">
<span class="ProductPrice">{$lng.lbl_brands}: </span>

{foreach from=$cidev_letters_arr item=item key=key}
&nbsp;<a href="/brands.php#{$item}" class="NavigationPath">{$item}</a>
{/foreach}
</td>

{if $main eq "product"}
<td align="right" style="padding-right: 25px;">
{section name=mid loop=$cidev_brands_menu}
{if $cidev_brands_menu[mid].brandid eq $product.brandid}
<a href="/brands.php?brandid={$product.brandid}" class="NavigationPath">All {$cidev_brands_menu[mid].brand} products</a>
{/if}
{/section}
</td>
{/if}

</tr>
</table>
{/if}



{*
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
*}
