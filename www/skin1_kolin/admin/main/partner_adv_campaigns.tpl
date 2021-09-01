{* $Id: partner_adv_campaigns.tpl,v 1.1.2.4 2004/11/05 14:49:34 max Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_adv_campaigns_management}
{$lng.txt_advertising_campaigns_note}<BR><BR>

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->
<BR>
<SCRIPT type="text/javascript" language="JavaScript 1.2">
var texts = new Array();
texts[0] = new Array('G', "{$lng.txt_acm_get_parameter|replace:'"':'\"'}");
texts[1] = new Array('R', "{$lng.txt_acm_http_referer|replace:'"':'\"'}");
texts[2] = new Array('L', "{$lng.txt_acm_landing_page|replace:'"':'\"'}");
{literal}
function change_textarea(word) {
var x;
	for(x = 0; x < texts.length; x++) {
		if(texts[x][0] == word)
			document.getElementById('textspan').innerHTML = texts[x][1];
	}
}
{/literal}
</SCRIPT>
{if $campaigns ne ''}
{capture name=dialog}
<TABLE border="0" cellspacing="1" cellpadding="2">
<TR class="TableHead">
	<TD>{$lng.lbl_campaign}</TD>
	<TD>{$lng.lbl_usage_type}</TD>
	<TD>&nbsp;</TD>
</TR>
{foreach from=$campaigns item=v}
<TR>
	<TD><A href="partner_adv_campaigns.php?campaignid={$v.campaignid}">{$v.campaign}</A></TD>
	<TD>{if $v.type eq 'G'}{$lng.lbl_get_parameter}{elseif $v.type eq 'R'}{$lng.lbl_http_referer}{else}{$lng.lbl_landing_page}{/if}</TD>
	<TD><A href="partner_adv_campaigns.php?mode=delete&campaignid={$v.campaignid}">{$lng.lbl_delete}</A></TD>
</TR>
{/foreach}
</TABLE>
{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_advertising_campaigns extra="width=100%"} 

<BR>
{/if}
{capture name=dialog}
<FORM action="partner_adv_campaigns.php" method="post">
<INPUT type="hidden" name="mode" value="add">
<INPUT type="hidden" name="campaignid" value="{$campaign.campaignid}">
<TABLE cellspacing="2" cellpadding="2" border="0">
<TR>
	<TD nowrap>{$lng.lbl_campaign_name}</TD>
	<TD><INPUT type="text" name="add[campaign]" value="{$campaign.campaign}"></TD>
</TR>
<TR>
    <TD nowrap>{$lng.lbl_pay_per_visit}</TD>
    <TD><INPUT type="text" size="5" name="add[per_visit]" value="{$campaign.per_visit|default:"0.00"}"></TD>
</TR>
<TR>
    <TD nowrap>{$lng.lbl_pay_per_period}</TD>
    <TD><INPUT type="text" size="5" name="add[per_period]" value="{$campaign.per_period|default:"0.00"}"></TD>
</TR>
<TR>
	<TD nowrap>{$lng.lbl_period_from}:</TD>
	<TD>{html_select_date prefix="Start" time=$campaign.start_period|default:$month_begin start_year="-1" end_year="+5"}</TD>
</TR>
<TR>
	<TD nowrap>{$lng.lbl_period_to}:</TD>
	<TD>{html_select_date prefix="End" time=$campaign.end_period start_year="-1" end_year="+5"}</TD>
</TR>
<TR> 
    <TD nowrap>{$lng.lbl_usage_type}</TD>
    <TD><SELECT name="add[type]" onchange="javascript: change_textarea(this.value);">
	<OPTION value='G'{if $campaign.type eq 'G' || $campaign.type eq ''} selected{/if}>{$lng.lbl_get_parameter}</OPTION>
    <OPTION value='R'{if $campaign.type eq 'R'} selected{/if}>{$lng.lbl_http_referer}</OPTION>
    <OPTION value='L'{if $campaign.type eq 'L'} selected{/if}>{$lng.lbl_landing_page}</OPTION>
	</SELECT></TD>
</TR>
<TR>
    <TD>&nbsp;</TD>
    <TD>{$lng.txt_acm_general_note}<BR><BR><SPAN id="textspan"></SPAN><BR><BR><TEXTAREA id="textarea" name="add[data]" rows="3" cols="50">{$campaign.data|escape}</TEXTAREA><BR>{if $campaign.type eq 'L'}<BR><B>&lt;IMG&gt; tag:</B><BR><INPUT type="text" readonly value="&lt;IMG src=&quot;{$current_location}/adv_counter.php?campaignid={$v.campaignid}&quot; border=&quot;0&quot; width=&quot;1&quot; height=&quot;1&quot;&gt;" size="50">{/if}</TD>
</TR>
<SCRIPT type="text/javascript" language="JavaScript 1.2">
change_textarea('{$campaign.type|default:"G"}');
</SCRIPT>
<TR>
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="{if $campaign.campaignid > 0}{$lng.lbl_modify}{else}{$lng.lbl_add}{/if}">{if $campaign.campaignid > 0}&nbsp;<INPUT type="submit" value="{$lng.lbl_close}" name="close">{/if}</TD>
</TR>
</TABLE>
</FORM>
{/capture}
{if $campaign.campaignid > 0}{assign var="dialog_title" value=$lng.lbl_modify_advertising_campaigns}{else}{assign var="dialog_title" value=$lng.lbl_add_advertising_campaigns}{/if} 
{include file="dialog.tpl" content=$smarty.capture.dialog title=$dialog_title extra="width=100%"} 

