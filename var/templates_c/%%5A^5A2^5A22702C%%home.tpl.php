<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/home.tpl', 1, false),array('function', 'config_load', 'customer/home.tpl', 12, false),array('modifier', 'stripslashes', 'customer/home.tpl', 23, false),array('modifier', 'escape', 'customer/home.tpl', 23, false),array('modifier', 'strip_tags', 'customer/home.tpl', 34, false),array('modifier', 'replace', 'customer/home.tpl', 44, false),array('modifier', 'truncate', 'customer/home.tpl', 46, false),array('modifier', 'lower', 'customer/home.tpl', 90, false),)), $this); ?>
<?php func_load_lang($this, "customer/home.tpl","lbl_recently_viewed_products,lbl_seo_cities_anchor,lbl_seo_cities"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/home.tpl"), $this); endif; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php if ($this->_tpl_vars['current_storefront_info']['storefrontid'] != ""): ?>
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=<?php echo $this->_tpl_vars['current_storefront_info']['storefrontid']; ?>
&amp;type=F" type="image/vnd.microsoft.icon" />
<?php else: ?>
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=0&amp;type=F" type="image/vnd.microsoft.icon" />
<?php endif;  if ($this->_tpl_vars['printable'] != ''):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/home_printable.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<html>
<head>
<title><?php echo '';  if ($this->_tpl_vars['brand']['title'] != "" && $this->_tpl_vars['main'] == 'brand_products'):  echo '';  echo $this->_tpl_vars['brand']['title'];  echo '';  else:  echo '';  if ($this->_tpl_vars['main'] == 'product' && $this->_tpl_vars['product']['title_tag'] != ""):  echo '';  echo $this->_tpl_vars['product']['title_tag'];  echo '';  else:  echo '';  if ($this->_tpl_vars['clean_url_data']['resource_type'] == 'K' && $this->_tpl_vars['e_search_data']['substring'] != ""):  echo '';  if ($this->_tpl_vars['e_search_data']['orig_substring'] != ""):  echo '';  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['e_search_data']['orig_substring'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '';  else:  echo '';  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['e_search_data']['substring'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '';  endif;  echo ' at&nbsp;';  endif;  echo '';  if ($this->_tpl_vars['config']['Appearance']['config_title_meta_tag'] != "" && ( ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" ) )):  echo '';  echo $this->_tpl_vars['config']['Appearance']['config_title_meta_tag'];  echo '';  elseif ($this->_tpl_vars['current_category']['title_tag'] != "" && $this->_tpl_vars['main'] == 'catalog'):  echo '';  echo $this->_tpl_vars['current_category']['title_tag'];  echo ' ';  echo '';  else:  echo '';  ob_start();  echo '';  if ($this->_tpl_vars['config']['SEO']['page_title_format'] == 'A'):  echo '';  unset($this->_sections['position']);
$this->_sections['position']['name'] = 'position';
$this->_sections['position']['loop'] = is_array($_loop=$this->_tpl_vars['location']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['position']['show'] = true;
$this->_sections['position']['max'] = $this->_sections['position']['loop'];
$this->_sections['position']['step'] = 1;
$this->_sections['position']['start'] = $this->_sections['position']['step'] > 0 ? 0 : $this->_sections['position']['loop']-1;
if ($this->_sections['position']['show']) {
    $this->_sections['position']['total'] = $this->_sections['position']['loop'];
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
 echo '';  if (! $this->_sections['position']['first']):  echo '&nbsp;::&nbsp;';  endif;  echo '';  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['location'][$this->_sections['position']['index']]['0'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '';  endfor; endif;  echo '';  else:  echo '';  unset($this->_sections['position']);
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
 echo '';  if (! $this->_sections['position']['first']):  echo '&nbsp;::&nbsp;';  endif;  echo '';  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['location'][$this->_sections['position']['index']]['0'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp) : smarty_modifier_strip_tags($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  echo '';  endfor; endif;  echo '';  endif;  echo '';  $this->_smarty_vars['capture']['title'] = ob_get_contents(); ob_end_clean();  echo '';  if ($this->_tpl_vars['config']['SEO']['page_title_limit'] <= 0):  echo '';  echo ((is_array($_tmp=$this->_smarty_vars['capture']['title'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&amp;", "&") : smarty_modifier_replace($_tmp, "&amp;", "&"));  echo '';  else:  echo '';  echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_smarty_vars['capture']['title'])) ? $this->_run_mod_handler('replace', true, $_tmp, "&nbsp;", ' ') : smarty_modifier_replace($_tmp, "&nbsp;", ' ')))) ? $this->_run_mod_handler('truncate', true, $_tmp, $this->_tpl_vars['config']['SEO']['page_title_limit']) : smarty_modifier_truncate($_tmp, $this->_tpl_vars['config']['SEO']['page_title_limit'])))) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', "&nbsp;") : smarty_modifier_replace($_tmp, ' ', "&nbsp;"));  echo '';  endif;  echo '';  endif;  echo '';  endif;  echo '';  endif;  echo ''; ?>
</title>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "meta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (( $this->_tpl_vars['main'] == 'product' )): ?>
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/jquery.tooltip.js" type="text/javascript"></script>
<?php endif; ?>

<?php if (( $this->_tpl_vars['main'] == 'product' )): ?>
<script type="text/javascript" language="JavaScript 1.2" src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/lib/jqueryui/jquery-ui.custom.min.js"></script>
<?php endif; ?>


<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/lib/jqueryui/jquery.ui.theme.css" />

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/<?php echo $this->_config[0]['vars']['CSSFile']; ?>
" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/jquery.tooltip.css" />

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/US_City_List/jquery.autocomplete.css" />

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/lib/colorbox/colorbox.css" />


<!-- igor_async <script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/lib/colorbox/jquery.colorbox-min.js" type="text/javascript"></script> -->


<!--[if IE]>
	<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/skin1.IE.css" type="text/css" media="all" />
<![endif]-->

<?php if ($this->_tpl_vars['canonical_url']): ?>
  <link rel="canonical" href="http://<?php if ($this->_tpl_vars['cidev_store_domain'] != ""):  echo ((is_array($_tmp=$this->_tpl_vars['cidev_store_domain'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp));  else: ?>www.artistsupplysource.com<?php endif; ?>/<?php echo $this->_tpl_vars['canonical_url']; ?>
" />
<?php endif;  if ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" && $this->_tpl_vars['clean_url_data']['resource_type'] != 'K'): ?>
  <link rel="canonical" href="http://<?php if ($this->_tpl_vars['cidev_store_domain'] != ""):  echo $this->_tpl_vars['cidev_store_domain'];  else: ?>www.artistsupplysource.com<?php endif; ?>/"/>
<?php endif; ?>


<script type="text/javascript">
//<![CDATA[

<?php if ($this->_tpl_vars['config']['SEO']['clean_urls_enabled'] == 'Y'):  echo '
//  Fix a.href if base url is defined for page
function anchor_fix() {
var links = document.getElementsByTagName(\'A\');
var m;
var _rg = new RegExp("(^|" + self.location.host + xcart_web_dir + "/)#([\\\\w\\\\d_]+)$")
for (var i = 0; i < links.length; i++) {
  if (links[i].href && (m = links[i].href.match(_rg))) {
    links[i].href = \'javascript:void(self.location.hash = "\' + m[2] + \'");\';
  }
}
}

if (window.addEventListener)
window.addEventListener("load", anchor_fix, false);

else if (window.attachEvent)
window.attachEvent("onload", anchor_fix);
'; ?>

<?php endif; ?>

//]]>
</script>

<?php if ($this->_tpl_vars['config']['SEO']['clean_urls_enabled'] == 'Y'): ?>
  <base href="<?php echo $this->_tpl_vars['catalogs']['customer']; ?>
/" />
<?php endif; ?>


</head>
<body<?php echo $this->_tpl_vars['reading_direction_tag'];  if ($this->_tpl_vars['body_onload'] != ''): ?> onload="javascript: <?php echo $this->_tpl_vars['body_onload']; ?>
"<?php endif; ?>>

<?php if ($this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search'): ?>
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/cidev_ajax.js" type="text/javascript"></script>
<?php endif; ?>


<?php if ($this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'catalog'): ?>

<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/jquery.jcarousel.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
<?php echo '


	function func_load_ALL_ajax_carousels(load_ajax_sections, ajax_counter){

                var load_ajax_sections_arr = load_ajax_sections.split(\',\');
                var count_ajax_sections = load_ajax_sections_arr.length;
		var load_ajax_carousel_flag;

                load_ajax_sections_arr.forEach(function(section_name, i, load_ajax_sections_arr) {

                        section_name.trim();

                        if ((ajax_counter - 1) == i){
//                                alert(section_name);

				load_ajax_carousel_flag = true;

				if (section_name == "similar_products"){

					var products_also_bought_with_this_product_style_display;
					products_also_bought_with_this_product_style_display = $("#products_also_bought_with_this_product").css("display");

					if (products_also_bought_with_this_product_style_display == "block"){
						load_ajax_carousel_flag = false;
					}
				}

				if (load_ajax_carousel_flag){
	                                func_load_ajax_carousel_products(section_name);
				}
                        }
                });

//$("#test_text").val(ajax_counter);

		ajax_counter++;
                setTimeout("func_load_ALL_ajax_carousels(\'" + load_ajax_sections + "\'," + ajax_counter + ")", 1100);
	}

        function func_load_ajax_carousel_products(section_name){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

				var cidev_parameters = \'section_name=\'+section_name

				'; ?>

				<?php if ($this->_tpl_vars['product']['productid'] != ""): ?>
				<?php echo '
					var productid = ';  echo $this->_tpl_vars['product']['productid'];  echo ';
        	                        cidev_parameters = cidev_parameters + \'&productid=\'+productid;
                                '; ?>

                                <?php endif; ?>
                                <?php echo '


                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){


/* ------------------------------------------------------------------------------------- */

	var data = cidev_xmlHttp.responseText;

	if (data != ""){
		$("#"+section_name).show();
	}

//	var jcarousel = $(\'.jcarousel\').jcarousel();
	var jcarousel = $(\'#jcarousel_\'+section_name).jcarousel();

//	$(\'.jcarousel-control-prev\')
	$(\'#jcarousel-control-prev_\'+section_name)
            .on(\'jcarouselcontrol:active\', function() {
                $(this).removeClass(\'inactive\');
            })
            .on(\'jcarouselcontrol:inactive\', function() {
                $(this).addClass(\'inactive\');
            })
            .jcarouselControl({
                target: \'-=1\'
            });

//	$(\'.jcarousel-control-next\')
	$(\'#jcarousel-control-next_\'+section_name)
            .on(\'jcarouselcontrol:active\', function() {
                $(this).removeClass(\'inactive\');
            })
            .on(\'jcarouselcontrol:inactive\', function() {
                $(this).addClass(\'inactive\');
            })
            .jcarouselControl({
                target: \'+=1\'
            });



	var obj = jQuery.parseJSON(data);

	var html = \'<ul>\';
	var a_href = \'\';

	if (obj){
	$.each(obj.items, function() {

		if (this.clean_url != \'\'){
			a_href = this.clean_url+\'/\';
		} else {
			a_href = \'product.php?productid=\'+ this.productid;
		}
		
                html += \'<li>\'+
			  \'<div style="text-align: center;">\'+
			  \'<a href="\'+ a_href +\'"><img src="\' + this.src + \'" alt="\' + this.title + \'"></a>\'+
			  \'<br />\'+ \'<a href="\'+ a_href +\'">\' +  this.title + \'</a>\'+
			  \'<br /> <font class="ProductPrice">Our Price: US$ \'+ this.price + \'</font>\'+
			  \'</div>\'+
			\'</li>\';
	});
	}

	html += \'</ul>\';

	// Append items
//	jcarousel
//	  .html(html);

	$(\'#jcarousel_\'+section_name).html(html);

	// Reload carousel
//	jcarousel
//	  .jcarousel(\'reload\');
	$(\'#jcarousel_\'+section_name).jcarousel(\'reload\');

/* ------------------------------------------------------------------------------------- */

                                                }else{
//                                                        cidev_Error(\'no_server\', \'Y\');
                                                }
                                        }
                                };

                                var tmp_rand = Math.random();

                                cidev_xmlHttp.open(\'POST\',\'cidev_ajax_suggestions.php?rand=\'+tmp_rand,true);
                                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                                cidev_xmlHttp.setRequestHeader(\'Cache-Control\',\'no-cache\');
                                cidev_xmlHttp.setRequestHeader(\'Cache-Control\',\'no-store\');
                                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
                                cidev_xmlHttp.send(cidev_parameters);
                        }
                        else {
                                setTimeout(\'func_load_ajax_carousel_products()\', 1000);
                        }
        }
'; ?>

//]]>
</script>
<?php endif; ?>


<?php if ($this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search'): ?>

<script type="text/javascript">
//<![CDATA[
<?php echo '
        function func_load_more_products(ajax_navigation_page_next){

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

				var current_storefront = \'';  echo $this->_tpl_vars['current_storefront'];  echo '\';
				var e_products_found = \'';  if ($this->_tpl_vars['e_products_found'] == 'Y'): ?>Y<?php endif;  echo '\';
				var cidev_filter_mode = \'load_more_products\';
				var additional_params = \'\';
	
				if (e_products_found == "Y"){
					cidev_filter_mode = \'load_more_e_products\';

					if (current_storefront == "41"){
						additional_params = \'&products_template=products_new_style\'
					}

					additional_params = additional_params + \'&e_search_data_substring=\' + $("#twotabsearchtextbox").val();
				}
				
				var cat = ';  if ($this->_tpl_vars['cat'] != ""):  echo $this->_tpl_vars['cat'];  else:  echo '\'\'';  endif;  echo ';

                                var cidev_parameters = \'cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=\'+ajax_navigation_page_next+\'&cat=\'+cat+additional_params;

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
							var cidev_parameters_load_next = \'mode_load_next_productids=Y&cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=\'+ajax_navigation_page_next+\'&cat=\'+cat+additional_params;
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
                                var current_storefront = \'';  echo $this->_tpl_vars['current_storefront'];  echo '\';
                                var e_products_found = \'';  if ($this->_tpl_vars['e_products_found'] == 'Y'): ?>Y<?php endif;  echo '\';
                                var cidev_filter_mode = \'load_more_products\';
                                var additional_params = \'\';
        
                                if (e_products_found == "Y"){
                                        cidev_filter_mode = \'load_more_e_products\';

                                        if (current_storefront == "41"){
                                                additional_params = \'&products_template=products_new_style\'
                                        }

                                        additional_params = additional_params + \'&e_search_data_substring=\' + $("#twotabsearchtextbox").val();
                                }
                                
                                var cat = ';  if ($this->_tpl_vars['cat'] != ""):  echo $this->_tpl_vars['cat'];  else:  echo '\'\'';  endif;  echo ';

                                cidev_parameters = \'mode_load_next_productids=Y&cidev_filter_mode=\'+cidev_filter_mode+\'&ajax_navigation_page_next=2&cat=\'+cat+additional_params;
			}

                        cidev_xmlHttp=cidev_createHttpRequestObject();
                        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                                cidev_xmlHttp.onreadystatechange=function(){
                                        if(cidev_xmlHttp.readyState==4){
                                                if(cidev_xmlHttp.status==200){
                                                        $(\'#load_next_productids\').attr(\'data-value\',cidev_xmlHttp.responseText);
                                                }else{
							if (first_on_load!= "Y"){
	                                                        cidev_Error(\'no_server\', \'Y\');
							}
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

<?php endif; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cidev_tracking_code.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php echo '
<style>
#tooltip {
    max-width: ';  echo $this->_tpl_vars['config']['Product_Page']['max_width_map_text'];  echo 'px;
}
</style>
'; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "rectangle_top.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['active_modules']['SnS_connector']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/SnS_connector/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<!-- main area -->


<?php if ($this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search'): ?>
<script type="text/javascript">
//<![CDATA[
func_load_more_next_productids('','Y');
//]]>
</script>
<?php endif; ?>


<?php if (! ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" && $this->_tpl_vars['current_seed_category'] == '' ) && $this->_tpl_vars['main'] != 'order_message'): ?>
<div style="margin-left: 10px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "location.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif; ?>


<table width="100%" cellpadding="0" cellspacing="0">
<tr>

<?php if ($this->_tpl_vars['active_modules']['CIDEV_Best_Search_Filter'] == "" || $this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products'): ?>

<td class="VertMenuLeftColumn">
<br>
<?php if ($this->_tpl_vars['categories'] != "" && ( $this->_tpl_vars['active_modules']['Fancy_Categories'] != "" || $this->_tpl_vars['config']['General']['root_categories'] == 'Y' || $this->_tpl_vars['subcategories'] != "" )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/categories.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Bestsellers'] != "" && $this->_tpl_vars['config']['Bestsellers']['bestsellers_menu'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Bestsellers/menu_bestsellers.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Survey'] && $this->_tpl_vars['menu_surveys']):  $_from = $this->_tpl_vars['menu_surveys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['menu_survey']):
 $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Survey/menu_survey.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />
<?php endforeach; endif; unset($_from);  endif; ?>

<?php if ($this->_tpl_vars['variant_id_for_point5'] != "" && $this->_tpl_vars['variant_id_for_point5'] == '0' && ! ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" )): ?>
<br />
<?php $this->assign('social_buttons_data_services', $this->_tpl_vars['config']['Appearance']['social_buttons_data_services']);  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['config']['Appearance']['social_buttons_script_code'])) ? $this->_run_mod_handler('replace', true, $_tmp, "[data-services]", ($this->_tpl_vars['social_buttons_data_services'])) : smarty_modifier_replace($_tmp, "[data-services]", ($this->_tpl_vars['social_buttons_data_services']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "[size]", 'medium') : smarty_modifier_replace($_tmp, "[size]", 'medium')); ?>

<?php endif; ?>

<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" width="156" height="1" alt="" />
</td>

<?php endif; ?>

<td valign="top">
<!-- central space -->

<?php if ($this->_tpl_vars['gcheckout_enabled'] && $this->_tpl_vars['main'] != 'cart' && $this->_tpl_vars['main'] != 'checkout' && $this->_tpl_vars['main'] != 'anonymous_checkout' && $this->_tpl_vars['main'] != 'order_message'): ?>
<div align="right"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Google_Checkout/gcheckout_button.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/customer/new_offers_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brands_list' || $this->_tpl_vars['main'] == 'brand_products'): ?>
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PaymentMethodCreditCard" />
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#VISA" />
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#MasterCard" />
<link itemprop="acceptedPaymentMethod" href="http://purl.org/goodrelations/v1#PayPal" />
<?php endif; ?>

<?php if ($this->_tpl_vars['use_schema_org'] == 'Y' && $this->_tpl_vars['main'] == 'product'): ?>
<div itemprop="name" itemscope itemtype="http://schema.org/Product">
<?php endif; ?>
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td colspan=3 height="10">&nbsp;</td></tr>
<tr>
<td bgcolor="#ffffff" colspan=3  >
<div id="google_search_result_block">
<?php echo $this->_tpl_vars['config']['Search_products']['search_products_result_code']; ?>

</div>
<div id="main">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/home_main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
</td>
</tr>
</table>
<?php if ($this->_tpl_vars['use_schema_org'] == 'Y' && $this->_tpl_vars['main'] == 'product'): ?>
</div>
<?php endif; ?>



<!-- /central space -->
</td>
</tr>
</table>



<?php if ($this->_tpl_vars['main'] == 'catalog'): ?>
<br />
<br />

<div id="recently_viewed_products" style="display: none;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/ajax_carousel_products.tpl", 'smarty_include_vars' => array('section_name' => 'recently_viewed_products','section_title' => $this->_tpl_vars['lng']['lbl_recently_viewed_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>

<script type="text/javascript">
//<![CDATA[
func_load_ALL_ajax_carousels("recently_viewed_products", 0);
//]]>
</script>
<?php endif; ?>




<?php if ($this->_tpl_vars['active_modules']['Brands'] != "" && $this->_tpl_vars['config']['Brands']['brands_menu'] == 'Y' && $this->_tpl_vars['main'] != 'sitemap_customer'): ?>
<div class="bottom-block" style="margin: 9px 10px 0px 10px; padding: 8px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Brands/menu_brands_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif; ?>

<?php if ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" && $this->_tpl_vars['keyphrase'] == ''): ?>
<div style="margin: 9px 10px 0px 10px; padding: 8px;" class="bottom-block">
 <div class="ship_cities_link"><a href="#" style="margin-left: 13px;"><?php echo $this->_tpl_vars['lng']['lbl_seo_cities_anchor']; ?>
</a></div>
 <div id="ship_cities_text">
 <?php echo $this->_tpl_vars['lng']['lbl_seo_cities']; ?>

 </div>
</div>
<?php endif; ?>

<?php if (( $this->_tpl_vars['active_modules']['Multiple_Storefronts'] != "" && $this->_tpl_vars['sf_links'] != '' && $this->_tpl_vars['main'] != 'sitemap_customer' ) && ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" ) && $this->_tpl_vars['area_selector'] != 'keyword'): ?>
<div class="bottom-block inter-sf" style="margin: 10px 10px 0px 10px; padding: 8px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Multiple_Storefronts/menu_storefronts_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "rectangle_bottom.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ga_code.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['config']['Product_Page']['map_bridge_text_background'] != ''):  echo '
<style>
#tooltip, .tooltip_helper {
	background-color: #';  echo $this->_tpl_vars['config']['Product_Page']['map_bridge_text_background']; ?>
;<?php echo '
}
</style>
'; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['main'] == 'product'): ?>
<script type="text/javascript">
//<![CDATA[
var txt_tooltip_helper = '<?php echo ((is_array($_tmp=$this->_tpl_vars['map_bridge_mouseover_text'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
';
<?php echo '
$(document).ready(function() {
	$(\'.map_price_help\').tooltip({
		delay: 0,
		showURL: false,
		track: false,
		bodyHandler: function() {
			return txt_tooltip_helper;
	}});
});
'; ?>

//]]>
</script>
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Company']['cidev_google_adwords'] != ""): ?>

<?php $this->assign('ecomm_prodid_replacement', "ecomm_prodid: ''");  $this->assign('ecomm_pagetype_replacement', "ecomm_pagetype: 'siteview'");  $this->assign('ecomm_totalvalue_replacement', "ecomm_totalvalue: ''"); ?>

<?php if ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == ""): ?>
	<?php $this->assign('ecomm_pagetype_replacement', "ecomm_pagetype: 'home'");  elseif ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] != ""): ?>
	<?php $this->assign('ecomm_pagetype_replacement', "ecomm_pagetype: 'category'");  elseif ($this->_tpl_vars['main'] == 'product'): ?>
	<?php $this->assign('ecomm_prodid_replacement', "ecomm_prodid: '".($this->_tpl_vars['product']['productid'])."'"); ?>
	<?php $this->assign('ecomm_pagetype_replacement', "ecomm_pagetype: 'product'"); ?>

	<?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
		<?php $this->assign('current_price', $this->_tpl_vars['product']['map_price']); ?>
	<?php else: ?>
		<?php $this->assign('current_price', $this->_tpl_vars['product']['taxed_price']); ?>
	<?php endif; ?>
	<?php $this->assign('ecomm_totalvalue_replacement', "ecomm_totalvalue: '".($this->_tpl_vars['current_price'])."'");  elseif ($this->_tpl_vars['main'] == 'order_message'): ?>
        <?php $this->assign('ecomm_prodid_replacement', "ecomm_prodid: ".($this->_tpl_vars['productids_in_cart_imploded'])); ?>
        <?php $this->assign('ecomm_pagetype_replacement', "ecomm_pagetype: 'purchase'"); ?>
        <?php $this->assign('ecomm_totalvalue_replacement', "ecomm_totalvalue: '".($this->_tpl_vars['order_data_subtotal'])."'");  endif; ?>

	<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['config']['Company']['cidev_google_adwords'])) ? $this->_run_mod_handler('replace', true, $_tmp, "ecomm_prodid: ''", ($this->_tpl_vars['ecomm_prodid_replacement'])) : smarty_modifier_replace($_tmp, "ecomm_prodid: ''", ($this->_tpl_vars['ecomm_prodid_replacement']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "ecomm_pagetype: 'siteview'", ($this->_tpl_vars['ecomm_pagetype_replacement'])) : smarty_modifier_replace($_tmp, "ecomm_pagetype: 'siteview'", ($this->_tpl_vars['ecomm_pagetype_replacement']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "ecomm_totalvalue: ''", ($this->_tpl_vars['ecomm_totalvalue_replacement'])) : smarty_modifier_replace($_tmp, "ecomm_totalvalue: ''", ($this->_tpl_vars['ecomm_totalvalue_replacement']))); ?>

<?php endif; ?>


<?php if ($this->_tpl_vars['GTS_badge_code'] != ""): ?>
	<?php echo $this->_tpl_vars['GTS_badge_code']; ?>

<?php endif;  if ($this->_tpl_vars['GTS_order_confirmation_module_code'] != ""): ?>
	<?php echo $this->_tpl_vars['GTS_order_confirmation_module_code']; ?>

<?php endif; ?>


<?php echo '
<script type="text/javascript">

function downloadJSAtOnload() 
{
'; ?>

<?php if (! ( $this->_tpl_vars['main'] == 'fast_lane_checkout' )):  echo '
var element = document.createElement("script");
element.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/check_email_script.js";
document.body.appendChild(element);
'; ?>

<?php endif;  echo '

/*
var element2 = document.createElement("script");
element2.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/ajax_add_to_cart.js";
document.body.appendChild(element2);
*/

var element3 = document.createElement("script");
element3.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/customer/popup_open.js";
document.body.appendChild(element3);

var element4 = document.createElement("script");
element4.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/lib/colorbox/jquery.colorbox-min.js";
document.body.appendChild(element4);

'; ?>

<?php if (! ( $this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'fast_lane_checkout' )):  echo '
var element5 = document.createElement("script");
element5.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/common.js";
document.body.appendChild(element5);
'; ?>

<?php endif;  echo '

var element6 = document.createElement("script");
element6.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/browser_identificator.js";
document.body.appendChild(element6);

/*
'; ?>

<?php if (! ( $this->_tpl_vars['main'] == 'product' )):  echo '
var element7 = document.createElement("script");
element7.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/jquery.min.1.7.1.js";
document.body.appendChild(element7);
'; ?>

<?php endif;  echo '
*/

'; ?>

<?php if (! ( $this->_tpl_vars['main'] == 'product' )):  echo '
var element8 = document.createElement("script");
element8.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/jquery.tooltip.js";
document.body.appendChild(element8);
'; ?>

<?php endif;  echo '

'; ?>

<?php if (! ( $this->_tpl_vars['main'] == 'product' )):  echo '
var element9 = document.createElement("script");
element9.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/lib/jqueryui/jquery-ui.custom.min.js";
document.body.appendChild(element9);
'; ?>

<?php endif;  echo '

'; ?>

<?php if ($this->_tpl_vars['main'] == 'product'):  echo '
var element10 = document.createElement("script");
element10.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/main/popup_image.js";
document.body.appendChild(element10);
'; ?>

<?php endif;  echo '

'; ?>

/*
<?php if ($this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'catalog' || $this->_tpl_vars['main'] == 'brand_products' || $this->_tpl_vars['main'] == 'search' || $this->_tpl_vars['main'] == 'advanced_search'):  echo '
var element11 = document.createElement("script");
element11.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/check_zipcode.js";
document.body.appendChild(element11);

var element12 = document.createElement("script");
element12.src = "';  echo $this->_tpl_vars['SkinDir'];  echo '/cidev_ajax.js";
document.body.appendChild(element12);

'; ?>

<?php endif;  echo '
*/

var element13 = document.createElement("script");
element13.src = "//www.googleadservices.com/pagead/conversion.js";
document.body.appendChild(element13);

}
if (window.addEventListener)
    window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
    window.attachEvent("onload", downloadJSAtOnload);
else 
    window.onload = downloadJSAtOnload;
</script>
'; ?>



<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/ajax_home_page.js" type="text/javascript"></script>

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