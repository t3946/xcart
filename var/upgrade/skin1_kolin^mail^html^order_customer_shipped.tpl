{* order_customer_shipped.tpl, random *}
{if $customer ne ''}{assign var="_userinfo" value=$customer}{else}{assign var="_userinfo" value=$userinfo}{/if}
{config_load file="$skin_config"}
{*include file="mail/html/mail_header.tpl"*}

{if $retrieve ne "Y"}<p />{$lng.eml_dear|substitute:"customer":"`$_userinfo.firstname` `$_userinfo.lastname`"},{/if}

<p />
{include file="mail/html/order_invoice.tpl" show_shipping_groups='Y'}

{if $retrieve eq 'Y'}
<p />
{/if}

{if $retrieve ne 'Y'}
	{if !$order.empty_shipping_groups}
	<table cellspacing="0" cellpadding="0" width="{if $is_nomail eq 'Y'}100%{else}600{/if}" bgcolor="#ffffff">
	<tr>
		<td align="center"><br /><br /><font style="FONT-SIZE:12px">{$lng.txt_thank_you_for_purchase}</font></td>
	</tr>
	</table>
	{/if}
	{include file="mail/html/signature.tpl"}
{/if}

