{* $Id: head.tpl,v 1.40.2.3 2004/11/19 06:40:15 max Exp $ *}

{if $main eq "fast_lane_checkout" && $smarty.get.mode eq ""}
<script type="text/javascript">
//<![CDATA[
{literal}
$(function(){
 document.onkeydown = function(e) {
        if (e.keyCode == "81"){
                if (document.getElementById('s3_logo')){
			$('#s3_logo').attr('href', "javascript: window.open('popup_shipquote.php','popup_shipquote','width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no'); void(0);");
                }
        }
 }

 document.onkeyup = function(e) {
	if (document.getElementById('s3_logo')){
		$('#s3_logo').attr('href', 'javascript: void(0);');
	}
 }

});
{/literal}
//]]>
</script>
{/if}

<CENTER>
<TABLE border="0" cellpadding="0" cellspacing="0" {* height="170" *} width="960">

<TR>
<TD height="24" valign="middle">
        <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" style="background-color: #0072BB;">
        <tr>
        <td width="350" nowrap="nowrap" valign="middle" align="left">&nbsp;<font style="color: #ffffff;">{$config.Company.cidev_top_header_code}</font></td>
        <td width="*" align="right" valign="middle" >
{if !(($smarty.get.mode eq "checkout") || ($smarty.get.mode eq "update" && $smarty.get.action eq "cart")) }
<table border="0" cellpadding="1" cellspacing="0">
<tr>
{if $top_pages_menu ne ""}
{section name=top_page loop=$top_pages_menu}
{if $top_pages_menu[top_page].image.filename ne ""}
<td valign="middle">{if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}<a href="/pages.php?pageid={$top_pages_menu[top_page].pageid}">{/if}<img src="{if $HTTPS_url eq "N" && $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}{$config.Appearance.CDN_domain}{else}{$xcart_web_dir}{/if}/image.php?id={$top_pages_menu[top_page].image.id}&amp;type=A" alt="" {if $top_pages_menu[top_page].image.image_x gt "16"}width="16"{/if} />{if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}</a>{/if}</td>
{/if}
<td valign="middle" nowrap="nowrap">{if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}<a class="top_links" href="/pages.php?pageid={$top_pages_menu[top_page].pageid}">{else}<font style="color: #cccccc;">{/if}{$top_pages_menu[top_page].title}{if $smarty.get.pageid ne $top_pages_menu[top_page].pageid}</a>{else}</font>{/if}</td>
{* {if !%page.last%} *}
<td width="15" valign="middle" align="center"><font style="color: #ffffff;">|</font></td>
{* {/if} *}
{/section}
{/if}

{*
<td valign="middle">{if $main ne "help" && $smarty.get.section ne "contactus"}<a href="/help.php?section=contactus&mode=update">{/if}<IMG src="{$ImagesDir}/contact_us.png" width="16" border="0" alt="" />{if $main ne "help" && $smarty.get.section ne "contactus"}</a>{/if}</td>
*}
<td nowrap="nowrap" valign="middle">{if $main ne "help" && $smarty.get.section ne "contactus"}<a class="top_links" href="/help.php?section=contactus&mode=update">{else}<font style="color: #cccccc;">{/if}Contact Us{if $main ne "help" && $smarty.get.section ne "contactus"}</a>{else}</font>{/if}</td>

<td valign="middle" nowrap="nowrap">{$lng.lbl_top_header_nbsp}</td>
</tr>
</table>
{/if}
        </td>
        </tr>
        </table>
</TD>
</TR>

<TR>
<TD {* height="130" *} valign="bottom">
        <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
        <tr>

        <td width="250" {* style="background: url({$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=S) no-repeat;" *} valign="middle">
        {if !($main eq "catalog" && $current_category.category eq "") || $smarty.get.page ne ""}<a href="/">{/if}<img src="{if $HTTPS_url eq "N" && $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}{$config.Appearance.CDN_domain}{else}{$xcart_web_dir}{/if}/image.php?id={$current_storefront_info.storefrontid}&amp;type=S" {if $current_storefront_info.image.image_x gt "250"} width="250" {/if}  alt="Home page" >{if !($main eq "catalog" && $current_category.category eq "")}</a>{/if}
        </td>

{if $main eq "fast_lane_checkout" || $main eq "order_message"}
        <td width="*" valign="middle" align="center">
	        <a href="javascript: void(0);" style="cursor: default;" id="s3_logo"><img src="{$ImagesDir}/S3-Stores-Logo-S2.png" alt="" /></a>
        </td>
        <td width="150" valign="middle" align="right">
{if $config.Security.ssl_seal ne ""}
{$config.Security.ssl_seal}
{/if}
        </td>
{else}
        <td width="*" valign="middle">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
			<td width="10">&nbsp;</td>
                        <td valign="middle" width="*" align="center">
                        {include file="customer/search.tpl"}
                        </td>
                        <td width="10">&nbsp;</td>
                        <td width="204" valign="middle" align="right">
                        {include file="customer/menu_cart.tpl"}
                        </td>
                        </tr>
                </table>
        </td>
{/if}
        </tr>
        </table>
</TD>
</TR>

{if $main eq "order_message"}
<tr><td>&nbsp;</td></tr>
<tr><td class="cidev_checkout_bar6"></td></tr>
{/if}

</TABLE>
</CENTER>


{*
<CENTER>
<TABLE border="0" cellpadding="0" cellspacing="0"{if $current_storefront eq '0' || !$current_storefront_info.image || $current_storefront_info.image.image_size lte 0 } height="170" width="960"  background="/skin1_kolin/top_v6.gif"  {else} height="{$current_storefront_info.image.image_y}" width="{$current_storefront_info.image.image_x}"  background="{if $current_storefront_info.image.image_path ne ''}{$current_storefront_info.image.image_path}{else}{$xcart_web_dir}/image.php?id={$current_storefront_info.storefrontid}&amp;type=S{/if}"  {/if}>

<TR>
<TD align="right" valign="top" style="padding-top: 0px; padding-right: 10px;">


<img alt="Front Page" src="/skin1_kolin/images/front-page/S3-Logo-Small-v1.gif" style="width: 113px; height: 51px;">

</TD>
</TR>


<TR>

<TD align="right" valign="bottom" style="padding-bottom: 3px; padding-right: 60px;">

<table border="0" cellpadding="0" cellspacing="0"><tr><td width="250" height="112" style="background-color: #FFFFFF;" valign="middle" align="center">
{ include file="customer/menu_cart.tpl" }
</td></tr></table>

</TD>
</TR>
</TABLE>
</CENTER>
*}
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
{$config.Storefront_common_details.common_header_code}
