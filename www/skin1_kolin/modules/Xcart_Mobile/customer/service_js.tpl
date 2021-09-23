{*
$Id: service_js.tpl 63 2012-10-30 11:56:13Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
{capture name=javascript_code}
  {if $__frame_not_allowed and not $smarty.get.open_in_layer}
    if (top != self)
    top.location = self.location;
  {/if}
  var number_format_dec = '{$number_format_dec}';
  var number_format_th = '{$number_format_th}';
  var number_format_point = '{$number_format_point}';
  var store_language = '{$store_language|escape:javascript}';
  var xcart_web_dir = "{$xcart_web_dir|wm_remove|escape:javascript}";
  var images_dir = "{$ImagesDir|wm_remove|escape:javascript}";
{if $AltImagesDir}var alt_images_dir = "{$AltImagesDir|wm_remove|escape:javascript}";{/if}
var lbl_no_items_have_been_selected = '{$lng.lbl_no_items_have_been_selected|wm_remove|escape:javascript}';
var current_area = '{$usertype}';
var currency_format = "{$config.General.currency_format|replace:'$':$config.General.currency_symbol}";
var lbl_product_minquantity_error = "{$lng.lbl_product_minquantity_error|wm_remove|escape:javascript}";
var lbl_product_maxquantity_error = "{$lng.lbl_product_maxquantity_error|wm_remove|escape:javascript}";
var lbl_product_quantity_type_error = "{$lng.lbl_product_quantity_type_error|wm_remove|escape:javascript}";
var is_limit = {if $config.General.unlimited_products eq 'Y'}false{else}true{/if};
var lbl_required_field_is_empty = "{$lng.lbl_required_field_is_empty|strip_tags|wm_remove|escape:javascript}";
var lbl_field_required = "{$lng.lbl_field_required|strip_tags|wm_remove|escape:javascript}";
var lbl_field_format_is_invalid = "{$lng.lbl_field_format_is_invalid|wm_remove|escape:javascript}";
var txt_required_fields_not_completed = "{$lng.txt_required_fields_not_completed|wm_remove|escape:javascript}";
var lbl_blockui_default_message = "{$lng.lbl_blockui_default_message|wm_remove|escape:javascript}";
var lbl_error = '{$lng.lbl_error|wm_remove|escape:javascript}';
var lbl_warning = '{$lng.lbl_warning|wm_remove|escape:javascript}';
var lbl_ok = '{$lng.lbl_ok|wm_remove|escape:javascript}';
var lbl_yes = '{$lng.lbl_yes|wm_remove|escape:javascript}';
var lbl_no = '{$lng.lbl_no|wm_remove|escape:javascript}';
var txt_minicart_total_note = '{$lng.txt_minicart_total_note|wm_remove|escape:javascript}';
var txt_ajax_error_note = '{$lng.txt_ajax_error_note|wm_remove|escape:javascript}';
{if $use_email_validation ne "N"}
  var txt_email_invalid = "{$lng.txt_email_invalid|wm_remove|escape:javascript}";
  var email_validation_regexp = new RegExp("{$email_validation_regexp|wm_remove|escape:javascript}", "gi");
{/if}
var is_admin_editor = {if $is_admin_editor}true{else}false{/if};
{/capture}
{load_defer file="javascript_code" direct_info=$smarty.capture.javascript_code type="js"}
{load_defer file="common.js" type="js"}

{if $active_modules.Amazon_Checkout ne "" && $amazon_widget_url}
  {*getvar func='func_tpl_is_acheckout_button_enabled'}
  {if $func_tpl_is_acheckout_button_enabled*}
    <script type="text/javascript" src="{$amazon_widget_url}"></script>
  {*/if*}
{/if}
{if $active_modules.PayPalAuth ne ""}
  {load_defer file="modules/PayPalAuth/ppa.js" type="js"}
{/if}
{load_defer file="lib/jquery-min.js" type="js"}
{load_defer file="lib/jquery.bgiframe.min.js" type="js"}
{load_defer file="lib/jquery.cookie.js" type="js"}
{include file="onload_js.tpl"}
{if $active_modules.Google_Analytics and $config.Google_Analytics.ganalytics_version eq 'Asynchronous'}
  {include file="modules/Google_Analytics/ga_code_async.tpl"}
{/if}
