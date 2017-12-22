{* $Id: payment_wait.tpl,v 1.8.2.4 2007/01/19 06:58:24 max Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{config_load file="$skin_config"}
<html>
<head>
<title>{$lng.msg_order_is_being_placed}</title>
<link rel="stylesheet" href="{$SkinDir}/{#CSSFile#}" />
</head>
<body>
<table cellpadding="0" cellspacing="0" align="center" class="Container" width="100%">
<tr>
	<td class="LCSBackground" height="30">&nbsp;</td>
</tr>
<tr>
	<td height="1"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr>
	<td class="LCSBackground" height="1"><img src="{$ImagesDir}/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr>
	<td style="padding-left: 30px; padding-top: 10px; height: 90%;">

<table cellspacing="1" cellpadding="2" width="100%" style="height: 100%;">
<tr>
	<td valign="top"><h1>{$lng.msg_order_is_being_placed}</h1></td>
</tr>
<tr>
	<td valign="top" height="95%">

