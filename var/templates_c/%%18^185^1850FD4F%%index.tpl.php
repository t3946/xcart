<?php /* Smarty version 2.6.12, created on 2015-11-02 03:28:56
         compiled from customer/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/index.tpl', 1, false),array('modifier', 'strip_tags', 'customer/index.tpl', 7, false),array('modifier', 'escape', 'customer/index.tpl', 7, false),array('modifier', 'date_format', 'customer/index.tpl', 269, false),array('modifier', 'substitute', 'customer/index.tpl', 271, false),)), $this); ?>
<?php func_load_lang($this, "customer/index.tpl","lbl_list_of_stores,lbl_loading,txt_copyright,lbl_terms_n_conditions,lbl_privacy_statement"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/index.tpl"), $this); endif; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>
<?php unset($this->_sections['position']);
$this->_sections['position']['name'] = 'position';
$this->_sections['position']['loop'] = is_array($_loop=$this->_tpl_vars['location']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['position']['step'] = ((int)-1) == 0 ? 1 : (int)-1;
$this->_sections['position']['show'] = true;
$this->_sections['position']['max'] = $this->_sections['position']['loop'];
$this->_sections['position']['start'] = $this->_sections['position']['step'] > 0 ? 0 : $this->_sections['position']['loop']-1;
if ($this->_sections['position']['show']) {
    $this->_sections['position']['total'] = min(ceil(($this->_sections['position']['step'] > 0 ? $this->_sections['position']['loop'] - $this->_sections['position']['start'] : $this->_sections['position']['start']+1)/abs($this->_sections['position']['step'])), $this->_sections['position']['max']);
    if ($this->_sections['position']['total'] == 0)
        $this->_sections['position']['show'] = false;
} else
    $this->_sections['position']['total'] = 0;
if ($this->_sections['position']['show']):

            for ($this->_sections['position']['index'] = $this->_sections['position']['start'], $this->_sections['position']['iteration'] = 1;
                 $this->_sections['position']['iteration'] <= $this->_sections['position']['total'];
                 $this->_sections['position']['index'] += $this->_sections['position']['step'], $this->_sections['position']['iteration']++):
$this->_sections['position']['rownum'] = $this->_sections['position']['iteration'];
$this->_sections['position']['index_prev'] = $this->_sections['position']['index'] - $this->_sections['position']['step'];
$this->_sections['position']['index_next'] = $this->_sections['position']['index'] + $this->_sections['position']['step'];
$this->_sections['position']['first']      = ($this->_sections['position']['iteration'] == 1);
$this->_sections['position']['last']       = ($this->_sections['position']['iteration'] == $this->_sections['position']['total']);
?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['location'][$this->_sections['position']['index']]['0'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php if (! $this->_sections['position']['last']): ?> :: <?php endif; ?>
<?php endfor; endif; ?>
</title>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "meta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link rel="shortcut icon" href="http://www.artistsupplysource.com/skin1_kolin/images/S3-favicon.png" type="image/vnd.microsoft.icon" />
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/skin1_index.css" />
</head>
<body>
<script type="text/javascript">
var search_all_website_transfer_from_sku_search = '<?php echo $this->_tpl_vars['config']['Search_All']['search_all_website_transfer_from_sku_search']; ?>
';
var arg_sku = '<?php echo $GLOBALS['_GET']['sku']; ?>
';
<?php echo '

  function google_custom_search(control) {
    $(\'#google_search_result_block\').hide();

    control.setSearchCompleteCallback(control, function(el) {
        $(\'#content\').hide();
        $(\'#google_search_result_block\').show();
    });
    
    $(\'.gsst_a .gscb_a\').live(\'click\', function() {
        $(\'#google_search_result_block\').hide();
        $(\'#content\').show();
    });

/*    $(\'td.gsib_a input\').css(\'margin\', \'4px\'); */
    
  }
  var inputQuery = \'\';
  google.load(\'search\', \'1\', {language : \'en\', style : google.loader.themes.V2_DEFAULT});
  google.setOnLoadCallback(function() {
    var customSearchOptions = {};
    customSearchOptions[\'adoptions\'] = {\'layout\': \'noTop\'};
    var customSearchControl = new google.search.CustomSearchControl(\'';  echo $this->_tpl_vars['config']['Search_All']['search_all_website_gcs_id'];  echo '\', customSearchOptions);
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
    var options = new google.search.DrawOptions();
    options.setSearchFormRoot(\'cse-search-form\');
    options.setAutoComplete(true);
    customSearchControl.setAutoCompletionId(\'{$config.Search_all.search_all_website_gcs_id}\');
    customSearchControl.setSearchStartingCallback(this, function (control, searcher, query) {
        var expSKU = /^[a-z]{3}-/i;
        if (inputQuery != query && expSKU.test(query)) { 
            control.cancelSearch();
            $.get(\'index.php\', \'sku=\' + query + \'&mode=check_all\', function (ans) {
                if (search_all_website_transfer_from_sku_search == "Y" && ans == 0) {
                    inputQuery = query;
                    control.execute(query);
                } else {
                    window.location = \'index.php?mode=search&sku=\' + query;
                }
            }); 
        }
    });
    customSearchControl.draw(\'cse\', options);
    customSearchControl.prefillQuery(arg_sku);
    google_custom_search(customSearchControl);
  }, true);
'; ?>

</script>


<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/cidev_ajax.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
<?php echo '

        function func_load_more_products(ajax_navigation_page_next){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var cidev_filter_mode = \'load_more_products_SKU\';
        
                                
                                var sku = \'';  if ($GLOBALS['_GET']['sku'] != ""):  echo $GLOBALS['_GET']['sku'];  endif;  echo '\';

                                var cidev_parameters = \'cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=\'+ajax_navigation_page_next+\'&sku=\'+sku;

//-Start-//
                                var LN_total_items = $(\'#LN_total_items\').attr(\'data-value\');
                                var load_next_productids = $(\'#load_next_productids\').attr(\'data-value\');
                                load_next_productids = load_next_productids.trim();

                                if (load_next_productids != ""){
                                        cidev_parameters = cidev_parameters + \'&load_next_productids=\'+load_next_productids+\'&total_items=\'+LN_total_items;
                                }
//-End-//

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        cidev_id$("show_next_products_block_"+ajax_navigation_page_next).innerHTML=cidev_xmlHttp.responseText;

//-Start-//
                                                        $(\'#load_next_productids\').attr(\'data-value\',\'\');
                                                        ajax_navigation_page_next++;
							var cidev_parameters_load_next = \'mode_load_next_productids=Y&cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=\'+ajax_navigation_page_next+\'&sku=\'+sku;
                                                        func_load_more_next_productids(cidev_parameters_load_next, \'N\');
//-End-//

                                                }else{
                                                        cidev_Error(\'no_server\', \'Y\');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open(\'POST\',\'infinite_products.php\',true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout(\'func_load_more_products()\', 1000);
                        }
        }

//-Start-//
        function func_load_more_next_productids(cidev_parameters, first_on_load){

                        if (first_on_load == "Y"){
                                var cidev_filter_mode = \'load_more_products_SKU\';
				var sku = \'';  if ($GLOBALS['_GET']['sku'] != ""):  echo $GLOBALS['_GET']['sku'];  endif;  echo '\';
                                cidev_parameters = \'mode_load_next_productids=Y&cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=2&sku=\'+sku;
                        }

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        $(\'#load_next_productids\').attr(\'data-value\',cidev_xmlHttp.responseText);
                                                }else{
                                                        cidev_Error(\'no_server\', \'Y\');
                                                }
                                        }
                                };

                                cidev_xmlHttp.open(\'POST\',\'infinite_products.php\',true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout(\'func_load_more_next_productids()\', 1000);
                        }
        }
//-End-//

'; ?>

//]]>
</script>

<div style="display: none;" id="load_next_productids" data-value="<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/infinite_products_load_next_productids.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>"></div>
<div style="display: none;" id="LN_total_items" data-value="<?php echo $this->_tpl_vars['total_items']; ?>
"></div>

<?php if ($GLOBALS['_GET']['sku'] != ""): ?>
<script type="text/javascript">
//<![CDATA[
func_load_more_next_productids('','Y');
//]]>
</script>
<?php endif; ?>


<table width="960" align="center" cellspacing="0" cellpadding="0">
<tr>
<td>
<div class="page" style="background: #ffffff;">
  <div class="wrap">
  
    <div class="header">
        <div class="tabs">
            <span style="color: #cccccc;"><?php echo $this->_tpl_vars['lng']['lbl_list_of_stores']; ?>
</span>
            <?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
            <a target="_blank" href="http://www.artistsupplysource.com/<?php if ($this->_tpl_vars['v']['link'] != ""):  echo $this->_tpl_vars['v']['link'];  else: ?>index.php?pageid=<?php echo $this->_tpl_vars['v']['pageid'];  endif; ?>"><span><?php echo $this->_tpl_vars['v']['title']; ?>
</span></a>
            <?php endforeach; endif; unset($_from); ?>
        </div>
        <div class="search">
            <table class="search-table">
              <tr>
                <td>
<?php if ($GLOBALS['_GET']['pageid'] != ""): ?>
<a href="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
">
<?php endif; ?>
<img alt="<?php echo $this->_tpl_vars['config']['Search_All']['search_all_website_name']; ?>
" class="logo" src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/S3-Stores-Logo-S2.png" />
<?php if ($this->_tpl_vars['pageid'] != ""): ?>
</a>
<?php endif; ?>
		</td>
                <td width="100%"><div id="cse-search-form"><?php echo $this->_tpl_vars['lng']['lbl_loading']; ?>
</div></td>
              <tr>
            </table>
        </div>
    </div>
    
    <div class="main">
        <div id="google_search_result_block">
            <div id="cse" style="width:100%;"></div>
        </div>
        <div id="content">
        <?php if ($this->_tpl_vars['sf_links'] != '' && $this->_tpl_vars['config']['Search_All']['search_all_website_number_columns'] > 0): ?>

		<table cellspacing="20" cellpadding="5" align="center" style="background: #ffffff;">
		<?php $this->assign('cell_counter', 0); ?>
		<?php $_from = $this->_tpl_vars['sf_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
		<?php if ($this->_tpl_vars['cell_counter'] == '0'): ?>
		<tr>
		<?php endif; ?>
		<?php $this->assign('cell_counter', $this->_tpl_vars['cell_counter']+1); ?>

		<td width="33%">
		<?php if ($this->_tpl_vars['v']['storefrontid'] >= '0'): ?>
		<a href="http://<?php echo $this->_tpl_vars['v']['domain']; ?>
" target="_blank"><img src="http://www.artistsupplysource.com/image.php?id=<?php echo $this->_tpl_vars['v']['storefrontid']; ?>
&amp;type=S" alt="" style="border: #F0F0F0 1px solid; box-shadow: 5px 5px 5px 0  #cccccc;" /></a>
		<?php else: ?>
		<?php echo $this->_tpl_vars['v']['name']; ?>

		<?php endif; ?>
		</td>

		<?php if ($this->_tpl_vars['cell_counter'] == '3'): ?>
		</tr>
		<?php $this->assign('cell_counter', 0); ?>
		<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>

		<?php if ($this->_tpl_vars['cell_counter'] > '0'): ?>
		<td <?php if ($this->_tpl_vars['cell_counter'] == '1'): ?>colspan="2"<?php endif; ?> width="*"></td></tr>
		<?php endif; ?>

		</table>

	<?php elseif ($this->_tpl_vars['stock_availability_page'] == 'Y'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/stock_availability.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php elseif ($this->_tpl_vars['stock_availability_page'] == 'sent'): ?>
		<div class="confirmation-hedgehog">
		    <img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/confirmation-hedgehog.png">
		    <p>Survey data received. Thank you very much for your input!</p>
		    <p>We appreciate your support!</p>
		</div>
        <?php elseif ($this->_tpl_vars['page_content'] != ""): ?>
            <h1><?php echo $this->_tpl_vars['page_data']['title']; ?>
</h1>
            <?php echo $this->_tpl_vars['page_content']; ?>

        <?php elseif ($this->_tpl_vars['mode'] == 'search'): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <br />
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
        </div>
    </div>
    
  </div>
</div>
<?php $this->assign('year_end', ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y"))); ?>
<div class="footer">
    <span class="copyright"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_copyright'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'year_start', $this->_tpl_vars['config']['Search_All']['search_all_website_year'], 'year_end', $this->_tpl_vars['year_end']) : smarty_modifier_substitute($_tmp, 'year_start', $this->_tpl_vars['config']['Search_All']['search_all_website_year'], 'year_end', $this->_tpl_vars['year_end'])); ?>
</span>
    &nbsp;&nbsp;
    <a target="_blank" href="http://www.artistsupplysource.com/page/39/terms-of-use/" class="NavigationPath"><?php echo $this->_tpl_vars['lng']['lbl_terms_n_conditions']; ?>
</a> | <a href="http://www.artistsupplysource.com/page/40/privacy-policy/" class="NavigationPath" target="_blank"><?php echo $this->_tpl_vars['lng']['lbl_privacy_statement']; ?>
</a>
</div>
</td>
</tr>
</table>


<script type="text/javascript">
//<![CDATA[
<?php echo '

  var _gaq = _gaq || [];
  _gaq.push([\'_setAccount\', \'UA-952715-27\']);
  _gaq.push([\'_trackPageview\']);

  (function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
  })();

'; ?>

//]]>
</script>


</body>
</html>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/index.tpl"), $this); endif; ?>