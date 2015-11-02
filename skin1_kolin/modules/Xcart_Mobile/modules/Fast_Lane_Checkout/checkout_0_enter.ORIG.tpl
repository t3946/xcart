{*
$Id: checkout_0_enter.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
<h1>{$lng.lbl_personal_details}</h1>
{if $active_modules.Image_Verification and $show_antibot.on_login eq 'Y' and $login_antibot_on}
  {assign var="is_antibot" value="Y"}
  {assign var="is_ajax_request" value="Y"}
{/if}
{capture name=dialog}
  <div class="text-block">{$lng.txt_login_incorrect}</div>
  {include file="customer/main/login_form.tpl" is_flc=true}
{/capture}
{include file="customer/dialog.tpl" title=$lng.lbl_returning_customer content=$smarty.capture.dialog additional_class="flc-left-dialog`$left_ext_additional_class`"}
{if $paypal_express_active}
  {include file="payments/ps_paypal_pro_express_checkout.tpl"}
{/if}
<div id="flc-register-dialog"{if $av_error ne 1 and $reg_error eq '' and $smarty.get.no_script ne 'Y'} style="display: none;"{/if}>
  {include file="customer/main/register.tpl"}
</div>