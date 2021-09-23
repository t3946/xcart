{*
$Id: popup_cookie_settings.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{capture name="eu_cookie_template"}
  <div class="eucl_dialog">
    <div class="cookie_settings_description">
      {$lng.txt_cookie_settings_description}
    </div>
    <h3>{$lng.lbl_eucl_strictly_necessary}:</h3>
    <select data-role="slider" disabled="disabled">
      <option value="Y" selected="selected">{$lng.lbl_on}</option>
      <option value="N">{$lng.lbl_off}</option>
    </select>
    <div class="text-block">
      {$lng.txt_eucl_strictly_necessary_desription}
    </div>
    <form action="popup_cookie_settings.php" method="post">
      <input type="hidden" name="mode" value="change_settings" />
      <h3>{$lng.lbl_eucl_functional}:</h3>
      <select name="functional_access" data-role="slider">
        <option value="Y"{if $cookie_access[1] eq "Y"} selected="selected"{/if}>{$lng.lbl_on}</option>
        <option value="N"{if $cookie_access[1] neq "Y"} selected="selected"{/if}>{$lng.lbl_off}</option>
      </select>
      <div class="text-block">
        {$lng.txt_eucl_functional_desription}
      </div>
      <h3>{$lng.lbl_eucl_other}:</h3>
      <select name="other_access" data-role="slider">
        <option value="Y"{if $cookie_access[2] eq "Y"} selected="selected"{/if}>{$lng.lbl_on}</option>
        <option value="N"{if $cookie_access[2] neq "Y"} selected="selected"{/if}>{$lng.lbl_off}</option>
      </select>
      <div class="text-block">
        {$lng.txt_eucl_other_cookie_desription}
      </div>
      <div class="save_close_btn">{include file="customer/buttons/button.tpl" type="input" button_title=$lng.lbl_save_and_close additional_button_class="light-button"}</div>
    </form>
  </div>
{/capture}
{include file="customer/help/popup_info.tpl" pre=$smarty.capture.eu_cookie_template popup_title=$lng.lbl_eucl_change_cookie_settings}