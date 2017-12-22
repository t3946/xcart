{* $Id: wizard_step.tpl,v 1.8 2006/03/28 08:21:09 max Exp $ *}

{include file="modules/Special_Offers/offer_nav.tpl"}

<form action="offers.php" method="post" name="wizardform" enctype="multipart/form-data">
<input type="hidden" name="mode" value="{$mode}" />
<input type="hidden" name="action" value="update" />
<input type="hidden" name="offerid" value="{$offerid}" />

<table cellpadding="3" cellspacing="1" width="100%">
<tr>
	<td>

{if $fill_error eq "Y"}
<table align="center" border="0">
<tr>
	<td><img src="{$ImagesDir}/log_type_Warning.gif" alt="" /></td>
	<td>
<font class="Star" align="center">
{if $mode eq "conditions"}
{$lng.txt_sp_warn_incomplete_conditions}
{elseif $mode eq "bonuses"}
{$lng.txt_sp_warn_incomplete_bonuses}
{/if}
</font>
	</td>
</tr>
</table>

<br />
{/if}

{if $mode eq "conditions"}
{$lng.txt_sp_wiz_conditions_title}
<br /><br />
{include file="modules/Special_Offers/wizard_step_w_list.tpl" items=$conditions}
{elseif $mode eq "bonuses"}
{$lng.txt_sp_wiz_bonuses_title}
<br /><br />
{include file="modules/Special_Offers/wizard_step_w_list.tpl" items=$bonuses}
{elseif $mode eq "promo"}
{include file="modules/Special_Offers/offer_languages.tpl"}
{elseif $mode eq "status"}
{include file="modules/Special_Offers/offer_status.tpl"}
{else}
{include file="modules/Special_Offers/offer_details.tpl"}
{/if}

	</td>
</tr>
</table>

<!-- wizard navigation -->
<hr class="Line" size="1" />

<table align="center" cellspacing="10">
<tr>
{if $mode ne "conditions"}
	<td><input type="submit" name="wzBack" value=" {$lng.lbl_sp_back} "></td>
{/if}
{if $mode ne "status"}
	<td><input type="submit" name="wzNext" value=" {$lng.lbl_sp_next} "></td>
{/if}
</tr>
</table>

</form>
