{* $Id: checkout_0_enter.tpl,v 1.7.2.9 2006/12/07 08:28:08 svowl Exp $ *}
<br>
{*
CHECKOUT: STEP 0 (Authorization/Registration)
*}
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
{if $active_modules.Image_Verification and $show_antibot.on_login eq 'Y' and $login_antibot_on}
{assign var="is_antibot" value="Y"}
{/if}
<td class="{if $is_antibot eq 'Y'}FLCDialogCellAntibot{else}FLCDialogCell{/if}">

<a href="#" class="VertMenuItems" onclick="javascript: document.getElementById('reg_dlg').style.display = (document.getElementById('reg_dlg').style.display == '') ? 'none' : '';"><center>{$lng.lbl_flc_new_customer_link}</center></a>

{capture name=dialog}

<div id="reg_dlg">

{******************** REGISTER FORM: BEGIN ***********************}

{$lng.txt_login_incorrect}

{assign var="display_mode" value="checkout"}

{include file="main/login_form.tpl"}

{******************** REGISTER FORM: END ************************}
</div>

{******************** LOGIN FORM: BEGIN ************************}

{include file="customer/main/register.tpl" is_flc=true}

{******************** LOGIN FORM: END ************************}
{/capture}
{include file="dialog_FLC.tpl" title='' content=$smarty.capture.dialog extra='class="FLCDialog"' is_flc_dialog=true align=center}

</td>
</tr>
</table>

{if $paypal_express_active}
{include file="payments/ps_paypal_pro_express_checkout.tpl"}
{/if}

{if $av_error ne 1 && $js_enabled && $top_message.reg_error eq '' && $smarty.get.toreg eq ''}
<script type="text/javascript">
<!--
document.getElementById('reg_dlg').style.display = 'none';
-->
</script>
{/if}
{if $top_message.reg_error ne '' or $av_error eq 1 and $smarty.get.toreg eq 1}
<script type="text/javascript">
<!--
self.location.hash = 'regdlg';
-->
</script>
{/if}
