{* $Id: popup_image_selection.tpl,v 1.17.2.4 2006/07/11 08:39:27 svowl Exp $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{config_load file="$skin_config"}
<html>
<head>
	<title>{$lng.lbl_add_new_product}</title>
	<link rel="stylesheet" href="{$SkinDir}/skin1_admin.css" />
</head>
<body{$reading_direction_tag} style="background:#d9ead3;">

<br />
<br />
<table width="90%" align="center">
<tr>
<td>


{capture name=dialog}
<form action="cidev_popup_add_product.php" method="post" name="imageselform" >

<input type="hidden" name="mode" value="cidev_add_product" />

<table align="center">

{if $active_modules.Manufacturers ne ""}
<tr>
    <td class="FormButton" nowrap="nowrap">{$lng.lbl_manufacturer}:</td>
    <td class="ProductDetails">
        <select name="manufacturerid">
            <option value=''{if $product.manufacturerid eq ''} selected="selected"{/if}>{$lng.lbl_no_manufacturer}</option>
    {foreach from=$manufacturers item=v}
        <option value='{$v.manufacturerid}'>{$v.manufacturer}</option>
    {/foreach}
    </select>
    </td>
    <td>
        {if $smarty.get.empty_mid eq "y"}
        <font color="red"><B><<</B> Please select</font>
        {/if}
    </td>
</tr>
<tr><td colspan="3">&nbsp;</td></tr>
{/if}

<tr>
        <td class="FormButton" nowrap="nowrap">{$lng.lbl_sku}:</td>
        <td class="ProductDetails"><input type="text" name="productcode" size="20" value="" class="InputWidth" /></td>
	<td>
	{if $smarty.get.empty_sku eq "y"}
	<font color="red"><B><<</B> Empty</font>
	{/if}
	</td>
</tr>

<tr><td colspan="3">&nbsp;</td></tr>

<tr><td colspan="3" align="center"><input type="submit" value="Next to Product modify page" name="submit" /></td></tr>

</table>

</form>

{/capture}
{include file="dialog.tpl" content=$smarty.capture.dialog title=$lng.lbl_add_new_product extra='width="100%"'}

<p align="right"><a href="javascript: window.close();"><b>{$lng.lbl_close_window}</b></a></p>

</td>
</tr>
</table>

</body>
</html>

