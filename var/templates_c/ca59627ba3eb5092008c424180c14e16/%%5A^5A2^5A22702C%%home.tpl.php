<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/home.tpl', 1, false),array('function', 'config_load', 'customer/home.tpl', 7, false),array('function', 'func_mobile_clear_modules', 'customer/home.tpl', 10, false),array('function', 'load_defer_code', 'customer/home.tpl', 153, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/home.tpl"), $this); endif;  if (! $this->_tpl_vars['is_ajax_request']): ?>
<!DOCTYPE html>
  <?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php echo func_mobile_clear_modules(array(), $this);?>

    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/service_head_mobile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </head>
  <body>
<?php endif; ?>

<?php if ($this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search'): ?>
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/cidev_ajax.js" type="text/javascript"></script>
<?php endif; ?>

<?php if ($this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search'): ?>

<script type="text/javascript">
//<![CDATA[
<?php echo '
        function func_load_more_products(ajax_navigation_page_next){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                var e_products_found = \'';  if ($this->_tpl_vars['e_products_found'] == 'Y'): ?>Y<?php endif;  echo '\';
                                var cidev_filter_mode = \'load_more_products\';
        
                                if (e_products_found == "Y"){
                                        cidev_filter_mode = \'load_more_e_products\';
                                }
                                
                                var cat = ';  if ($this->_tpl_vars['cat'] != ""):  echo $this->_tpl_vars['cat'];  else:  echo '\'\'';  endif;  echo ';

                                var cidev_parameters = \'cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=\'+ajax_navigation_page_next+\'&cat=\'+cat;

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
                                                        var cidev_parameters_load_next = \'mode_load_next_productids=Y&cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=\'+ajax_navigation_page_next+\'&cat=\'+cat;
                                                        func_load_more_next_productids(cidev_parameters_load_next, \'N\');
//-End-//

                                                }else{
                                                        cidev_Error(\'no_server\', \'Y\');
                                                }
                                        }
                                };

                                var tmp_rand = Math.random();

                                cidev_xmlHttp.open(\'POST\',\'infinite_products.php?rand=\'+tmp_rand,true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Cache-Control\',\'no-cache\');
                                cidev_xmlHttp.setRequestHeader(\'Cache-Control\',\'no-store\');
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
                                var current_storefront = \'';  echo $this->_tpl_vars['current_storefront'];  echo '\';
                                var e_products_found = \'';  if ($this->_tpl_vars['e_products_found'] == 'Y'): ?>Y<?php endif;  echo '\';
                                var cidev_filter_mode = \'load_more_products\';
        
                                if (e_products_found == "Y"){
                                        cidev_filter_mode = \'load_more_e_products\';
                                }
                                
                                var cat = ';  if ($this->_tpl_vars['cat'] != ""):  echo $this->_tpl_vars['cat'];  else:  echo '\'\'';  endif;  echo ';

                                cidev_parameters = \'mode_load_next_productids=Y&cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=2&cat=\'+cat;
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

                                var tmp_rand = Math.random();

                                cidev_xmlHttp.open(\'POST\',\'infinite_products.php?rand=\'+tmp_rand,true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Cache-Control\',\'no-cache\');
                                cidev_xmlHttp.setRequestHeader(\'Cache-Control\',\'no-store\');
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

<?php endif; ?>


    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/page.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search'): ?>
<script type="text/javascript">
//<![CDATA[
func_load_more_next_productids('','Y');
//]]>
</script>
<?php endif; ?>

<?php if (! $this->_tpl_vars['is_ajax_request']): ?>
    <?php echo smarty_function_load_defer_code(array('type' => 'js'), $this);?>

    <?php echo smarty_function_load_defer_code(array('type' => 'css'), $this);?>


<script type="text/javascript">
//<![CDATA[
<?php echo '

    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = \'s3stores\';
    
    /* * * DON\'T EDIT BELOW THIS LINE * * */
    (function () {
        var s = document.createElement(\'script\'); s.async = true;
        s.type = \'text/javascript\';
        s.src = \'//\' + disqus_shortname + \'.disqus.com/count.js\';
        (document.getElementsByTagName(\'HEAD\')[0] || document.getElementsByTagName(\'BODY\')[0]).appendChild(s);
    }());
'; ?>

//]]>
</script>

  </body>
</html>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/home.tpl"), $this); endif; ?>