<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/service_head_mobile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/service_head_mobile.tpl', 1, false),array('function', 'get_title', 'customer/service_head_mobile.tpl', 5, false),array('function', 'load_defer', 'customer/service_head_mobile.tpl', 22, false),array('function', 'load_defer_code', 'customer/service_head_mobile.tpl', 127, false),array('modifier', 'default', 'customer/service_head_mobile.tpl', 6, false),)), $this); ?>
<?php func_load_lang($this, "customer/service_head_mobile.tpl","lbl_out_stock,lbl_in_stock_top,txt_mobile_switch_view_dialog_header,txt_mobile_switch_view_dialog_content_mobile,lbl_cancel,lbl_switch,lbl_day_fullname_7,lbl_day_fullname_1,lbl_day_fullname_2,lbl_day_fullname_3,lbl_day_fullname_4,lbl_day_fullname_5,lbl_day_fullname_6,lbl_day_abbr_7,lbl_day_abbr_1,lbl_day_abbr_2,lbl_day_abbr_3,lbl_day_abbr_4,lbl_day_abbr_5,lbl_day_abbr_6,lbl_month_fullname_1,lbl_month_fullname_2,lbl_month_fullname_3,lbl_month_fullname_4,lbl_month_fullname_5,lbl_month_fullname_6,lbl_month_fullname_7,lbl_month_fullname_8,lbl_month_fullname_9,lbl_month_fullname_10,lbl_month_fullname_11,lbl_month_fullname_12,lbl_month_abbr_1,lbl_month_abbr_2,lbl_month_abbr_3,lbl_month_abbr_4,lbl_month_abbr_5,lbl_month_abbr_6,lbl_month_abbr_7,lbl_month_abbr_8,lbl_month_abbr_9,lbl_month_abbr_10,lbl_month_abbr_11,lbl_month_abbr_12,lbl_day,lbl_month,lbl_year,mc_lbl_selector_title"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/service_head_mobile.tpl"), $this); endif;  echo smarty_function_get_title(array('page_type' => $this->_tpl_vars['meta_page_type'],'page_id' => $this->_tpl_vars['meta_page_id']), $this);?>

<meta charset="<?php echo ((is_array($_tmp=@$this->_tpl_vars['default_charset'])) ? $this->_run_mod_handler('default', true, $_tmp, "utf-8") : smarty_modifier_default($_tmp, "utf-8")); ?>
" />
<meta name="viewport" content="width=device-width, initial-scale=<?php if ($this->_tpl_vars['is_tablet']): ?>0.9<?php else: ?>0.6<?php endif; ?>, minimum-scale=0.25, maximum-scale=5, user-scalable=yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="shortcut icon" type="image/png" href="<?php if ($this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  if ($this->_tpl_vars['add_http_if_cdn'] == 'Y'): ?>http://<?php endif;  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['current_location'];  endif; ?>/favicon.ico" />
<link rel="apple-touch-icon-precomposed" href="<?php if ($this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  if ($this->_tpl_vars['add_http_if_cdn'] == 'Y'): ?>http://<?php endif;  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['current_location'];  endif; ?>/touch-icon-iphone-retina.png" />
<link rel="apple-touch-icon" href="<?php if ($this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  if ($this->_tpl_vars['add_http_if_cdn'] == 'Y'): ?>http://<?php endif;  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['current_location'];  endif; ?>/touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="<?php if ($this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  if ($this->_tpl_vars['add_http_if_cdn'] == 'Y'): ?>http://<?php endif;  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['current_location'];  endif; ?>/touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php if ($this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  if ($this->_tpl_vars['add_http_if_cdn'] == 'Y'): ?>http://<?php endif;  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['current_location'];  endif; ?>/touch-icon-iphone-retina.png" />
<link rel="apple-touch-icon" sizes="144x144" href="<?php if ($this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  if ($this->_tpl_vars['add_http_if_cdn'] == 'Y'): ?>http://<?php endif;  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['current_location'];  endif; ?>/touch-icon-ipad-retina.png" />
<?php if ($this->_tpl_vars['canonical_url']): ?>
  <link rel="canonical" href="<?php echo $this->_tpl_vars['current_location']; ?>
/<?php echo $this->_tpl_vars['canonical_url']; ?>
" />
<?php endif;  if ($this->_tpl_vars['config']['SEO']['clean_urls_enabled'] == 'Y'): ?>
  <base href="<?php echo $this->_tpl_vars['catalogs']['customer']; ?>
/" />
<?php endif;  echo smarty_function_load_defer(array('file' => "lib/photoswipe/klass.min.js",'type' => 'js'), $this);?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/service_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo smarty_function_load_defer(array('file' => "lib/jquery.mobile.css",'type' => 'css'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/jquery.mobile.core.css",'type' => 'css'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/jquery.mobile.scheme_f.css",'type' => 'css'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/jquery.mobile.js",'type' => 'js'), $this);?>


<?php echo smarty_function_load_defer(array('file' => "lib/photoswipe/code.photoswipe.jquery.min.js",'type' => 'js'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/mobiscroll/js/mobiscroll.core-2.0.3.js",'type' => 'js'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/mobiscroll/css/mobiscroll.core-2.0.3.css",'type' => 'css'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/mobiscroll/js/mobiscroll.jqm-2.0.2.js",'type' => 'js'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/mobiscroll/css/mobiscroll.jqm-2.0.2.css",'type' => 'css'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "lib/mobiscroll/js/mobiscroll.datetime-2.0.3.js",'type' => 'js'), $this);?>


<?php ob_start(); ?>
  var lbl_out_stock = '<?php echo $this->_tpl_vars['lng']['lbl_out_stock']; ?>
',
  lbl_in_stock_top = '<?php echo $this->_tpl_vars['lng']['lbl_in_stock_top']; ?>
',
  minicart_total_items = <?php echo ((is_array($_tmp=@$this->_tpl_vars['minicart_total_items'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
,
  txt_mobile_switch_view_dialog_header = '<?php echo $this->_tpl_vars['lng']['txt_mobile_switch_view_dialog_header']; ?>
',
  txt_mobile_switch_view_dialog_content_mobile = '<?php echo $this->_tpl_vars['lng']['txt_mobile_switch_view_dialog_content_mobile']; ?>
',
  lbl_cancel = '<?php echo $this->_tpl_vars['lng']['lbl_cancel']; ?>
',
  lbl_switch = '<?php echo $this->_tpl_vars['lng']['lbl_switch']; ?>
';
      // create a timepicker with default settings
    
    $(document).bind('pagebeforeshow', function(e, data) {
      if ($("input[type='date'], input:jqmData(type='date')")) {
        $("input[type='date'], input:jqmData(type='date')").scroller({ 
          preset: 'date',
          theme: 'jqm',
          startYear: <?php echo ((is_array($_tmp=@$this->_tpl_vars['start_year'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['Company']['start_year']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['Company']['start_year'])); ?>
,
          endYear: <?php echo ((is_array($_tmp=@$this->_tpl_vars['end_year'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['Company']['end_year']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['Company']['end_year'])); ?>
,
          dateFormat: '<?php echo $this->_tpl_vars['config']['Appearance']['ui_date_format']; ?>
',
          dayNames: ['<?php echo $this->_tpl_vars['lng']['lbl_day_fullname_7']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_fullname_1']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_fullname_2']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_fullname_3']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_fullname_4']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_fullname_5']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_fullname_6']; ?>
'],
          dayNamesShort: ['<?php echo $this->_tpl_vars['lng']['lbl_day_abbr_7']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_abbr_1']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_abbr_2']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_abbr_3']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_abbr_4']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_abbr_5']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_day_abbr_6']; ?>
'],
          monthNames: ['<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_1']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_2']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_3']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_4']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_5']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_6']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_7']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_8']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_9']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_10']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_11']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_fullname_12']; ?>
'],
          monthNamesShort: ['<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_1']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_2']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_3']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_4']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_5']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_6']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_7']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_8']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_9']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_10']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_11']; ?>
', '<?php echo $this->_tpl_vars['lng']['lbl_month_abbr_12']; ?>
'],
          dayText: '<?php echo $this->_tpl_vars['lng']['lbl_day']; ?>
',
          monthText: '<?php echo $this->_tpl_vars['lng']['lbl_month']; ?>
',
          yearText: '<?php echo $this->_tpl_vars['lng']['lbl_year']; ?>
'
        });
      }
    });
    
  <?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images']): ?>
    <?php echo '
      (function(window, $, PhotoSwipe){
      $(document).ready(function(){
      $(\'div.gallery-page\')
      .live(\'pageshow\', function(e){
      var 
      currentPage = $(e.target),
      options = {
      captionAndToolbarAutoHideDelay: 0
      },
      photoSwipeInstance = $("ul.gallery a", e.target).photoSwipe(options,  currentPage.attr(\'id\'));
      return true;
      })
      .live(\'pagehide\', function(e){
      var 
      currentPage = $(e.target),
      photoSwipeInstance = PhotoSwipe.getInstance(currentPage.attr(\'id\'));
      if (typeof photoSwipeInstance != "undefined" && photoSwipeInstance != null) {
      PhotoSwipe.detatch(photoSwipeInstance);
      }
      return true;
      });
      });
      }(window, window.jQuery, window.Code.PhotoSwipe));
    '; ?>

  <?php endif;  $this->_smarty_vars['capture']['javascript_mobile_code'] = ob_get_contents(); ob_end_clean();  echo smarty_function_load_defer(array('file' => 'javascript_mobile_code','direct_info' => $this->_smarty_vars['capture']['javascript_mobile_code'],'type' => 'js','queue' => '9999'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "customer/core.js",'type' => 'js'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "css/main.css",'type' => 'css','queue' => '7777'), $this);?>

<?php if ($this->_tpl_vars['config']['UA']['browser'] == 'Opera'): ?>
  <?php echo smarty_function_load_defer(array('file' => "css/main.Opera.css",'type' => 'css','queue' => '7778'), $this);?>

<?php endif;  if ($this->_tpl_vars['config']['UA']['browser'] == 'Firefox'): ?>
  <?php echo smarty_function_load_defer(array('file' => "css/main.FF.css",'type' => 'css','queue' => '7778'), $this);?>

<?php endif;  echo smarty_function_load_defer(array('file' => "lib/photoswipe/photoswipe.css",'type' => 'css','queue' => '9999'), $this);?>

<?php echo smarty_function_load_defer(array('file' => "customer/help/popup_info.js",'type' => 'js'), $this);?>

<?php if ($this->_tpl_vars['active_modules']['XMultiCurrency']): ?>
  <?php ob_start(); ?>
    var lng_mc_selector_title = '<?php echo $this->_tpl_vars['lng']['mc_lbl_selector_title']; ?>
';
    var lng_thumbnails = [
    <?php $_from = $this->_tpl_vars['all_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
          ['<?php echo $this->_tpl_vars['l']['code']; ?>
', '<?php if (! $this->_tpl_vars['l']['is_url']):  echo $this->_tpl_vars['current_location'];  endif;  echo $this->_tpl_vars['l']['tmbn_url']; ?>
'],
    <?php endforeach; endif; unset($_from); ?>
        ['empty', '']
      ];
      var mc_countries = [
    <?php $_from = $this->_tpl_vars['mc_all_countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['cnt']):
?>
        <?php if (! $this->_tpl_vars['cnt']['excluded']): ?>
            ['<?php echo $this->_tpl_vars['cnt']['country_code']; ?>
', '<?php echo $this->_tpl_vars['cnt']['currency_code']; ?>
', '<?php echo $this->_tpl_vars['cnt']['language_code']; ?>
'],
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
      ];
  <?php $this->_smarty_vars['capture']['mc_definitions'] = ob_get_contents(); ob_end_clean(); ?>
  <?php echo smarty_function_load_defer(array('file' => 'mc_definitions','direct_info' => $this->_smarty_vars['capture']['mc_definitions'],'type' => 'js','queue' => '10000'), $this);?>

  <?php echo smarty_function_load_defer(array('file' => "modules/XMultiCurrency/customer/func.js",'type' => 'js','queue' => '10001'), $this);?>

<?php endif;  echo smarty_function_load_defer_code(array('type' => 'css'), $this);?>

<?php echo smarty_function_load_defer_code(array('type' => 'js'), $this);?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/service_head_mobile.tpl"), $this); endif; ?>