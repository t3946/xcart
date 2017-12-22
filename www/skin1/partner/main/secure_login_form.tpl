{* $Id: secure_login_form.tpl,v 1.6.2.1 2004/09/28 09:06:54 max Exp $ *}
{$lng.txt_secure_login_form}
<P>
{capture name=dialog}
<TABLE border="0">
<FORM action="{$https_location}/include/login.php" method="post" name=secureform>
<TR> 
<TD height="10" width="78" class="FormButton">{$lng.lbl_login}</TD>
<TD width="10" height="10"><FONT class="Star">*</FONT></TD>
<TD width="282" height="10"> 
<INPUT type="text" name="username" size="30">
</TD>
</TR>
<TR> 
<TD height="10" width="78" class="FormButton">{$lng.lbl_password}</TD>
<TD width="10" height="10"><FONT class="Star">*</FONT></TD>
<TD width="282" height="10"> 
<INPUT type="password" name="password" size="30">
{if $active_modules.Simple_Mode ne "" and $usertype ne "C" and $usertype ne "B"}
<INPUT type="hidden" name="usertype" value="P">
{else}
<INPUT type="hidden" name="usertype" value="{$usertype}">
{/if}
<INPUT type="hidden" name="redirect" value="{$redirect}">
<INPUT type="hidden" name="mode" value="login">
</TD>
</TR>
<TR> 
<TD height="10" width="78" class="FormButton"></TD>
<TD width="10" height="10">&nbsp;</TD>
<TD width="282" height="10" class="ErrorMessage">
</TD>
</TR>
<TR> 
<TD height="10" width="78" class="FormButton"></TD>
<TD width="10" height="10" class="FormButton">&nbsp;</TD>
<TD width="282" height="10">
{if $js_enabled}
{include file="buttons/submit.tpl" href="javascript: document.secureform.submit()" js_to_href="Y"}
{else}
{include file="submit_wo_js.tpl" value=$lng.lbl_submit}
{/if}
</TD>
</TR>
</FORM>
</TABLE>
<P>
{/capture}
{include file="dialog.tpl" title=$lng.lbl_secure_login_form content=$smarty.capture.dialog extra="width=100%"}
