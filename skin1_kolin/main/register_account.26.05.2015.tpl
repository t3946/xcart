{* $Id: register_account.tpl,v 1.19.2.5 2006/12/25 13:32:08 max Exp $ *}
{if $hide_account_section ne "Y"}

{if $hide_header eq ""}
<tr>
<td colspan="3" class="RegSectionTitle">
{if $anonymous ne "" and $config.General.disable_anonymous_checkout ne "Y"}
<table cellpadding="0" cellspacing="0">
<tr>
	<td><label for="ship2diff" class="RegSectionTitle">{$lng.lbl_username_n_password}</label></td>
	<td>&nbsp;</td>
	<td><input type="checkbox" id="account_box_check" name="account_box_check" value="Y" onclick="javascript: document.getElementById('account_box').style.display = this.checked ? '' : 'none';"{if $userinfo.login || $userinfo.uname} checked="checked"{/if} /></td>
</tr>
</table>
{else}
{$lng.lbl_setup_an_account}
{/if}
<hr size="1" noshade="noshade" /></td>
</tr>
{/if}

{if $anonymous ne "" and $config.General.disable_anonymous_checkout ne "Y"}

{* Anonymous account *}

</tbody>
<tbody id="account_box"{if !$userinfo.login && !$userinfo.uname} style="display: none"{/if}>

<tr>
<td colspan="3">{$lng.txt_anonymous_account_msg}</td>
</tr>

{/if}

{if $userinfo.login eq $login and $login and $userinfo.usertype ne "C"}

{* Display membership level *}

<tr style="display: none;">
<td>
<input type="hidden" name="membershipid" value="{$userinfo.membershipid}" />
<input type="hidden" name="pending_membershipid" value="{$userinfo.pending_membershipid}" />
</td>
</tr>

{else}

<tr>
<td align="right">Allow operate as membership</td>
<td></td>
<td>
{foreach from=$all_memberships item=vm key=km}

  {if $vm.membershipid ne $userinfo.membershipid}
	<input type="checkbox" name="allow_operate_as_membership[{$vm.membershipid}]" value="Y"
{if $userinfo.allow_operate_as_membership_arr ne ""}
{foreach from=$userinfo.allow_operate_as_membership_arr item=v_a key=k_a}
{if $v_a eq $vm.membershipid} checked="checked"{/if} 
{/foreach}
{/if}
	/> {if $vm.area eq "A"}Admin{elseif $vm.area eq "P"}Provider{/if}/{$vm.membership}<br />
  {/if}

{/foreach}

</td>
</tr>


{if $config.General.membership_signup eq "Y" and ($usertype eq "C" or ($active_modules.Simple_Mode ne "" and $usertype eq "P") or $usertype eq "A" or $usertype eq "B") && $membership_levels}
{include file="admin/main/membership_signup.tpl"}
{/if}

{if $usertype eq "A" or ($usertype eq "P" and $active_modules.Simple_Mode ne "") && $membership_levels}
{include file="admin/main/membership.tpl"}
{/if}

{if $usertype eq "A" and $smarty.get.usertype eq "P" and $userinfo.login ne ''}
{if $active_modules.Manufacturers ne ""}
<tr>
    <td colspan="3">{$lng.txt_operator_permission}</td>
</tr>
<tr valign="middle">
<td align="right">{$lng.lbl_allowed_distributors}</td>
<td></td>
<td nowrap="nowrap">
<select name="add_manufacturerids[]" multiple size="5">
	{foreach from=$manufacturers item=v}
		<option value='{$v.manufacturerid}' {if $v.selected eq 'Y'} selected="selected"{/if}>{$v.manufacturer}</option>
	{/foreach}
	</select>
</td>
</tr>
{/if}
{/if}
{* /Display membership level *}

{/if}

{if $anonymous ne "" and $config.General.disable_anonymous_checkout ne "Y"}

{* Anonymous account *}

<tr>
<td align="right">{$lng.lbl_username}</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<input type="text" id="uname" name="uname" size="32" maxlength="32" value="{if $userinfo.uname}{$userinfo.uname}{else}{$userinfo.login}{/if}" />
{if ($reg_error ne "" && $reg_error ne 'A' && $userinfo.uname eq "" && $userinfo.login eq "") || $reg_error eq "U"}<font class="Star">&lt;&lt;</font>{/if}
</td>
</tr>

<tr>
<td align="right">{$lng.lbl_password}</td>
<td>&nbsp;</td>
<td nowrap="nowrap"><input type="password" name="passwd1" size="32" maxlength="64" value="{$userinfo.passwd1}" />
</td>
</tr>

<tr>
<td align="right">{$lng.lbl_confirm_password}</td>
<td>&nbsp;</td>
<td nowrap="nowrap"><input type="password" name="passwd2" size="32" maxlength="64" value="{$userinfo.passwd2}" />
</td>
</tr>

</tbody>
<tbody>

{* /Anonymous account *}

{else}

{* NOT anonymous account *}

<tr>
<td align="right">{$lng.lbl_username}</td>
<td class="Star">*</td>
<td nowrap="nowrap">
{if $userinfo.login ne "" || ($login eq $userinfo.uname && $login ne '')}
<b>{$userinfo.login|default:$userinfo.uname}</b>
<input type="hidden" name="uname" value="{$userinfo.login|default:$userinfo.uname}" />
{else}
<input type="text" id="uname" name="uname" size="32" maxlength="32" value="{if $userinfo.uname}{$userinfo.uname}{else}{$userinfo.login}{/if}" />
{if ($reg_error ne "" and $userinfo.uname eq "" and $userinfo.login eq "") or $reg_error eq "U"}<font class="Star">&lt;&lt;</font>{/if}
{/if}
</td>
</tr>

<tr>
<td align="right">{$lng.lbl_password}</td>
<td><font class="Star">*</font></td>
<td nowrap="nowrap"><input type="password" id="passwd1" name="passwd1" size="32" maxlength="64" value="{$userinfo.passwd1}" />
{if $reg_error ne "" and $userinfo.passwd1 eq ""}<font class="Star">&lt;&lt;</font>{/if} 
</td>
</tr>

<tr>
<td align="right">{$lng.lbl_confirm_password}</td>
<td class="Star">*</td>
<td nowrap="nowrap"><input type="password" id="passwd2" name="passwd2" size="32" maxlength="64" value="{$userinfo.passwd2}" />
{if $reg_error ne "" and $userinfo.passwd2 eq ""}<font class="Star">&lt;&lt;</font>{/if} 
</td>
</tr>

{* / NOT anonymous account *}

{/if}

{if (($active_modules.Simple_Mode ne "" and $usertype eq "P") or $usertype eq "A") and ($userinfo.uname && $userinfo.uname ne $login or !$userinfo.uname and $userinfo.login ne $login)}

{if $userinfo.status ne "A"}{* only for non-anonymous users *}
<tr valign="middle">
<td align="right">{$lng.lbl_account_status}:</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<select name="status">
<option value="N"{if $userinfo.status eq "N"} selected="selected"{/if}>{$lng.lbl_account_status_suspended}</option>
<option value="Y"{if $userinfo.status eq "Y"} selected="selected"{/if}>{$lng.lbl_account_status_enabled}</option>
{if $active_modules.XAffiliate ne "" and ($userinfo.usertype eq "B" or $smarty.get.usertype eq "B")}
<option value="Q"{if $userinfo.status eq "Q"} selected="selected"{/if}>{$lng.lbl_account_status_not_approved}</option>
<option value="D"{if $userinfo.status eq "D"} selected="selected"{/if}>{$lng.lbl_account_status_declined}</option>
{/if}
</select>
</td>
</tr>

{if $display_activity_box eq "Y"}
<tr valign="middle">
<td align="right">{$lng.lbl_account_activity}:</td>
<td>&nbsp;</td>
<td nowrap="nowrap">
<select name="activity">
<option value="Y"{if $userinfo.activity eq "Y"} selected="selected"{/if}>{$lng.lbl_account_activity_enabled}</option>
<option value="N"{if $userinfo.activity eq "N"} selected="selected"{/if}>{$lng.lbl_account_activity_disabled}</option>
</select>
</td>
</tr>
{/if}

{/if}{* $userinfo.status ne "A" *}

<tr valign="middle">
	<td colspan="2">&nbsp;</td>
	<td nowrap="nowrap">

<table>
<tr>
	<td><input type="checkbox" id="change_password" name="change_password" value="Y"{if $userinfo.change_password eq "Y"} checked="checked"{/if} /></td>
	<td><label for="change_password">{$lng.lbl_reg_chpass}</label></td>
</tr>
</table>

	</td>
</tr>

{/if}

{else}
<tr style="display: none;">
<td>
<input type="hidden" name="uname" value="{$userinfo.login|default:$userinfo.uname}" />
<input type="hidden" name="passwd1" value="{$userinfo.passwd1}" />
<input type="hidden" name="passwd2" value="{$userinfo.passwd2}" />
</td>
</tr>
{/if}
