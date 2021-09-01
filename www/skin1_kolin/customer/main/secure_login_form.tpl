{* $Id: secure_login_form.tpl,v 1.9.2.3 2006/12/07 08:28:04 svowl Exp $ *}
{$lng.txt_secure_login_form}
<p />
{capture name=dialog}
<table border="0">
<form action="{$https_location}/include/login.php" method="post" name=secureform>
<tr> 
<td height="10" width="78" class="FormButton">{$lng.lbl_username}</td>
<td width="10" height="10"><font class="Star">*</font></td>
<td width="282" height="10"> 
<input type="text" name="username" size="30" value="{$username|escape}"/>
</td>
</tr>
<tr> 
<td height="10" width="78" class="FormButton">{$lng.lbl_password}</td>
<td width="10" height="10"><font class="Star">*</font></td>
<td width="282" height="10"> 
<input type="password" name="password" size="30" />
{if $active_modules.Simple_Mode ne "" and $usertype ne "C"}
<input type="hidden" name="usertype" value="P" />
{else}
<input type="hidden" name="usertype" value="{$usertype}" />
{/if}
<input type="hidden" name="redirect" value="{$redirect}" />
<input type="hidden" name="mode" value="login" />
</td>
</tr>
<tr> 
<td height="10" width="78" class="FormButton"></td>
<td width="10" height="10">&nbsp;</td>
<td width="282" height="10" class="ErrorMessage">
</td>
</tr>
{if $active_modules.Image_Verification and $show_antibot.on_login eq 'Y' and $login_antibot_on}
{include file="modules/Image_Verification/spambot_arrest.tpl" mode="advanced" id=$antibot_sections.on_login}
{/if}
<tr>
	<td height="10" width="78" class="FormButton"></td>
	<td width="10" height="10">&nbsp;</td>
	<td width="282" height="10" class="ErrorMessage">{if $antibot_err}{$lng.msg_err_antibot}{/if}</td>
</tr>

<tr> 
<td height="10" width="78" class="FormButton"></td>
<td width="10" height="10" class="FormButton">&nbsp;</td>
<td width="282" height="10">
{if $js_enabled}
{include file="buttons/submit.tpl" href="javascript:document.secureform.submit()" js_to_href="Y"}
{else}
{include file="submit_wo_js.tpl" value=$lng.lbl_submit}
{/if}
</td>
</tr>
</form>
</table>
<p />
{/capture}
{include file="dialog.tpl" title=$lng.lbl_secure_login_form content=$smarty.capture.dialog extra='width="100%"'}
