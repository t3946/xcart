{* $Id: login_form.tpl,v 1.6.2.4 2006/12/07 08:28:06 svowl Exp $ *}
{if $config.Security.use_https_login eq "Y" and $usertype eq "C"}
{assign var="form_url" value=$https_location}
{else}
{assign var="form_url" value=$current_location}
{/if}
<form action="{$form_url}/include/login.php" method="post" name="errorform">
<input type="hidden" name="is_remember" value="{$is_remember}" />

<table>
<tr> 
	<td height="10" width="176" align="right" class="FormButton">{$lng.lbl_login}</td>
	<td width="18" height="10"></td>
	<td width="282" height="10"><input type="text" name="username" size="33" value="{#default_login#|default:$username}" /></td>
</tr>
<tr> 
	<td height="10" width="176" align="right" class="FormButton">{$lng.lbl_password}</td>
	<td width="18" height="10"></td>
	<td width="282" height="10"><input type="password" name="password" size="33" maxlength="64" value="{#default_password#}" />
{if $active_modules.Simple_Mode ne "" and $usertype ne "C" and $usertype ne "B"}
<input type="hidden" name="usertype" value="P" />
{else}
<input type="hidden" name="usertype" value="{$usertype}" />
{/if}
<input type="hidden" name="redirect" value="{$redirect}" />
<input type="hidden" name="mode" value="login" />
</td>
</tr>

{if $main eq "login_incorrect"}
<tr> 
	<td height="10" width="176" align="right" class="FormButton"></td>
	<td width="18" height="10">&nbsp;</td>
	<td width="282" height="10" class="ErrorMessage">{ if $main eq "login_incorrect"}{$lng.err_invalid_login}{/if}</td>
</tr>
{/if}

{if $active_modules.Image_Verification and $show_antibot.on_login eq 'Y' and $login_antibot_on}
{include file="modules/Image_Verification/spambot_arrest.tpl" mode="advanced" id=$antibot_sections.on_login}
{/if}
{if $antibot_err}
<tr>
	<td height="10" width="176" align="right" class="FormButton"></td>
	<td width="18" height="10">&nbsp;</td>
	<td width="282" height="10" class="ErrorMessage">{if $antibot_err}{$lng.msg_err_antibot}{/if}</td>
</tr>
{/if}

<tr> 
<td height="10" width="176" align="right" class="FormButton"></td>
<td width="18" height="10" class="FormButton"></td>


{*<td width="282" height="10" colspan=3*}

<td width="282" height="10" colspan="3">

{if $js_enabled}
{include file="buttons/submit.tpl" href="javascript:document.errorform.submit()" js_to_href="Y" type="input" style="button" b="1"}
{include file="buttons/button.tpl" href="help.php?section=Password_Recovery" button_title=$lng.lbl_recover_password}
{else}
{include file="submit_wo_js.tpl" value=$lng.lbl_submit}
{/if}
</td>
</tr>

</table>

</form>

{if $usertype eq "C" && !$is_flc && $display_mode ne "checkout"}
<br />{$lng.txt_new_account_msg}
{elseif $usertype eq "P"}
<br />{$lng.txt_create_account_msg}
{/if}
