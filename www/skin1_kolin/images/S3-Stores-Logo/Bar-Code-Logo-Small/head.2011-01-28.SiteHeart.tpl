{* $Id: head.tpl,v 1.40.2.3 2004/11/19 06:40:15 max Exp $ *}
<CENTER>
<TABLE border="0" cellpadding="0" cellspacing="0" height="170" width="1102" background="/skin1_kolin/top_v6.gif">
<TR>
<TD align="right" valign="top" style="padding-top: 0px; padding-right: 6px;">
<a href="/home.php"><img alt="Front Page" src="/skin1_kolin/images/front-page/Home.gif" style="width: 99px; height: 40px;"></a>
</TD>
</TR>
<TR>
<TD align="right" valign="bottom" style="padding-bottom: 0px; padding-right: 5px; font-size: 14px; font-weight: bold;">
<a href="https://siteheart.com/webconsultation/2848?byhref=1&amp;s=1" target="siteheart_sitewindow_2848" onclick="o=window.open;o('https://siteheart.com/webconsultation/2848?s=1', 'siteheart_sitewindow_2848', 'width=550,height=400,top=30,left=30,resizable=yes'); return false;"><img src="/livehelp/onoff_images/text_online.gif" border="0" alt="Artist Supply Source Live Help" /></a>
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
