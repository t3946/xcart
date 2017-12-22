{* $Id: search_result.tpl,v 1.12.2.5 2005/03/25 12:53:28 max Exp $ *}
{ include file="page_title.tpl" title=$lng.lbl_search_products }

<!-- IN THIS SECTION -->

{include file="dialog_tools.tpl"}

<!-- IN THIS SECTION -->

<BR>

{if $mode ne "search" or $products eq ""}

{capture name=dialog}

<BR>

<FORM name="searchform" action="search.php" method="POST">
<INPUT type="hidden" name="mode" value="search">
<TABLE border="0" cellpadding="1" cellspacing="5" width="100%">

<TR>
<TD height="10" width="20%" class="FormButton" nowrap>{$lng.lbl_search_for_pattern}:</TD>
<TD width="10" height="10"><FONT class="CustomerMessage"></FONT></TD>
<TD height="10" width="80%">
<INPUT type="text" name="posted_data[substring]" size="30" style="width:70%" value="{$search_prefilled.substring}">
&nbsp;
<INPUT type="submit" value="{$lng.lbl_search}">
</TD>
</TR>

<TR>
<TD height="10" width="20%" class="FormButton" nowrap>{$lng.lbl_search_in}:</TD>
<TD width="10" height="10"><FONT class="CustomerMessage"></FONT></TD>
<TD>
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<TD width="5"><INPUT type="checkbox" name="posted_data[by_title]"{if $search_prefilled eq "" or $search_prefilled.by_title} checked{/if}></TD><TD nowrap>{$lng.lbl_title}&nbsp;&nbsp;</TD>
<TD width="5"><INPUT type="checkbox" name="posted_data[by_shortdescr]"{if $search_prefilled eq "" or $search_prefilled.by_shortdescr} checked{/if}></TD><TD nowrap>{$lng.lbl_short_description}&nbsp;&nbsp;</TD>
<TD width="5"><INPUT type="checkbox" name="posted_data[by_fulldescr]"{if $search_prefilled eq "" or $search_prefilled.by_fulldescr} checked{/if}></TD><TD nowrap>{$lng.lbl_det_description}&nbsp;&nbsp;</TD>
</TR>
</TABLE>
</TD>
</TR>

{if $active_modules.Extra_Fields && $extra_fields ne ''}
<TR>
<TD height="10" width="20%" class="FormButton" nowrap>{$lng.lbl_search_also_in}:</TD>
<TD width="10" height="10"><FONT class="CustomerMessage"></FONT></TD>
<TD>
<TABLE border="0" cellpadding="0" cellspacing="0">
{foreach from=$extra_fields item=v}
<TR>
<TD width="5"><INPUT type="checkbox" name="posted_data[extra_fields][{$v.fieldid}]"{if $v.selected eq "Y"} checked{/if}></TD>
<TD>{$v.field}</TD>
</TR>
{/foreach}
</TABLE>
</TD>
</TR>
{/if}

</TABLE>

<BR>

<TABLE>
<TR>
<TD id="close1" style="cursor: hand;" onClick="visibleBox('1')"><IMG src="{$ImagesDir}/plus.gif" border="0" align="absmiddle" alt="Click to open"></TD>
<TD id="open1" style="display: none; cursor: hand;" onClick="visibleBox('1')"><IMG src="{$ImagesDir}/minus.gif" border="0" align="absmiddle" alt="Click to close"></TD>
<TD><A href="javascript:void(0);" onClick="visibleBox('1')"><B>{$lng.lbl_advanced_search_options}</B></A></TD>
</TR>
</TABLE>

<BR>

<TABLE border="0" cellpadding="0" cellspacing="0" width="100%" style="display: none;" id="box1" name="box1">
<TR>
<TD>

<TABLE border="0" cellpadding="1" cellspacing="5" width="100%">

<TR>
<TD colspan="3"><BR>{include file="main/subheader.tpl" title=$lng.lbl_advanced_search_options}</TD>
</TR>

<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_search_in_category}:</TD>
<TD height="10"></TD>
<TD height="10"><SELECT name="posted_data[categoryid]" style="width:70%">
<OPTION value=""></OPTION>
{foreach from=$search_categories item=v}
<OPTION value="{$v.categoryid}" {if $search_prefilled.categoryid eq $v.categoryid}selected{/if}>{$v.category_path}</OPTION>
{/foreach}
</SELECT></TD>
</TR>

<TR>
<TD colspan="2" height="10">&nbsp;</TD>
<TD height="10">
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<TD width="5" nowrap>{$lng.lbl_as}&nbsp;&nbsp;</TD>
<TD width="5"><INPUT type="checkbox" name="posted_data[category_main]"{if $search_prefilled eq "" or $search_prefilled.category_main} checked{/if}></TD><TD nowrap>{$lng.lbl_main_category}&nbsp;&nbsp;</TD>
<TD width="5"><INPUT type="checkbox" name="posted_data[category_extra]"{if $search_prefilled.category_extra} checked{/if}></TD><TD nowrap>{$lng.lbl_additional_category}</TD>
</TR>
</TABLE>
</TD>
</TR>

<TR>
<TD colspan="2" width="10" height="10">&nbsp;</TD>
<TD height="10">
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<TD width="5"><INPUT type="checkbox" name="posted_data[search_in_subcategories]"{if $search_prefilled eq "" or $search_prefilled.search_in_subcategories} checked{/if}></TD><TD nowrap>{$lng.lbl_search_in_subcategories}</TD>
</TR>
</TABLE>
</TD>
</TR>

{if $active_modules.Manufacturers && $manufacturers ne ''}
<TR>
<TD height="10" class="FormButton" nowrap>{$lng.lbl_manufacturers}:</TD>
<TD height="10"></TD>
<TD height="10"><SELECT name="posted_data[manufacturers][]" style="width:70%" multiple size="3">
{foreach from=$manufacturers item=v}
<OPTION value="{$v.manufacturerid}"{if $v.selected eq 'Y'} selected{/if}>{$v.manufacturer}</OPTION>
{/foreach}
</SELECT></TD>
</TR>
{/if}

<TR>
<TD height="10" width="20%" class="FormButton" nowrap>{$lng.lbl_sku}:</TD>
<TD width="10" height="10"><FONT class="CustomerMessage"></FONT></TD>
<TD height="10" width="80%">
<INPUT type="text" maxlength="64" name="posted_data[productcode]" value="{$search_prefilled.productcode}" style="width:70%">
</TD>
</TR>

<TR>
<TD height="10" width="20%" class="FormButton" nowrap>{$lng.lbl_price} ({$config.General.currency_symbol}):</TD>
<TD width="10" height="10"><FONT class="CustomerMessage"></FONT></TD>
<TD height="10" width="80%">
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<TD><INPUT type="text" size="10" maxlength="15" name="posted_data[price_min]" value="{if $search_prefilled eq ""}0.00{else}{$search_prefilled.price_min}{/if}"></TD>
<TD>&nbsp;-&nbsp;</TD>
<TD><INPUT type="text" size="10" maxlength="15" name="posted_data[price_max]" value="{$search_prefilled.price_max}"></TD>
</TR>
</TABLE>
</TD>
</TR>

<TR>
<TD height="10" width="20%" class="FormButton" nowrap>{$lng.lbl_quantity}:</TD>
<TD width="10" height="10"><FONT class="CustomerMessage"></FONT></TD>
<TD height="10" width="80%">
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<TD><INPUT type="text" size="10" maxlength="10" name="posted_data[avail_min]" value="{if $search_prefilled eq ""}0{else}{$search_prefilled.avail_min}{/if}"></TD>
<TD>&nbsp;-&nbsp;</TD>
<TD><INPUT type="text" size="10" maxlength="10" name="posted_data[avail_max]" value="{$search_prefilled.avail_max}"></TD>
</TR>
</TABLE>
</TD>
</TR>

<TR>
<TD height="10" width="20%" class="FormButton" nowrap>{$lng.lbl_weight} ({$config.General.weight_symbol}):</TD>
<TD width="10" height="10"><FONT class="CustomerMessage"></FONT></TD>
<TD height="10" width="80%">
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<TD><INPUT type="text" size="10" maxlength="10" name="posted_data[weight_min]" value="{if $search_prefilled eq ""}0{else}{$search_prefilled.weight_min}{/if}"></TD>
<TD>&nbsp;-&nbsp;</TD>
<TD><INPUT type="text" size="10" maxlength="10" name="posted_data[weight_max]" value="{$search_prefilled.weight_max}"></TD>
</TR>
</TABLE>
</TD>
</TR>

<TR>
<TD>&nbsp;</TD>
</TR>

<TR>
<TD>&nbsp;</TD>
<TD colspan="3"><BR><INPUT type="submit" value="{$lng.lbl_search}"></TD>
</TR>

</TABLE>

</TD>
</TR>
</TABLE>

</FORM>

{if $search_prefilled.need_advanced_options}
<SCRIPT type="text/javascript" language="JavaScript 1.2"><!--
visibleBox('1');
--></SCRIPT>
{/if}


{/capture}
{include file="dialog.tpl" title=$lng.lbl_search_products content=$smarty.capture.dialog extra="width=100%"}

<BR>

<!-- SEARCH FORM DIALOG END -->

{/if}

<!-- SEARCH RESULTS SUMMARY -->

<A name="results"/>

{if $mode eq "search"}
{if $total_items gt "1"}
{$lng.txt_N_results_found|substitute:"items":$total_items}<BR>
{$lng.txt_displaying_X_Y_results|substitute:"first_item":$first_item:"last_item":$last_item}
{elseif $total_items eq "0"}
{$lng.txt_N_results_found|substitute:"items":0}
{/if}
{/if}

{if $mode eq "search" and $products ne ""}

<!-- SEARCH RESULTS START -->

<BR><BR>

{capture name=dialog}

<DIV align="right">{include file="buttons/button.tpl" button_title=$lng.lbl_search_again href="search.php"}</DIV>

{if $total_pages gt 2}
{assign var="navpage" value=$navigation_page}
{/if}


<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">

<FORM action="process_product.php" method="POST" name="processproductform">

<INPUT type="hidden" name="mode" value="update">
<INPUT type="hidden" name="navpage" value="{$navpage}">

<TR>
<TD>

{include file="customer/main/navigation.tpl"}

{include file="customer/main/products.tpl" products=$products}

<BR>

{include file="customer/main/navigation.tpl"}

</TD>
</TR>

</FORM>

</TABLE>

<BR>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_search_results content=$smarty.capture.dialog extra="width=100%"}

{/if}

<BR><BR>
