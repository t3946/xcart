{*
$Id: service_head_mobile.tpl 78 2012-12-28 13:59:37Z skot $ 
vim: set ts=2 sw=2 sts=2 et:
*}
{if $config.SEO.clean_urls_enabled eq "Y"}
  <base href="{$catalogs.customer}/" />
{/if}

{include file="meta_titles.tpl" }
{*{get_title page_type=$meta_page_type page_id=$meta_page_id}*}

<meta charset="{$default_charset|default:"utf-8"}" />
{*<meta name="viewport" content="width=width, initial-scale=1, minimum-scale=0.25, maximum-scale=5, user-scalable=yes" />*}
<meta name="viewport" content="width=width, initial-scale={if $is_tablet}0.9{else}0.6{/if}, minimum-scale=0.6, maximum-scale=1, user-scalable=yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="shortcut icon" type="image/png" href="{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}//{$config.Appearance.CDN_domain}{else}{$current_location}{/if}/favicon.ico" />
<link rel="apple-touch-icon-precomposed" href="{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}//{$config.Appearance.CDN_domain}{else}{$current_location}{/if}/touch-icon-iphone-retina.png" />
<link rel="apple-touch-icon" href="{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}//{$config.Appearance.CDN_domain}{else}{$current_location}{/if}/touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}//{$config.Appearance.CDN_domain}{else}{$current_location}{/if}/touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}//{$config.Appearance.CDN_domain}{else}{$current_location}{/if}/touch-icon-iphone-retina.png" />
<link rel="apple-touch-icon" sizes="144x144" href="{if $config.Appearance.CDN_domain ne "" && $config.Appearance.Enable_CDN eq "Y"}//{$config.Appearance.CDN_domain}{else}{$current_location}{/if}/touch-icon-ipad-retina.png" />

{if $canonical_url}
  {if $oProduct && $oProduct->isGroupChild() && $oProduct->parent}
    <link rel="canonical" href="{$oProduct->parent->getUrl()}" />
  {else}
    <link rel="canonical" href="{$current_location}/{$canonical_url}" />
  {/if}
{/if}

{* for Photoswipe *}
{load_defer file="lib/photoswipe/klass.min.js" type="js"}
{include file="customer/service_js.tpl"}
{load_defer file="lib/jquery.mobile.css" type="css"}
{load_defer file="lib/jquery.mobile.core.css" type="css"}
{load_defer file="lib/jquery.mobile.scheme_f.css" type="css"}
{load_defer file="lib/jquery.mobile.js" type="js"}

{* Photoswipe *}
{load_defer file="lib/photoswipe/code.photoswipe.jquery.min.js" type="js"}
{* Mobiscroll *}
{load_defer file="lib/mobiscroll/js/mobiscroll.core-2.0.3.js" type="js"}
{load_defer file="lib/mobiscroll/css/mobiscroll.core-2.0.3.css" type="css"}
{load_defer file="lib/mobiscroll/js/mobiscroll.jqm-2.0.2.js" type="js"}
{load_defer file="lib/mobiscroll/css/mobiscroll.jqm-2.0.2.css" type="css"}
{load_defer file="lib/mobiscroll/js/mobiscroll.datetime-2.0.3.js" type="js"}

{capture name=javascript_mobile_code}
  var lbl_out_stock = '{$lng.lbl_out_stock}',
  lbl_in_stock_top = '{$lng.lbl_in_stock_top}',
  minicart_total_items = {$minicart_total_items|default:0},
  txt_mobile_switch_view_dialog_header = '{$lng.txt_mobile_switch_view_dialog_header}',
  txt_mobile_switch_view_dialog_content_mobile = '{$lng.txt_mobile_switch_view_dialog_content_mobile}',
  lbl_cancel = '{$lng.lbl_cancel}',
  lbl_switch = '{$lng.lbl_switch}';
  {* Mobiscroll *}
    // create a timepicker with default settings
    
    $(document).bind('pagebeforeshow', function(e, data) {ldelim}
      if ($("input[type='date'], input:jqmData(type='date')")) {ldelim}
        $("input[type='date'], input:jqmData(type='date')").scroller({ldelim} 
          preset: 'date',
          theme: 'jqm',
          startYear: {$start_year|default:$config.Company.start_year},
          endYear: {$end_year|default:$config.Company.end_year},
          dateFormat: '{$config.Appearance.ui_date_format}',
          dayNames: ['{$lng.lbl_day_fullname_7}', '{$lng.lbl_day_fullname_1}', '{$lng.lbl_day_fullname_2}', '{$lng.lbl_day_fullname_3}', '{$lng.lbl_day_fullname_4}', '{$lng.lbl_day_fullname_5}', '{$lng.lbl_day_fullname_6}'],
          dayNamesShort: ['{$lng.lbl_day_abbr_7}', '{$lng.lbl_day_abbr_1}', '{$lng.lbl_day_abbr_2}', '{$lng.lbl_day_abbr_3}', '{$lng.lbl_day_abbr_4}', '{$lng.lbl_day_abbr_5}', '{$lng.lbl_day_abbr_6}'],
          monthNames: ['{$lng.lbl_month_fullname_1}', '{$lng.lbl_month_fullname_2}', '{$lng.lbl_month_fullname_3}', '{$lng.lbl_month_fullname_4}', '{$lng.lbl_month_fullname_5}', '{$lng.lbl_month_fullname_6}', '{$lng.lbl_month_fullname_7}', '{$lng.lbl_month_fullname_8}', '{$lng.lbl_month_fullname_9}', '{$lng.lbl_month_fullname_10}', '{$lng.lbl_month_fullname_11}', '{$lng.lbl_month_fullname_12}'],
          monthNamesShort: ['{$lng.lbl_month_abbr_1}', '{$lng.lbl_month_abbr_2}', '{$lng.lbl_month_abbr_3}', '{$lng.lbl_month_abbr_4}', '{$lng.lbl_month_abbr_5}', '{$lng.lbl_month_abbr_6}', '{$lng.lbl_month_abbr_7}', '{$lng.lbl_month_abbr_8}', '{$lng.lbl_month_abbr_9}', '{$lng.lbl_month_abbr_10}', '{$lng.lbl_month_abbr_11}', '{$lng.lbl_month_abbr_12}'],
          dayText: '{$lng.lbl_day}',
          monthText: '{$lng.lbl_month}',
          yearText: '{$lng.lbl_year}'
        {rdelim});
      {rdelim}
    {rdelim});
    
  {if $active_modules.Detailed_Product_Images}
    {literal}
      (function(window, $, PhotoSwipe){
      $(document).ready(function(){
      $('div.gallery-page')
      .live('pageshow', function(e){
      var 
      currentPage = $(e.target),
      options = {
      captionAndToolbarAutoHideDelay: 0
      },
      photoSwipeInstance = $("ul.gallery a", e.target).photoSwipe(options,  currentPage.attr('id'));
      return true;
      })
      .live('pagehide', function(e){
      var 
      currentPage = $(e.target),
      photoSwipeInstance = PhotoSwipe.getInstance(currentPage.attr('id'));
      if (typeof photoSwipeInstance != "undefined" && photoSwipeInstance != null) {
      PhotoSwipe.detatch(photoSwipeInstance);
      }
      return true;
      });
      });
      }(window, window.jQuery, window.Code.PhotoSwipe));
    {/literal}
  {/if}
{/capture}
{load_defer file="javascript_mobile_code" direct_info=$smarty.capture.javascript_mobile_code type="js" queue="9999"}
{load_defer file="customer/core.js" type="js"}
{load_defer file="css/main.css" type="css" queue="7777"}
{if $config.UA.browser eq 'Opera'}
  {load_defer file="css/main.Opera.css" type="css" queue="7778"}
{/if}
{if $config.UA.browser eq 'Firefox'}
  {load_defer file="css/main.FF.css" type="css" queue="7778"}
{/if}
{load_defer file="lib/photoswipe/photoswipe.css" type="css" queue="9999"}
{load_defer file="customer/help/popup_info.js" type="js"}
{if $active_modules.XMultiCurrency}
  {capture name="mc_definitions"}
    var lng_mc_selector_title = '{$lng.mc_lbl_selector_title}';
    var lng_thumbnails = [
    {foreach from=$all_languages item=l}
          ['{$l.code}', '{if not $l.is_url}{$current_location}{/if}{$l.tmbn_url}'],
    {/foreach}
        ['empty', '']
      ];
      var mc_countries = [
    {foreach from=$mc_all_countries item=cnt}
        {if not $cnt.excluded}
            ['{$cnt.country_code}', '{$cnt.currency_code}', '{$cnt.language_code}'],
      {/if}
    {/foreach}
      ];
  {/capture}
  {load_defer file="mc_definitions" direct_info=$smarty.capture.mc_definitions type="js" queue="10000"}
  {load_defer file="modules/XMultiCurrency/customer/func.js" type="js" queue="10001"}
{/if}

{load_defer_code type="css"}
{load_defer_code type="js"}
{if $GTS_badge_code ne ""}
    {$GTS_badge_code}
{/if}

{include file='sliders/head_inlines.tpl'}