{*
$Id: info_panel.tpl 63 2012-10-30 11:56:13Z skot $
vim: set ts=2 sw=2 sts=2 et:
*}
{if $view_info_panel eq "Y"}
  <div id="eucl_panel" class="ui-body ui-body-e">
    <table class="eucl_panel_countdown">
      <tr>
        <td id="eucl_panel_countdown" class="star">&nbsp;</td>
      </tr>
    </table>
    <div id="eucl_panel_msg">{$lng.txt_eu_cookie_law_panel_msg}&nbsp;</div>
    <br />
    
    <div id="eucl_panel_btn" class="ui-grid-a">
      <div class="ui-block-a">
        {include file="customer/buttons/button.tpl" button_title=$lng.lbl_eucl_change_settings tips_title=$lng.lbl_eucl_change_settings href="javascript: return func_change_cookie_settings();" data_mini="true"}
      </div>
      <div class="ui-block-b">
        {include file="customer/buttons/button.tpl" button_title=$lng.lbl_close tips_title=$lng.lbl_close href="javascript: return func_down_eucl_panel();" data_mini="true" data_theme="b"}
      </div>
    </div>
  </div>
{/if}
