{* $Id: affiliates.tpl,v 1.6.2.1 2005/08/05 06:59:47 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_affiliates_tree}
{$lng.txt_affiliates_tree_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>
 
{if $usertype ne 'B'}
{capture name=dialog}
<FORM action="affiliates.php" method="get">
<TABLE>
<TR>
	<TD>{$lng.lbl_partner_as_root}</TD>
	<TD><SELECT name="affiliate" size="5">
	{foreach from=$partners item=v}
	<OPTION value="{$v.login}"{if $v.login eq $affiliate} selected{/if}>{$v.firstname} {$v.lastname}</OPTION>
	{/foreach}
	</SELECT></TD>
</TR>
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="{$lng.lbl_select}"></TD>
</TR>
</TABLE>
</FORM>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_select extra="width=100%"}

<BR>

{/if}
{if $affiliate || $usertype eq 'B'}
{capture name=dialog}
<B>Note:</B> 
{if $usertype ne 'B'}
{$lng.txt_affiliates_tree_comment_a}
{else}
{$lng.txt_affiliates_tree_comment_b}
{/if}
<BR><BR>
<TABLE cellspacing="1" cellpadding="2" border="0" width="100%">
<TR class="TableHead">
    <TD width="100%">{$lng.lbl_partner}</TD>
    <TD align="center">{$lng.lbl_commission}</TD>
    <TD align="center" nowrap>{$lng.lbl_affiliate_commission}</TD>
</TR>
<TR height="19">
    <TD nowrap>&nbsp;{$parent_affiliate.firstname} {$parent_affiliate.lastname}</TD>
	<TD align="right">{include file="currency.tpl" value=$parent_affiliate.sales|default:0}</TD>
	<TD align="right">{include file="currency.tpl" value=$parent_affiliate.childs_sales}</TD>
</TR>
<TR>
    <TD>{include file="main/affiliate_list.tpl" affiliates=$affiliates level="0" type="1"}</TD>
    <TD>{include file="main/affiliate_list.tpl" affiliates=$affiliates level="0" type="2"}</TD>
    <TD>{include file="main/affiliate_list.tpl" affiliates=$affiliates level="0" type="3"}</TD>
</TR>
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_affilaites extra="width=100%"}
{/if}
