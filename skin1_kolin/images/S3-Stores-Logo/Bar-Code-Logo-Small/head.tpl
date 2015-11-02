{* $Id: head.tpl,v 1.40.2.3 2004/11/19 06:40:15 max Exp $ *}
<CENTER>
<TABLE border="0" cellpadding="0" cellspacing="0"{if $current_storefront eq '0' || !$current_storefront_info.image || $current_storefront_info.image.image_size lte 0 } height="170" width="1102" background="/skin1_kolin/top_v6.gif"{else} height="{$current_storefront_info.image.image_y}" width="{$current_storefront_info.image.image_x}" background="{if $current_storefront_info.image.image_path ne ''}{$current_storefront_info.image.image_path}{else}{$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=S{/if}"{/if}>
<TR>
<TD align="right" valign="top" style="padding-top: 0px; padding-right: 10px;">
<img alt="Front Page" src="/skin1_kolin/images/front-page/S3-Logo-Small-v1.gif" style="width: 113px; height: 51px;">
</TD>
</TR>
<TR>
<TD align="right" valign="bottom" style="padding-bottom: 0px; padding-right: 5px; font-size: 14px; font-weight: bold;">
</TD>
</TR>
</TABLE>
</CENTER>

{*
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">
<TR> 
<TD  colspan="2" class="VertMenuBorder"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD>
</TR>
<TR> 
<TD class="HeadLine" height="22">
{if $usertype eq "C"}
{ include file="customer/search.tpl" }
{/if}
</TD>
<TD class="HeadLine" align="right">
{if ($usertype eq "C" || $usertype eq "B")and $all_languages_cnt gt 1}
<TABLE border="0" cellpadding="0" cellspacing="0">
<FORM action="home.php" method="GET" name="sl_form">
<INPUT type="hidden" name="redirect" value="{$smarty.server.PHP_SELF}?{$smarty.server.QUERY_STRING}">
<TR>
<TD>
<B>{$lng.lbl_select_language}:&nbsp;</B>
<SELECT name="sl" onChange="javascript: document.sl_form.submit()">
{section name=ai loop=$all_languages}
<OPTION value="{$all_languages[ai].code}"{if $store_language eq $all_languages[ai].code} selected{/if}>{$all_languages[ai].language}</OPTION>
{/section}
</SELECT>
</TD></TR>
</FORM>
</TABLE>
{else}
&nbsp;
{/if}
</TD>
</TR>
<TR> 
<TD  colspan="2" class="VertMenuBorder"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD>
</TR>

<TR> 
<TD colspan="2" class="NumberOfArticles" align="right">{insert name="productsonline"} {$lng.lbl_products} {if $config.Appearance.show_in_stock eq "Y"}{$lng.lbl_and} {insert name="itemsonline"} {$lng.lbl_items} {/if}{$lng.lbl_online}&nbsp;</TD>
</TR>

<TR>
	<TD colspan="2" valign="middle" height="32">
<TABLE cellspacing="0" cellpadding="0" border="0" width="100%" height="18">
<TR>
	<TD><IMG src="{$ImagesDir}/spacer.gif" width="1" height="18" border="0"></TD>
{if (($main eq 'catalog' && $cat ne '') || $main eq 'product' || ($main eq 'comparison' && $mode eq 'compare_table') || ($main eq 'choosing' && $smarty.get.mode eq 'choose')) && $config.Appearance.enabled_printable_version eq 'Y'}
<TD width="100%" valign="middle" align="right">{include file="printable.tpl"}</TD>
<TD><IMG src="{$ImagesDir}/spacer.gif" width="176" height="1" border="0"></TD>
{/if}
</TR>
</TABLE>
	</TD>
</TR>
</TABLE>*}
{include file="customer/top_menu.tpl"}
