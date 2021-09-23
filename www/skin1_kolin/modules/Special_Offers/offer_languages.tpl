{* $Id: offer_languages.tpl,v 1.10.2.1 2006/11/15 11:49:27 max Exp $ *}
{include file="main/include_js.tpl" src="main/popup_image_selection.js"}

{if $active_modules.HTML_Editor}
{include file="modules/HTML_Editor/editor.tpl"}
{/if}

<!-- PROMO BLOCKS -->
<table>
{if $all_languages_cnt > 1}
<tr>
	<td colspan="3" align="right" valign="middle">{$lng.lbl_language}:
	<select name="offer_lng_code" onchange="self.location='offers.php?mode=promo&offerid={$offerid}&offer_lng_code='+document.wizardform.offer_lng_code.value;">
{foreach from=$avail_languages item=ol}
		<option value="{$ol.code}"{if $ol.code eq $offer_lng_code} selected="selected"{/if}>{$ol.language}</option>
{/foreach}
	</select>
	</td>
</tr>
{elseif $offer_lng_code ne ''}
<tr style="display: none;">
	<td><input type="hidden" name="offer_lng_code" value="{$offer_lng_code}" /></td>
</tr>
{/if}

<tr>
	<td colspan="3">

<input type="hidden" name="img_del_code" value="" />
<table cellpadding="3" cellspacing="0" width="100%">
<tr>
	<td><b>{$lng.lbl_sp_promo_short}:</b></td>
</tr>
<tr>
	<td>

<table cellpadding="3" width="100%">
<tr>
	<td>{$lng.lbl_sp_promo_image}</td>
{if $offer_lng.promo_short_img eq '1'}{assign var="no_delete" value=""}{else}{assign var="no_delete" value="Y"}{/if}
	<td width="95%">
{include file="main/edit_image.tpl" type="S" id=$offer_lng.code|cat:$offerid delete_url="offers.php?mode=promo&action=delete_image&img_del_code=`$offer_lng.code``$offerid`&offerid=`$offerid`&offer_lng_code=`$offer_lng.code`" button_name=$lng.lbl_update no_delete=$no_delete}
	</td>
</tr>
<tr>
	<td>{$lng.lbl_sp_promo_text}</td>
	<td width="95%">
{include file="main/textarea.tpl" name="offer_lng[`$offer_lng.code`][promo_short]" cols=40 rows=3 data=$offer_lng.promo_short width="100%" style="width: 100%;" btn_rows=4}
<br />
{$lng.txt_sp_promo_short_note|substitute:"customer_url":$catalogs.customer:"offerid":$offer.offerid}
	</td>
</tr>
</table>

	</td>
</tr>

<tr>
	<td><br /><b>{$lng.lbl_sp_promo_long}:</b></td>
</tr>
<tr>
	<td>
{include file="main/textarea.tpl" name="offer_lng[`$offer_lng.code`][promo_long]" cols=50 rows=10 data=$offer_lng.promo_long width="100%" style="width: 100%;" btn_rows=4}
	</td>
</tr>
</table>

	</td>
</tr>

<tr align="left">
	<td colspan="3">

<br /><br />

<table width="100%">
<tr>
	<td><input type="submit" value=" {$lng.lbl_update} "></td>
</tr>
</table>

<hr />
{$lng.txt_sp_promo_blocks_detailed_descr}
	</td>
</tr>

</table>
