{*
$Id: login_form.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if !$is_flc}
  <h2>{$lng.lbl_login}</h2>
{/if}
<form action="{$authform_url}" method="post" name="authform" data-ajax="false">
  <input type="hidden" name="{$XCARTSESSNAME}" value="{$XCARTSESSID}" />
  <input type="hidden" name="is_remember" value="{$is_remember}" />
  <input type="hidden" name="mode" value="login" />
  <input placeholder="{$login_field_name}" type="{if $login_field_name|@has_string:'mail'}email{else}text{/if}" id="username" class="login-form" name="username" value="{#default_login#|default:$username|escape}" />
  <input placeholder="{$lng.lbl_password}" type="password" id="password" class="login-form" name="password" maxlength="64" value="{#default_password#}" />
  {if $active_modules.Image_Verification and $show_antibot.on_login eq 'Y' and $login_antibot_on and $main ne 'disabled'}
    {include file="modules/Image_Verification/spambot_arrest.tpl" mode="simple_column" id=$antibot_sections.on_login button_code=$submit_button}
    {if $antibot_err}
      {$lng.msg_err_antibot}
    {/if}
  {/if}
  <div class="ui-grid-solo">
    <div class="ui-block-a">
      {include file="customer/buttons/submit.tpl" type="input" additional_button_class="main-button" data_inline="false"}
    </div>
  </div>
  <div class="ui-grid-a">
    <div class="ui-block-a">
      <a href="{$current_location}/help.php?section=Password_Recovery" data-role="button">{$lng.lbl_recover_password}</a>
    </div>
    <div class="ui-block-b">
      {if $is_flc}
        <span data-role="button" data-theme="a" onclick="$('#flc-register-dialog').toggle(); $.mobile.silentScroll($('#flc-register-dialog').offset().top);">{$lng.lbl_register_new}</span>
      {else}
        <a href="{$current_location}/register.php" data-role="button" data-theme="a">{$lng.lbl_register_new}</a>
      {/if}
    </div>
  </div>

</form>