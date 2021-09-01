{* $Id: generator.tpl,v 1.7.2.11 2006/12/06 13:21:12 twice Exp $ *}
{include file="page_title.tpl" title=$lng.lbl_shipping_labels}

{$lng.txt_shipping_labels_info}
<br /><br />
{capture name=dialog}

<div align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_go_back href="javascript: history.go(-1);" js_to_href="Y"}</div>

<br />

{$lng.txt_shipping_labels_help}
<br /><br />
<form action="generator.php" method="post" name="ordersform">

<script type="text/javascript" language="JavaScript 1.2">
<!--
checkboxes_form = 'ordersform';
checkboxes = new Array({foreach from=$orders item=v key=k}{if $k > 0},{/if}'orderids[{$v.orderid}]'{/foreach});
 
-->
</script>
{include file="main/include_js.tpl" src="change_all_checkboxes.js"}

<div style="line-height:170%"><a href="javascript:change_all(true);">{$lng.lbl_check_all}</a> / <a href="javascript:change_all(false);">{$lng.lbl_uncheck_all}</a></div>

<script type="text/javascript">
<!--
{literal}
function openWindow() {
var x, str;
	if(checkboxes.length == 0)
		return false;

	str = '';
	for(x = 0; x < checkboxes.length; x++) {
		if(document.forms['ordersform'].elements[checkboxes[x]].checked)
			str = str+"&"+checkboxes[x]+"=Y";
	}
	window.open('generator.php?mode=get_label'+str,'SLabels','width=800,height=450,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=yes,location=no,direction=no');
}

{/literal}
-->
</script>
<table cellspacing="1" cellpadding="5" width="100%">
<tr class="TableHead">
	<td width="10"><input type="hidden" name="update" value="N"></td>
	<td width="10%">{$lng.lbl_order}</td>
	<td width="15%">{$lng.lbl_customer}</td>
	<td width="15%">{$lng.lbl_date}</td>
	<td width="30%">{$lng.lbl_shipping_method}</td>
	<td width="30%">{$lng.lbl_shipping_label}</td>
</tr>
{foreach from=$orders item=v}
<tr{cycle values=", class='TableSubHead'"}>
	<td><input type="checkbox" name="orderids[{$v.orderid}]" /><input type="hidden" name="orderids_all[{$v.orderid}]" value="{$v.orderid}"></td>
	<td align="center"><a href="order.php?orderid={$v.orderid}" border="0">{$v.order_prefix}{$v.orderid}</a></td>
	<td align="center">{$v.login}</td>
	<td align="center">{$v.date|date_format:$config.Appearance.date_format}</td>
	<td align="center">{$v.shipping|trademark|default:$lng.txt_not_available}</td>
	<td align="center">
	{if $v.sl_type eq 'D' || $v.sl_type eq 'I'} 
	<a href="{$current_location}/slabel.php?orderid={$v.orderid}">{$lng.lbl_download}</a></td>
	{elseif $v.sl_type eq 'E'}
		<b>{$lng.lbl_error}:</b> {$v.shipping_label_error}	
	{elseif $v.sl_type ne 'E' && $v.sl_type ne 'D' && $v.sl_type ne 'I'}
	{$lng.txt_not_available}
	{/if}

	</td>
</tr>
{/foreach}
{if $is_ups_exists}
<tr>
	<td colspan="6">
		<hr />
	</td>
</tr>
<tr>
	<td colspan="5">
	</td>
	<td align="center">
		<a href="{$current_location}/slabel.php?orderid=ups">{$lng.lbl_all_ups_labels}</a>
	</td>
</tr>
{/if}
</table>

<br />
<br />

{$lng.txt_shipping_labels_note}

<br />
<br />

<input type="button" value="{$lng.lbl_update_shipping_labels}" onclick="javascript: if (checkMarks(this.form, new RegExp('orderids\[[0-9]+\]', 'gi'))) {ldelim} document.ordersform.action='generator.php'; this.form.update.value='Y';submitForm(this, ''); {rdelim}"/>
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_selected_orders content=$smarty.capture.dialog extra='width="100%"'}
