{* $Id: offer_details.tpl,v 1.11 2005/12/07 14:07:30 max Exp $ *}

{math equation="x+1" x=$config.Company.end_year assign="end_year"}

<table cellpadding="3" cellpadding="1" width="100%">
<tr>
	<td width="30%" class="FormButton">{$lng.lbl_sp_offer_short_name}:</td>
	<td width="70%"><input type="text" name="offer[name]" value="{$offer.name}" size="50" style="width: 90%;" /></td>
	<td width="10">&nbsp;</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_active}:</td>
	<td>
	<select name="offer[avail]">
		<option value="N"{if $offer.avail eq "N"} selected="selected"{/if}>{$lng.lbl_no}</option>
		<option value="Y"{if $offer.avail eq "Y"} selected="selected"{/if}>{$lng.lbl_yes}</option>
	</select>
	</td>
	<td>&nbsp;</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_sp_offer_start_date}:</td>
	<td>{html_select_date prefix="Start" time=$offer.offer_start start_year=$config.Company.start_year end_year=$end_year}</td>
	<td>&nbsp;</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_sp_offer_end_date}:</td>
	<td>{html_select_date prefix="End" time=$offer.offer_end start_year=$config.Company.start_year end_year=$end_year}</td>
	<td>&nbsp;</td>
</tr>

<tr>
	<td class="FormButton">{$lng.lbl_sp_display_short_promo}:</td>
	<td>
	<select name="offer[show_short_promo]">
		<option value="Y"{if $offer.show_short_promo eq "Y"} selected="selected"{/if}>{$lng.lbl_yes}</option>
		<option value="N"{if $offer.show_short_promo eq "N"} selected="selected"{/if}>{$lng.lbl_no}</option>
	</select>
	</td>
	<td>&nbsp;</td>
</tr>

<tr>
	<td colspan="3" class="SubmitBox"><input type="submit" value=" {$lng.lbl_update} "></td>
</tr>
</table>
