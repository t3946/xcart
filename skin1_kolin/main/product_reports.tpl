{* $Id: product_reports.tpl,v 1.0 2011/06/17 18:05:48 kate Exp $ *}

<script type="text/javascript" language="JavaScript 1.2" src="{$SkinDir}/lib/jqueryui/jquery-ui.custom.min.js"></script>

{include file="page_title.tpl" title=$lng.lbl_product_management_reports}

{include file="main/include_js.tpl" src="reset.js"}
<script type="text/javascript">
<!--
var searchform_def = [
	['Day', '{$start_date|default:$smarty.now|date_format:"%d"}'],
	['Month', '{$start_date|default:$smarty.now|date_format:"%m"}'],
	['Year', '{$start_date|default:$smarty.now|date_format:"%Y"}']
];
-->
</script>
{capture name=dialog}

{$lng.lbl_product_reports_top_text}
<br /><br />

<form name="prodreportrform" action="product_reports.php" method="post">
<input type="hidden" name="mode" value="send" />

<table cellpadding="1" cellspacing="1" width="100%">

<tr>
	<td class="OptionLabel" width="20%">{$lng.lbl_report_date|cat:":"}</td>
	<td> 

<script type="text/javascript" language="JavaScript 1.2">
 <!--
 {literal}
   $(function() {
     $("#id_start_date").datepicker();
   });
 {/literal}
 -->
</script>
 
		<input id="id_start_date" type="text" size="11" name="posted_data[start_date]" value="" />

{*
	    {html_select_date prefix="" time=$start_date start_year=$config.Company.start_year end_year=$config.Company.end_year}
*}
	</td>
</tr>

<tr>
	<td class="OptionLabel" width="20%" style="vertical-align: top;">{$lng.lbl_send_report_to|cat:":"}</td>
	<td>
        {if $operators ne ''}
        <table cellpadding="1" cellspacing="1">
        {foreach from=$operators item=op}
            <tr>
                <td><input type="checkbox" name="selected[{$op}]" value="Y"{if !$selected || $selected[$op] eq 'Y'} checked="checked"{/if} /></td>
                <td>{$op}</td>
            </tr>
        {/foreach}
        </table>
        {else}
            {$lng.lbl_no_operators}
        {/if}
	</td>
</tr>

<tr>
    <td>&nbsp;</td>
    <td align="left"><input type="submit" name="{$lng.lbl_send}"></td>
</tr>

</table>
</form>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_product_reports content=$smarty.capture.dialog extra='width="100%"'}
