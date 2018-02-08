{* $Id: main.tpl,v 1.5.2.1 2004/08/16 08:36:03 mclap Exp $ *}
<P>
{capture name=dialog}
<P>
{if $product}
<TABLE border="0" width="100%">
<TR>
<TD colspan="2" class="ProductTitle"><A href="product.php?productid={$product.productid}">#{$product.productid}. {$product.product}</A></TD>
</TR>
<TR><TD colspan="2">&nbsp;</TD></TR>
<TR>
<TD valign="top" align="left" rowspan="2" width="120">
<A href="product.php?productid={$product.productid}">{include file="product_thumbnail.tpl" productid=$product.productid image_x=$product.image_x image_y=$product.image_y product=$product.product}</A>&nbsp;
</TD>
<TD valign="top">
<SPAN class=>
{if $product.fulldescr ne ""}{$product.fulldescr}{else}{$product.descr}{/if}
</SPAN>
<P>
<TABLE width="100%" cellpadding="0" cellspacing="0" border="0">
<TR><TD colspan="2"><B><FONT class="ProductDetailsTitle">{$lng.lbl_details}</FONT></B></TD></TR>
<TR><TD class="Line" height="1" colspan="2"><IMG src="{$ImagesDir}/spacer.gif" width="1" height="1" border="0"></TD></TR>
<TR><TD colspan="2">&nbsp;</TD></TR>
{if $product.weight ne "0.00"}
<TR><TD width="30%">{$lng.lbl_weight}</TD><TD nowrap>{$product.weight} {$config.General.weight_symbol}</TD></TR>
{/if}
{if $active_modules.Extra_Fields ne ""}
{include file="modules/Extra_Fields/product.tpl"}
{/if}
</TD>
</TR>
</TABLE>
</TD>
</TR>
<TR>
<TD>
<BR><BR>
{$lng.lbl_download_msg}
<BR><BR>
{assign var="title_length" value=""}
{if $product.length > 0}
{assign var="title_length" value=$lng.lbl_file_size|cat:": `$product.length` `$lng.lbl_byte`"}
{/if}
{include file="buttons/button.tpl" button_title=$lng.lbl_download href=$url title=$title_length}<BR>
{$title_length}
</TD>
</TR>
</TABLE>
{else}
{$lng.lbl_download_errmsg}
{/if}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_download content=$smarty.capture.dialog extra="width=100%"}
