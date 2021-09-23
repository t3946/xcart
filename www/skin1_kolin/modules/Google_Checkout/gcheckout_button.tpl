{* $Id: gcheckout_button.tpl,v 1.1.2.2 2007/01/18 06:38:20 svowl Exp $ *}
{if $gcheckout_button}
<table cellpadding="0" cellspacing="0">
<tr>
	<td valign="top" style="padding-top: 3px; padding-right: 4px;"><a href="cart.php?mode=checkout"><img src="{$ImagesDir}/checkout.gif" width="79" height="19" border="0" alt="" /></a></td>
	<td>&nbsp;&nbsp;</td>
	<td valign="top" style="padding-top: 5px;">{$lng.lbl_gcheckout_or_use}</td>
	<td>&nbsp;&nbsp;</td>
	<td>{$gcheckout_button}</td>
</tr>
</table>
{/if}
