{* $Id: history_order.tpl,v 1.83.2.17 2006/12/25 11:53:29 svowl Exp $ *}
{if $current_membership_flag eq 'FS'}
{assign var="membership_static" value="F"}
{else}
{assign var="membership_static" value=""}
{/if}
{include file="main/multirow.tpl"}
<script type="text/javascript">
<!--
multirowInputSets['track'] = [];
multirowInputSets['track'].noCloneContent = 1;
-->
</script>

{include file="page_title.tpl" title=$lng.lbl_order_details_label}

{$lng.txt_order_details_top_text}

<br /><br />

{if $usertype eq 'A' && $is_merchant_password ne 'Y' && $config.Security.blowfish_enabled eq 'Y'}
{capture name=dialog}
<form action="{$catalogs.admin}/merchant_password.php" method="post" name="mpasswordform">
<input type="hidden" name="orderid" value="{$orderid}" />
{$lng.txt_enter_merchant_password_note}
<br /><br />
<table>
<tr>
	<td><font class="VertMenuItems">{$lng.lbl_merchant_password}</font></td>
	<td><input type="password" name="mpassword" size="16" /></td>
	<td><input type="submit" value="{$lng.lbl_enter_mpassword|strip_tags:false|escape}" /></td>
</tr>
</table>
</form>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_enter_merchant_password content=$smarty.capture.dialog extra='width="100%"'}
<br />
{/if}

{capture name=dialog}

{assign var=colspan value=10}

<table width="100%">
<tr> 
	<td valign="top" colspan="{$colspan}">

{if $usertype ne "C"}
  <div align="left">

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td>
<b>{$lng.lbl_order} # {$order.order_prefix}{$order.orderid}</b>
</td>

<td>
{if $orderid_prev ne ""}<a href="order.php?orderid={$orderid_prev}">&lt;&lt;&nbsp;{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_prev}{$orderid_prev}</a>{/if}
{if $orderid_next ne ""}{if $orderid_prev ne ""} | {/if}<a href="order.php?orderid={$orderid_next}">{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_next}{$orderid_next}&nbsp;&gt;&gt;</a>{/if}
</td>

<td width="50%" align="right">
<table cellspacing="0" cellpadding="0" class="ButtonsRow">
<tr>
{if $usertype eq "P" || $usertype eq 'A'}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_print_order target="_blank" href="order.php?orderid=`$order.orderid`&mode=printable"}</td>
{/if}
{if $active_modules.RMA ne '' && $current_membership_flag ne 'FS'}
{if ($usertype eq  'C' || $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $return_products ne ''}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_create_return href="#returns"}</td>
{/if}
{if ($usertype eq  'C' || $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $order.is_returns}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_order_returns href="returns.php?mode=search&search[orderid]=`$order.orderid`"}</td>
{/if}
{/if}
{if $active_modules.Shipping_Label_Generator ne '' && ($usertype eq 'A' || $usertype eq 'P')}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_shipping_label href="generator.php?orderid=`$order.orderid`"}</td>
{/if}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_print_invoice target="_blank" href="order.php?orderid=`$order.orderid`&mode=invoice"}</td>
{if ($usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)) and $active_modules.Advanced_Order_Management}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_modify href="order.php?orderid=`$order.orderid`&mode=edit"}</td>
{/if}
</tr>
</table>
</td>
</tr>
</table>

{$lng.lbl_date}: {$order.date|date_format:$config.Appearance.datetime_format}

<br />
Order source: <a href="{$customer.referer}" target="_blank">Referral link</a>
</div>
{/if}


{if $usertype eq "C"}
<p class="prev-next-links">
{if $orderid_prev ne ""}<a href="order.php?orderid={$orderid_prev}">&lt;&lt;&nbsp;{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_prev}{$orderid_prev}</a>{/if}
{if $orderid_next ne ""}{if $orderid_prev ne ""} | {/if}<a href="order.php?orderid={$orderid_next}">{$lng.lbl_order} {if $usertype eq 'A' || $usertype eq 'P'}# {/if}{$order_prefix_next}{$orderid_next}&nbsp;&gt;&gt;</a>{/if}
</p>

<table cellspacing="1" cellpadding="2" class="ButtonsRow">
<tr>
{if $usertype eq "P" || $usertype eq 'A'}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_print_order target="_blank" href="order.php?orderid=`$order.orderid`&mode=printable"}</td>
{/if}
{if $active_modules.RMA ne '' && $current_membership_flag ne 'FS'} 
{if ($usertype eq  'C' || $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $return_products ne ''}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_create_return href="#returns"}</td>
{/if}
{if ($usertype eq  'C' || $usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $order.is_returns}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_order_returns href="returns.php?mode=search&search[orderid]=`$order.orderid`"}</td>
{/if}
{/if}
{if $active_modules.Shipping_Label_Generator ne '' && ($usertype eq 'A' || $usertype eq 'P')} 
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_shipping_label href="generator.php?orderid=`$order.orderid`"}</td>
{/if}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_print_invoice target="_blank" href="order.php?orderid=`$order.orderid`&mode=invoice"}</td>
{if ($usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)) and $active_modules.Advanced_Order_Management}
<td class="ButtonsRow">{include file="buttons/button.tpl" button_title=$lng.lbl_modify href="order.php?orderid=`$order.orderid`&mode=edit"}</td>
{/if}
</tr>
</table>
{/if}

<p />
{if $usertype eq "C"}
<hr />
{include file="mail/html/order_invoice.tpl" is_nomail='Y'}
{elseif $usertype eq "A" || ($usertype eq 'P' && $active_modules.Simple_Mode)}
{include file="main/order_info_admin.tpl" static=$membership_static}
{else}
{include file="main/order_info.tpl"}
{/if}
	</td>
</tr>
<tr>
	<td height="1" valign="top">
<script type="text/javascript">
<!--
var details_mode = false;
var details_fields_labels = new Object();
{foreach from=$order_details_fields_labels key=dfield item=dlabel}
details_fields_labels["{$dfield|escape:javascript}"] = "{$dlabel|escape:javascript}";
{/foreach}
-->
</script>
{include file="main/include_js.tpl" src="main/history_order.js"}
<form action="order.php" method="post" name="ordernotesform">
<input type="hidden" name="send_email" value="N" />

{if $usertype ne "C"}
<p />
{$lng.lbl_customer_notes}:<br />
<textarea name="customer_notes" cols="70" rows="8" style="width: 520px;"{if $current_membership_flag eq 'FS'} readonly="readonly"{/if}>{$order.customer_notes}</textarea>
<p />
{/if}

{if $usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)}

{if $order.extra.ip ne ''}
<p />
{$lng.lbl_ip_address}: {$order.extra.ip}{if $order.extra.proxy_ip ne ''} ({$order.extra.proxy_ip}){/if}<br />
{if $active_modules.Stop_List ne ''}
{if $order.blocked eq 'Y'}
<font class="Star">{$lng.lbl_ip_address_blocked}</font><br />
{else}
<input type="button" value="{$lng.lbl_block_ip_address|strip_tags:false|escape}" onclick="javascript: self.location='order.php?mode=block_ip&amp;orderid={$orderid}'" />
{/if}
{/if}
{* $active_modules.Stop_List ne '' *}

{/if}

{if $active_modules.Anti_Fraud ne ''}
<input type="button" value="{$lng.lbl_af_lookup_address|strip_tags:false|escape}" onclick="javascript: window.open('{$catalogs.admin}/anti_fraud.php?mode=popup&amp;ip={$order.extra.ip}&amp;proxy_ip={$order.extra.proxy_ip}','AFLOOKUP','width=600,height=460,toolbar=no,status=no,scrollbars=yes,resizable=no,menubar=no,location=no,direction=no');" />
{/if}{* $active_modules.Anti_Fraud ne '' *}

<p />
{$lng.lbl_order_details}:
{if !$order.details_encrypted}
<div style="text-align: right; width: 520px; padding-bottom: 3px;">
<a id="view_mode" href="javascript: void(0);" onclick="javascript: switch_details_mode(false, this, document.getElementById('edit_mode'));" style="font-weight: bold;">{$lng.lbl_view_mode}</a>
&nbsp;&nbsp;&nbsp;
<a id="edit_mode" href="javascript: void(0);" onclick="javascript: switch_details_mode(true, this, document.getElementById('view_mode'));">{$lng.lbl_edit_mode}</a>
</div>
{/if}
<textarea id="details_view" cols="70" style="color: #666666; background-color:#EEEEEE; width: 520px;" readonly="readonly" rows="12"{if $order.details_encrypted} disabled="disabled"{/if}>{$order.details|func_order_details_translate|escape:quotes}</textarea>
{if $order.details_encrypted eq ''}
<textarea id="details_edit" style="display: none; width: 520px;" name="details" cols="70" rows="12">{$order.details|escape:quotes}</textarea>
{/if}
{/if}

{if $usertype ne "C"}
<p />
{$lng.lbl_order_notes}:<br />
<textarea name="notes" cols="70" style="width: 520px;" rows="8">{$order.notes}</textarea>
{/if}

{if $usertype eq "A" || $usertype eq "P"}
<p />
<input type="submit" value="{$lng.lbl_apply_changes|strip_tags:false|escape}" />
{if $current_membership_flag ne 'FS'}
	&nbsp;&nbsp;&nbsp;&nbsp;
	<input type="submit" value="{$lng.lbl_apply_changes_send_email|strip_tags:false|escape}" onclick="javascript: document.ordernotesform.send_email.value = 'Y'; document.ordernotesform.submit();" /><br />
{/if}
{if $usertype neq "A"}
{$lng.txt_apply_changes}	
{/if}
{/if}

{if $active_modules.Special_Offers ne "" && ($usertype eq "A" or $usertype eq "P")}
<br /><br /><br />
{include file="modules/Special_Offers/order_extra_data.tpl" data=$order.extra}
{/if}

{if ($usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode)) && $active_modules.Anti_Fraud}
<br /><br /><br />
{include file="modules/Anti_Fraud/extra_data.tpl" data=$order.extra.Anti_Fraud}
{/if}

{if ($usertype eq 'A' || ($usertype eq 'P' && $active_modules.Simple_Mode)) && $order.is_egood ne '' && $active_modules.Egoods}
<p />
<input type="button" value="{if $order.is_egood eq 'Y'}{$lng.lbl_prolong_ttl|strip_tags:false|escape}{else}{$lng.lbl_regenerate_ttl|strip_tags:false|escape}{/if}" onclick="javascript: self.location='order.php?mode=prolong_ttl&amp;orderid={$orderid}'" /><br />
{$lng.txt_prolong_ttl}
{/if}

<input type="hidden" name="mode" value="status_change" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
</form>

{if $usertype eq "P" and $order.dc_status ne "C"}
<br />
<form action="order.php" method="post">
<input type="hidden" name="mode" value="complete_order" />
<input type="submit" value="{$lng.lbl_complete_order|strip_tags:false|escape}" /><br />
{$lng.txt_complete_order}
<input type="hidden" name="orderid" value="{$order.orderid}" />
</form>
{/if}

{if $active_modules.Order_Tracking ne "" and $order.tracking ne ""}

<br /><br /><br />

{include file="main/subheader.tpl" title=$lng.lbl_tracking_order}

{assign var="postal_service" value=$order.shipping|truncate:3:"":true}
{$lng.lbl_tracking_number}: {$order.tracking}
<br /><br />

{if $postal_service eq "UPS"}
{include file="modules/Order_Tracking/ups.tpl"}
{elseif $postal_service eq "USP"}
{include file="modules/Order_Tracking/usps.tpl"}
{elseif $postal_service eq "Fed"}
{include file="modules/Order_Tracking/fedex.tpl"}
{elseif $postal_service eq "Aus"}
{include file="modules/Order_Tracking/australia_post.tpl"}
{/if}

{/if}

	</td>
</tr>
</table>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_order_details_label content=$smarty.capture.dialog extra='width="100%"'}
{if $active_modules.RMA ne '' && ($usertype eq  'C' || ($usertype eq 'A' && $current_membership_flag ne 'FS') || ($usertype eq 'P' && $active_modules.Simple_Mode))}

<br />
<a name="returns"></a>
{include file="modules/RMA/add_returns.tpl"}
{/if}

{if $usertype eq 'A' && $order_manufacturers && $current_membership_flag ne 'FS'}
<br />
<a name="mnf_notify"></a>

{capture name=dialog}
{foreach from=$order_manufacturers item=v key=mnf_id}
<div class="ProductTitle" align="center">{$v.manufacturer}</div>
<form action="order.php" method="post" name="mnfnotifyform_{$mnf_id}">
<input type="hidden" name="mode" value="mnf_notify" />
<input type="hidden" name="orderid" value="{$order.orderid}" />
<input type="hidden" name="mnf_id" value="{$mnf_id}" />
{$lng.lbl_status}:<br />
{if $v.notify_sent eq 'Y'}<span style="color: green; font-weight: bold">{$lng.lbl_sent}</span>{else}<span style="color: green;">{$lng.lbl_queued}</span>{/if}<br /><br />
{$lng.lbl_from}:<br />
<input type="text" name="mnf_from" value="{$config.Company.orders_department}" readonly="readonly" style="width: 80%;" /><br /><br />
{$lng.lbl_to}:<br />
<input type="text" name="mnf_to" value="{$v.email}" style="width: 80%;" /><br /><br />
{$lng.lbl_mnf_send_shipping}:<br />
<select name="mnf_shipping">
<option value="Y">{$lng.lbl_yes}</option>
<option value="N">{$lng.lbl_no}</option>
</select><br /><br />
{$lng.lbl_message_body}:<br />
<textarea rows="20" cols="60" name="mnf_body" style="width: 80%;">{$v.mess_body}</textarea><br /><br />
<input type="submit" value="{$lng.lbl_send|strip_tags:false|escape}" /><br /><br />
<hr /><br />
</form>
{/foreach}

{/capture}
{include file="dialog.tpl" title=$lng.lbl_manufacturers_notifications content=$smarty.capture.dialog extra='width="100%"'}
{/if}

{if $usertype eq 'A' or ($usertype eq "P" and $active_modules.Simple_Mode)}
<br />
<a name="accounting"></a>
{include file="main/order_accounting.tpl" static=$membership_static}
{/if}

{if ($usertype eq 'A' or $usertype eq 'P') and $active_modules.Google_Checkout ne '' and $order.extra.goid ne ''}

<br />
<a name="gcheckout"></a>
{include file="modules/Google_Checkout/gcheckout_order.tpl"}
{/if}
