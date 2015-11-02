{* $Id: cc_cpac.tpl,v 1.8.2.4 2006/09/14 08:04:51 max Exp $ *}
<h3>LaCaixa</h3>
{$lng.txt_cc_configure_top_text}
<p />
{capture name=dialog}
<form action="cc_processing.php?cc_processor={$smarty.get.cc_processor|escape:"url"}" method="post">
<center>
<table cellspacing="10">

<tr>
<td>{$lng.lbl_cc_cpac_mcode}:</td>
<td><input type="text" name="param01" size="9" maxlength="9" value="{$module_data.param01|escape}" /></td>
</tr>

<tr>
<td>{$lng.lbl_cc_cpac_terminal}:</td>
<td><input type="text" name="param04" size="3" maxlength="3" value="{$module_data.param04|escape}" /></td>
</tr>

<tr>
<td>{$lng.lbl_cc_cpac_secret_code}:</td>
<td><input type="text" name="param05" size="32" value="{$module_data.param05|escape}" /></td>
</tr>


<tr>
<td>{$lng.lbl_cc_currency}:</td>
<td>
<select name="param02">
<option value="978"{if $module_data.param02 eq "978"} selected="selected"{/if}>Euro</option>
<option value="840"{if $module_data.param02 eq "840"} selected="selected"{/if}>Dollar</option>
</select>
</tr>
<tr>
<td>{$lng.lbl_cc_cpac_language}:</td>
<td>
<select name="param03">
	<option value="001"{if $module_data.param03 eq "001"} selected="selected"{/if}>Spanish</option>
	<option value="002"{if $module_data.param03 eq "002"} selected="selected"{/if}>English</option>
	<option value="003"{if $module_data.param03 eq "003"} selected="selected"{/if}>Catalan</option>
	<option value="004"{if $module_data.param03 eq "004"} selected="selected"{/if}>French</option>
	<option value="005"{if $module_data.param03 eq "005"} selected="selected"{/if}>German</option>
	<option value="006"{if $module_data.param03 eq "006"} selected="selected"{/if}>Dutch</option>
	<option value="007"{if $module_data.param03 eq "007"} selected="selected"{/if}>Italian</option>
	<option value="008"{if $module_data.param03 eq "008"} selected="selected"{/if}>Swedish</option>
	<option value="009"{if $module_data.param03 eq "009"} selected="selected"{/if}>Portuguese</option>
	<option value="010"{if $module_data.param03 eq "010"} selected="selected"{/if}>Valencian</option>
</select>
</td>
</tr>

<tr>
<td>{$lng.lbl_cc_testlive_mode}:</td>
<td>
<select name="testmode">
<option value="Y"{if $module_data.testmode eq "Y"} selected="selected"{/if}>{$lng.lbl_cc_testlive_test}</option>
<option value="N"{if $module_data.testmode eq "N"} selected="selected"{/if}>{$lng.lbl_cc_testlive_live}</option>
</select>
</td>
</tr>


<tr>
<td>{$lng.lbl_cc_order_prefix}:</td>
<td><input type="text" name="param06" size="32" value="{$module_data.param06|escape}" /><br />{$lng.lbl_cc_cpac_digits_only}</td>
</tr>
</table>
<p />
<input type="submit" value="{$lng.lbl_update|strip_tags:false|escape}" />
</form>
</center>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_cc_settings content=$smarty.capture.dialog extra='width="100%"'}
