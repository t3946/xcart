{*
$Id: configuration.tpl 63 2012-10-30 11:56:13Z skot $
*}
<tr style="display: none;">
  <td colspan="3">
    <style type="text/css">
      {include file="modules/Xcart_Mobile/admin/main.css"}
    </style>
    {literal}
      <script type="text/javascript">
        (function($) {
          $.fn.toggleDisabled = function() {
            return this.each(function() {
              this.disabled = !this.disabled;
            });
          };
        })(jQuery);
      </script>
    {/literal}
  </td>
</tr>
<tr>
  <td colspan="2" class="TableSeparator">
    <h2 class="mobile-skin-header">{$lng.lbl_adm_xcart_mobile_custom_html_content}</h2>
  </td>
  <td style="text-align: right;">
    {if $all_languages_cnt > 1}
      {$lng.lbl_language}: {include file="main/language_selector_short.tpl" script="configuration.php?option=Xcart_Mobile&"}
    {/if}
    <input type="hidden" name="shop_language" value="{$shop_language}" />
  </td>
</tr>
<tr>
  <td colspan="3">
    <div class="mobile-skin-texarea-title">
      {$lng.txt_adm_xcart_mobile_custom_content_title}:
    </div>
    {include file="main/textarea.tpl" name="gpg_key[xcart_mobile_header_text]" data=$xcart_mobile_config.header_text cols=45 rows=22 width="100%" btn_rows=4}
    <label><input type="checkbox" name="xcart_mobile_config[parse_smarty]" value="Y"{if $xcart_mobile_config.parse_smarty eq 'Y'} checked="checked"{/if} />{$lng.lbl_adm_xcart_mobile_parse_smarty}</label>
  </td>
</tr>
<tr>
  <td colspan="3" class="TableSeparator">
    <h2 class="mobile-skin-header">{$lng.lbl_adm_xcart_mobile_settings}</h2>
  </td>
</tr>
<tr>
  <td colspan="3" class="TableSeparator">
    <h3 class="mobile-skin-header">{$lng.lbl_adm_mobile_featured_products}</h3>
  </td>
</tr>
<tr class="TableSubHead">
  <td width="30">&nbsp;</td>
  <td width="60%" nowrap="nowrap"> <b>{$lng.lbl_adm_xcart_mobile_show_featureds}</b> </td>
  <td width="40%"> <input type="checkbox" id="featureds" name="xcart_mobile_config[featured]"{if $xcart_mobile_config.featured eq 'Y'} checked="checked"{/if} value="Y" /> </td>
</tr>
<tr>
  <td width="30">&nbsp;</td>
  <td width="60%" nowrap="nowrap"> <b>{$lng.lbl_adm_xcart_mobile_show_cat_featureds}</b> </td>
  <td width="40%"> <input type="checkbox" id="cat_featureds" name="xcart_mobile_config[cat_featured]"{if $xcart_mobile_config.cat_featured eq 'Y'} checked="checked"{/if} value="Y" /> </td>
</tr>
{if $active_modules.Bestsellers ne ''}
  <tr>
    <td colspan="3" class="TableSeparator">
      <h3 class="mobile-skin-header">{$lng.lbl_adm_mobile_bestsellers}</h3>
    </td>
  </tr>
  <tr class="TableSubHead">
    <td width="30">&nbsp;</td>
    <td width="60%" nowrap="nowrap"> <b>{$lng.lbl_adm_xcart_mobile_show_bestsellers}</b> </td>
    <td width="40%"> <input type="checkbox" id="bestsellers" name="xcart_mobile_config[bestsellers]"{if $xcart_mobile_config.bestsellers eq 'Y'} checked="checked"{/if} value="Y" /> </td>
  </tr>
  <tr>
    <td width="30">&nbsp;</td>
    <td width="60%" nowrap="nowrap"> <b>{$lng.lbl_adm_xcart_mobile_show_cat_bestsellers}</b> </td>
    <td width="40%"> <input type="checkbox" id="cat_bestsellers" name="xcart_mobile_config[cat_bestsellers]"{if $xcart_mobile_config.cat_bestsellers eq 'Y'} checked="checked"{/if} value="Y" /> </td>
  </tr>
{/if}
{if $active_modules.New_Arrivals ne '' or $active_modules.On_Sale ne ''}
  <tr>
    <td colspan="3" class="TableSeparator">
      <h3 class="mobile-skin-header">{$lng.lbl_adm_mobile_hot_products}</h3>
    </td>
  </tr>
  {if $active_modules.New_Arrivals ne ''}
    <tr class="TableSubHead">
      <td width="30">&nbsp;</td>
      <td width="60%" nowrap="nowrap"> <b>{$lng.lbl_adm_xcart_mobile_show_new_arrivals}</b> </td>
      <td width="40%"> <input type="checkbox" id="new_arrivals" name="xcart_mobile_config[new_arrivals]"{if $xcart_mobile_config.new_arrivals eq 'Y'} checked="checked"{/if} value="Y" /> </td>
    </tr>
  {/if}
  {if $active_modules.New_Arrivals ne ''}
    <tr>
      <td width="30">&nbsp;</td>
      <td width="60%" nowrap="nowrap"> <b>{$lng.lbl_adm_xcart_mobile_show_on_sale}</b> </td>
      <td width="40%"> <input type="checkbox" id="on_sale" name="xcart_mobile_config[on_sale]"{if $xcart_mobile_config.on_sale eq 'Y'} checked="checked"{/if} value="Y" /> </td>
    </tr>
  {/if}
{/if}
<tr>
  <td colspan="3" class="TableSeparator">
    <h3 class="mobile-skin-header">{$lng.opt_sep1}</h3>
  </td>
</tr>
<tr class="TableSubHead">
  <td width="30">&nbsp;</td>
  <td width="60%" nowrap="nowrap"> <b>{$lng.opt_products_per_page}</b> </td>
  <td width="40%"> <input type="text" size="10" name="xcart_mobile_config[products_per_page]" value="{$xcart_mobile_config.products_per_page|default:$config.Appearance.products_per_page|formatnumeric}" /> </td>
</tr>
<tr>
  <td colspan="3" class="TableSeparator">
    <h3 class="mobile-skin-header">{$lng.lbl_adm_mobile_checkout_options}</h3>
  </td>
</tr>
<tr class="TableSubHead">
  <td width="30">&nbsp;</td>
  <td width="60%" nowrap="nowrap"> <b>{$lng.lbl_adm_mobile_submit_order_dialog}</b> </td>
  <td width="40%"> <input type="checkbox" name="xcart_mobile_config[submit_order_dlg_disabled]"{if $xcart_mobile_config.submit_order_dlg_disabled eq 'Y'} checked="checked"{/if} value="Y" /> </td>
</tr>