{* $Id: checkout_0_enter.tpl,v 1.7.2.9 2006/12/07 08:28:08 svowl Exp $ *}
<br>
{*
CHECKOUT: STEP 0 (Authorization/Registration)
*}
{*
<h3><center>{$lng.lbl_my_account}</center></h3>
*}
<table cellpadding="0" cellspacing="5" width="100%">
<tr>
{if $active_modules.Image_Verification and $show_antibot.on_login eq 'Y' and $login_antibot_on}
{assign var="is_antibot" value="Y"}
{/if}
<td class="{if $is_antibot eq 'Y'}FLCDialogCellAntibot{else}FLCDialogCell{/if}">
{capture name=dialog}

{$lng.txt_login_incorrect}

<br />
<br />

{******************** LOGIN FORM: BEGIN ************************}

{include file="main/login_form.tpl" is_flc=true}

{******************** LOGIN FORM: END ************************}
{/capture}
{include file="dialog.tpl" title=$lng.lbl_returning_customer content=$smarty.capture.dialog extra='class="FLCDialog"' is_flc_dialog=true align=center}
</td>

<td class="{if $is_antibot eq 'Y'}FLCDialogCellAntibot{else}FLCDialogCell{/if}">

{capture name=dialog}

<font class="FLC_Register">{*{$lng.lbl_flc_new_customer_text} *}
<br><br>
<a href="#regdlg" class="VertMenuItems" onclick="javascript: document.getElementById('reg_dlg').style.display = (document.getElementById('reg_dlg').style.display == '') ? 'none' : '';"><center>{$lng.lbl_flc_new_customer_link}</center></a></font>

{/capture}
{include file="dialog.tpl" title=$lng.lbl_new_customer content=$smarty.capture.dialog extra='class="FLCDialog"' valign="top" is_flc_dialog=true align="center"}

</td>
</tr>
</table>

{*<br />*}
{if $paypal_express_active}
{include file="payments/ps_paypal_pro_express_checkout.tpl"}
{/if}

{*<br />
<br />*}

<div id="reg_dlg">

<a name="regdlg"></a>

{******************** REGISTER FORM: BEGIN ************************}

{include file="customer/main/register.tpl"}

{******************** REGISTER FORM: END ************************}
</div>
{if $av_error ne 1 && $js_enabled && $top_message.reg_error eq ''}
<script type="text/javascript">
<!--
document.getElementById('reg_dlg').style.display = 'none';
-->
</script>
{/if}
{if $top_message.reg_error ne '' or $av_error eq 1}
<script type="text/javascript">
<!--
self.location.hash = 'regdlg';
-->
</script>
{/if}
