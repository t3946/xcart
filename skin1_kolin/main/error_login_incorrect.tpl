{* $Id: error_login_incorrect.tpl,v 1.35 2005/11/30 17:02:36 max Exp $ *}
<p />
{capture name=dialog}
{$lng.txt_login_incorrect}
<br><br>
{include file="main/login_form.tpl}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_authentication content=$smarty.capture.dialog extra='width="100%"'}
