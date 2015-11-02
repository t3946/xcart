{* menu_storefronts_footer.tpl, kirill *}
<!--
{$config.Appearance.storefront_columns}
-->
{if $sf_links ne '' && $storefronts_per_column > 0}
<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td style="vertical-align: top;" width="{$sf_column_percent}%">
<span class="ProductPrice">{$lng.lbl_related_stores}:</span><br />
{assign var=cell_counter value=1}
{assign var=row_counter value=0}
{foreach item=v from=$sf_links}
{assign var=cell_counter value=$cell_counter+1}
<a href="{$v.company_website}" class="NavigationPath" target="_blank">{$v.company_name}</a>
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
