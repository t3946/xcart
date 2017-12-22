{* $Id: product_manufacturer.tpl,v 1.5 2004/05/20 12:18:09 max Exp $ *}
{if $active_modules.Manufacturers ne ""}
{include file="main/popup_product_js.tpl"}

{$lng.txt_select_manufacturer}

<BR><BR>

{capture name=dialog}

<TABLE border="0" {if $productids ne ''}cellspacing="0" cellpadding="4"{else}cellspacing="1" cellpadding="2"{/if}>

<FORM action="product_modify.php" name="manufacturer" method="POST">
{if $productids ne ''}
<TR>
    <TD width="15" class="TableSubHead">&nbsp;</TD>
    <TD class="TableSubHead" colspan="2"><B>* {$lng.lbl_note}:</B> {$lng.txt_edit_product_group}</TD>
</TR>
{/if}

<INPUT type="hidden" name="productid" value="{$product.productid}">
<INPUT type="hidden" name="selected_productid" value="">
<INPUT type="hidden" name="mode" value="manufacturer">
<TR>
{if $productids ne ''}<TD width="15" class="TableSubHead"><INPUT type="checkbox" value="Y" name="fields[manufacturer]"></TD>{/if}
	<TD>{$lng.lbl_manufacturer}</TD>
	<TD width="100%"><SELECT name="manufacturerid">
	<OPTION value=''{if $product.manufacturerid eq ''} selected{/if}>{$lng.lbl_no_manufacturer}</OPTION>
	{foreach from=$manufacturers item=v}
	<OPTION value='{$v.manufacturerid}'{if $v.manufacturerid eq $product.manufacturerid} selected{/if}>{$v.manufacturer}</OPTION>
	{/foreach}
	</SELECT></TD>
</TR>
<TR>
{if $productids ne ''}<TD width="15" class="TableSubHead">&nbsp;</TD>{/if}
	<TD>&nbsp;</TD>
	<TD><INPUT type="submit" value="{$lng.lbl_update}"></TD>
</TR>
</FORM>
</TABLE>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_select_manufacturer content=$smarty.capture.dialog extra="width=100%"}
{/if}
