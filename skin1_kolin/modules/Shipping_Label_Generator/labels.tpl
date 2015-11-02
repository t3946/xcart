{* $Id: labels.tpl,v 1.5.2.1 2006/11/01 12:37:40 twice Exp $ *}
{ config_load file="$skin_config" }
<html>
<head>
<title>{$lng.lbl_shipping_labels}</title>
{ include file="meta.tpl" }
</head>
<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" onload="javascript: window.print();" bgcolor="white"{$reading_direction_tag}>
<table cellspacing="0" cellpadding="0">
{foreach from=$orderids item=id}
<tr>
	<td><img src="{$xcart_web_dir}/slabel.php?orderid={$id}" border="0" alt="" /></td>
</tr>
{/foreach}
</table>
</body>
</html>
