{* $Id: dhl_ext_countries.tpl,v 1.1.2.1 2006/11/01 08:28:47 max Exp $ *}
{if $dhl_ext_countries}
<table cellspacing="1" cellpadding="2">
<tr>
    <td><label for="dhl_ext_country">{$lng.txt_dhl_ext_countries_note}</label>:</td>
    <td>
<select name="dhl_ext_country" id="dhl_ext_country"{if $onchange} onchange="javascript: document.cartform.submit();"{/if}>
    <option value="">{$lng.lbl_please_select_one}</option>
{foreach from=$dhl_ext_countries item=c}
    <option value="{$c}"{if $c eq $dhl_ext_country} selected="selected"{/if}>{$c}</option>
{/foreach}
</select>
    </td>
</tr>
</table>
{/if}
