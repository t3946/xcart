{* $Id: return_slip.tpl,v 1.5 2005/11/22 08:17:30 max Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{ config_load file="$skin_config" }
<html>
<head>
<title>{$lng.txt_site_title}</title>
{ include file="meta.tpl" }
</head>
<body onload="javascript: window.print();"{$reading_direction_tag}>
<table>
<tr><td height="230">
<table border="1" width="330">
<tr>
	<td bgcolor="#FFFFFF">
<h1 style="FONT-FAMILY: Verdana, Arial, Helvetica, Sans-serif;">
<b>
{$config.Company.company_name}<br />
{$config.Company.location_address}<br />
{$config.Company.location_city}, {$config.Company.location_state} {$config.Company.location_zipcode}<br />
<br />
</b>
</h1>
<h2>
<b>{$lng.lbl_returnid}:</b> {$return.returnid}<br />
</h2>
	</td>
</tr>
</table>
</td>
</tr></table>
</body>
</html>
