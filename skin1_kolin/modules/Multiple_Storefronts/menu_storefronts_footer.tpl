{* menu_storefronts_footer.tpl, kirill *}
<!--
{$config.Appearance.storefront_columns}
-->
{*
{if $sf_links ne '' && $storefronts_per_column > 0}
<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td style="vertical-align: top;" width="{$sf_column_percent}%">
<span class="ProductPrice">{$lng.lbl_related_stores}:</span><br />
{assign var=cell_counter value=1}
{assign var=row_counter value=0}
{foreach item=v from=$sf_links}
{assign var=cell_counter value=$cell_counter+1}
<a href="{$v.company_website}" class="NavigationPath" target="_blank" rel="nofollow">{$v.company_name}</a>
{if $cell_counter eq $storefronts_per_column}
{assign var=cell_counter value=0}
{assign var=row_counter value=$row_counter+1}
</td><td style="vertical-align: top;" width="{$sf_column_percent}%">
{else}
<br />
{/if}
{/foreach}
</td>
{section name=rows start=$row_counter loop=$config.Appearance.storefront_columns-1 step=1}
<td style="vertical-align: top;" width="{$sf_column_percent}%">&nbsp;</td>
{/section}
</tr>
</table>
{/if}
*}

{if $sf_links ne ''}
{assign var=count_cells_in_row value=4}

<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td align="left" style="vertical-align: top;" colspan="4">
<span class="ProductPrice">{$lng.lbl_related_stores}</span>
</td>
</tr>

<tr>
{assign var=cell_counter value=0}

{foreach item=item key=key from=$sf_links}

{if $cell_counter eq "0"}
<tr>
{/if}

<td width="25%" align="left"><a href="{$item.company_website}" class="NavigationPath" target="_blank" rel="nofollow">{$item.company_name}</a></td>
{assign var=cell_counter value=$cell_counter+1}

{if $cell_counter eq $count_cells_in_row}
</tr>
{assign var=cell_counter value=0}
{/if}

{/foreach}

{if $cell_counter eq "1"}
<td colspan="3">&nbsp;</td>
{elseif $cell_counter eq "2"}
<td colspan="2">&nbsp;</td>
{else}
<td>&nbsp;</td>
{/if}

{if $cell_counter gt "0"}
</tr>
{/if}


</table>
{/if}

