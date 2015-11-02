<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/main/ui_tabs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/ui_tabs.tpl', 1, false),array('function', 'math', 'customer/main/ui_tabs.tpl', 152, false),array('modifier', 'amp', 'customer/main/ui_tabs.tpl', 85, false),array('modifier', 'default', 'customer/main/ui_tabs.tpl', 85, false),array('modifier', 'price_format', 'customer/main/ui_tabs.tpl', 157, false),array('modifier', 'date_format', 'customer/main/ui_tabs.tpl', 300, false),array('modifier', 'formatprice', 'customer/main/ui_tabs.tpl', 352, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/ui_tabs.tpl","lbl_product_description,lbl_product_description,lbl_product_queries_pre_instructions,lbl_product_queries_after_instructions,lbl_product_question_pre_instructions,lbl_FIELD_DESCRIPTION_Product_question,lbl_shipping_dimensions"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/ui_tabs.tpl"), $this); endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "check_email_script.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<script type="text/javascript">
//<![CDATA[
$(function() {
  $('#<?php echo $this->_tpl_vars['prefix']; ?>
container').tabs();
});

<?php echo '
function check_question_email_form() {

	if ($("#email").val()!="" && $("#phone").val()!="" && $("#question").val()!="" && $("#firstname").val()!=""){

		$("#button_submit_question_id").hide();

		send_question_email_form();

	} else {
		alert("Please fill in all fields");
		return false;
	}
}

function send_question_email_form(){

	cidev_xmlHttp=cidev_createHttpRequestObject();
	if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

		var cidev_parameters = \'cidev_mode=send&email=\' + $("#email").val() + \'&phone=\' + $("#phone").val() + \'&question=\' + $("#question").val() + \'&productid=\' + $(\'#question_productid\').val() + \'&firstname=\' + $(\'#firstname\').val();

		cidev_xmlHttp.onreadystatechange=function(){
			if(cidev_xmlHttp.readyState==4){
				if(cidev_xmlHttp.status==200){
                	        	cidev_id$("product_question_after").innerHTML=cidev_xmlHttp.responseText;
					$("#product_question_pre").hide();
                        	}else{
                        		cidev_Error(\'no_server\', \'Y\');
	                        }
			}
		};

                cidev_xmlHttp.open(\'POST\',\'product_question.php\',true);
                cidev_xmlHttp.setRequestHeader(\'Content-type\',\'application/x-www-form-urlencoded\');
                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
                cidev_xmlHttp.send(cidev_parameters);
	}
	else {
		setTimeout(\'send_question_email_form()\', 1000);
	}
}


'; ?>


//]]>
</script>

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '
  $(document).ready(function() {  
        $(\'#email\').focusout(function() {

                if ($(\'#email\').val() != ""){
                        checkEmailAddress(document.product_question_email_form.email, \'Y\');
                }
        });
  });
'; ?>

//]]>
</script>


<div id="<?php echo $this->_tpl_vars['prefix']; ?>
container">

  <ul>
  <?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ind'] => $this->_tpl_vars['tab']):
?>
    <li><a <?php if ($this->_tpl_vars['count_product_tabs'] >= '7'): ?>style="padding: 0.5em 10px;"<?php endif; ?> href="<?php if ($this->_tpl_vars['tab']['url']):  echo ((is_array($_tmp=$this->_tpl_vars['tab']['url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  else: ?>#<?php echo $this->_tpl_vars['prefix'];  echo ((is_array($_tmp=@$this->_tpl_vars['tab']['anchor'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['ti']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['ti']));  endif; ?>"><?php echo $this->_tpl_vars['tab']['title']; ?>
</a></li> 
  <?php endforeach; endif; unset($_from); ?>
  </ul>

  <?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ind'] => $this->_tpl_vars['tab']):
?>
    <?php if ($this->_tpl_vars['tab']['tpl']): ?>
      <div id="<?php echo $this->_tpl_vars['prefix'];  echo ((is_array($_tmp=@$this->_tpl_vars['tab']['anchor'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['ti']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['ti'])); ?>
">

	<?php if ($this->_tpl_vars['tab']['tpl'] == '_product_description_'): ?>
<br />
<?php ob_start(); ?>
<div style="padding-left: 8px;">
<span style="font-size: 13px; color: #000000;" class="SPItems-description"><?php if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?><span itemprop="description"><?php endif;  if ($this->_tpl_vars['product']['fulldescr'] != ""):  echo $this->_tpl_vars['product']['fulldescr'];  else:  echo $this->_tpl_vars['product']['descr'];  endif;  if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?></span><?php endif; ?></span>

<?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != '' || $this->_tpl_vars['show_dimensions'] || $this->_tpl_vars['product']['upc_ean_isbn']): ?>
<br />
<?php endif; ?>

<?php if ($this->_tpl_vars['use_schema_org'] == 'Y'):  endif; ?>

<br />
<?php if ($this->_tpl_vars['active_modules']['Extra_Fields'] != ""): ?>
<table width="100%" cellpadding="0" cellspacing="0">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Extra_Fields/product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['use_schema_org'] == 'Y'):  if ($this->_tpl_vars['current_storefront'] == '0'): ?>
<meta itemprop="logo" content="http://www.artistsupplysource.com/image.php?type=P&id=<?php echo $this->_tpl_vars['product']['productid']; ?>
"/>
<?php else: ?>
<meta itemprop="logo" content="http://<?php echo $this->_tpl_vars['cidev_store_domain']; ?>
/image.php?type=P&id=<?php echo $this->_tpl_vars['product']['productid']; ?>
"/>
<?php endif; ?>

<meta itemprop="brand" content="<?php echo $this->_tpl_vars['product']['cidev_brand_name']; ?>
"/>
<meta itemprop="manufacturer" content="<?php echo $this->_tpl_vars['product']['manufacturer']; ?>
"/>
<meta itemprop="sku" content="<?php echo $this->_tpl_vars['product']['productcode']; ?>
"/>
<?php if ($this->_tpl_vars['cidev_mpn'] != ""): ?>
<meta itemprop="mpn" content="<?php echo $this->_tpl_vars['cidev_mpn']; ?>
"/>
<?php endif; ?>

<div itemprop="offers" itemscope itemtype="http://schema.org/Offer"/>

<?php if ($this->_tpl_vars['cat_name_for_itemprop'] != ""): ?>
<meta itemprop="category" content="<?php echo $this->_tpl_vars['cat_name_for_itemprop']; ?>
"/>
<?php endif; ?>

<?php if ($this->_tpl_vars['product']['product_availability'] == 'in stock'): ?>
<meta itemprop="availability" itemtype="http://schema.org/ItemAvailability" href="http://schema.org/InStock" content="In Stock"/>
<?php else: ?>
<meta itemprop="availability" itemtype="http://schema.org/ItemAvailability" href="http://schema.org/OutOfStock" content="Out of stock"/>
<?php endif; ?>

<meta itemprop="itemCondition" itemtype="http://schema.org/OfferItemCondition" content="http://schema.org/NewCondition"/>

<meta itemprop="businessFunction" content="sell"/>
<meta itemprop="deliveryLeadTime" content="6"/>

<?php if ($this->_tpl_vars['product_wholesale']['0']['price'] != "" && $this->_tpl_vars['product']['new_notify_in_stock_price'] == "" && $this->_tpl_vars['product']['map_price'] <= $this->_tpl_vars['product']['taxed_price']): ?>
	<?php $this->assign('current_price', $this->_tpl_vars['product_wholesale']['0']['price']);  endif; ?>

<?php if ($this->_tpl_vars['product']['min_amount'] > 1 && $this->_tpl_vars['product']['mult_order_quantity'] == 'Y'): ?>
	<?php echo smarty_function_math(array('assign' => 'itemprop_price','equation' => "y*x",'y' => $this->_tpl_vars['product']['min_amount'],'x' => $this->_tpl_vars['current_price']), $this);?>

<?php else: ?>
	<?php $this->assign('itemprop_price', $this->_tpl_vars['current_price']);  endif; ?>

<meta itemprop="price" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['itemprop_price'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
"  />
<meta itemprop="priceCurrency" content="USD"/>
<meta itemprop="seller" content="S3 Stores Inc."/>
</div>

 <?php endif; ?>

</div>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>

<?php if ($this->_tpl_vars['product']['seo_h2'] != ""): ?>
	<?php $this->assign('product_description_title', $this->_tpl_vars['product']['seo_h2']);  else: ?>
	<?php if ($this->_tpl_vars['current_storefront_info']['storefrontid'] == '50'): ?>
		<?php $this->assign('product_description_title', ($this->_tpl_vars['product']['mpn'])." ".($this->_tpl_vars['lng']['lbl_product_description'])); ?>
	<?php else: ?>
		<?php $this->assign('product_description_title', $this->_tpl_vars['lng']['lbl_product_description']); ?>
	<?php endif;  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['product_description_title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','use_h2' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

        <?php elseif ($this->_tpl_vars['tab']['tpl'] == '_Brand_'): ?>

<br />
<?php ob_start(); ?>

<?php if ($this->_tpl_vars['brand_image']['filename'] != ""): ?>
<img src="images/B/<?php echo $this->_tpl_vars['brand_image']['filename']; ?>
" style="float: left; margin: 10px 10px 10px 0;" />
<?php endif; ?>

<p align="justify">
<?php echo $this->_tpl_vars['brandid_brands_info'][$this->_tpl_vars['product']['brandid']]['descr']; ?>

<br />
<a href="/brands.php?brandid=<?php echo $this->_tpl_vars['product']['brandid']; ?>
" class="NavigationPath">All <?php echo $this->_tpl_vars['brandid_brands_info'][$this->_tpl_vars['product']['brandid']]['brand']; ?>
 products</a>
</p>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['brandid_brands_info'][$this->_tpl_vars['product']['brandid']]['brand'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','use_h2' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<?php elseif ($this->_tpl_vars['tab']['tpl'] == '_product_queries_tpl_'): ?>

<?php echo $this->_tpl_vars['lng']['lbl_product_queries_pre_instructions']; ?>

<br >
<br >
<?php if ($this->_tpl_vars['productqueries_page_arr'] != ""): ?>

<?php $_from = $this->_tpl_vars['productqueries_page_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['v']):
?>

[<?php echo $this->_tpl_vars['v']['time']; ?>
] User <?php echo $this->_tpl_vars['v']['username']; ?>
 asks <a href="<?php echo $this->_tpl_vars['v']['url']; ?>
" target="_blank"><?php echo $this->_tpl_vars['v']['name']; ?>
</a> (click on link to review detailed question)<br />
<br />
<?php if ($this->_tpl_vars['v']['answers'] != ""): ?>
        <?php $_from = $this->_tpl_vars['v']['answers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kk'] => $this->_tpl_vars['vv']):
?>
                &nbsp;&nbsp;&nbsp;&nbsp;[<?php echo $this->_tpl_vars['vv']['date']; ?>
] User <?php echo $this->_tpl_vars['vv']['username']; ?>
 wrote: <?php echo $this->_tpl_vars['vv']['content']; ?>
<br />
                <?php if ($this->_tpl_vars['vv']['comments'] != ""): ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Comments:<br />
                        <?php $_from = $this->_tpl_vars['vv']['comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['kkk'] => $this->_tpl_vars['vvv']):
?>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['vvv']['content']; ?>
<br /><br />
                        <?php endforeach; endif; unset($_from); ?>
                        <br />
                <?php endif; ?>
        <?php endforeach; endif; unset($_from);  endif; ?>
<br />
<?php endforeach; endif; unset($_from);  endif; ?>

<?php echo $this->_tpl_vars['lng']['lbl_product_queries_after_instructions']; ?>

<br />
<br />

<?php if ($this->_tpl_vars['product_form_info'] != ""): ?>
	<?php echo $this->_tpl_vars['product_form_info']; ?>

<?php endif; ?>

	<?php elseif ($this->_tpl_vars['tab']['tpl'] == '_product_question_tpl_'): ?>
<div id="product_question_pre">
<?php echo $this->_tpl_vars['lng']['lbl_product_question_pre_instructions']; ?>

<br />
<br />
<form name="product_question_email_form" action="" method="POST" >
<table cellpadding="1" cellspacing="3" width="100%">

 <tr>
  <td class="cidev_padding_top" align="right">Your First name:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
        <input type="text" id="firstname" name="firstname" size="32" maxlength="32" value="" onkeyup="cidev_check_field_name('firstname')" />
  </td>
 </tr>

 <tr>
  <td align="right" class="cidev_padding_top">Your email:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
	<input type="text" id="email" name="email" size="32" maxlength="128" value="" />
	<input type="hidden" id="question_productid" name="question_productid" size="32" maxlength="128" value="<?php echo $this->_tpl_vars['productid']; ?>
" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">Your phone:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
	<input type="text" id="phone" name="phone" size="32" maxlength="32" value="" onkeyup="cidev_check_field_phone('phone')" />
  </td>
 </tr>

 <tr>
  <td class="cidev_padding_top" align="right">Product question:
	<div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_FIELD_DESCRIPTION_Product_question']; ?>
</div>
  </td>
  <td><font class="Star">*</font></td>
  <td>
	<textarea style="width: 98%" name="question" id="question" cols="60" rows="10"></textarea>
  </td>
 </tr>

 <tr>
  <td colspan="3" align="center" id="button_submit_question_id">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => 'Submit question','type' => 'input','href' => "javascript: check_question_email_form();",'js_to_href' => 'Y','b' => '1')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  </td>
 </tr>

</table>
</form>
</div>

<div id="product_question_after"></div>


<?php if ($this->_tpl_vars['product']['product_questions'] != ""): ?>
<br />
<br />
<hr />
<?php $_from = $this->_tpl_vars['product']['product_questions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k_q'] => $this->_tpl_vars['v_q']):
?>

	<span style="color: #cc0000; font-weight: bold; font-size: 12px;">QUESTION</span><br />
	<?php echo $this->_tpl_vars['v_q']['question']; ?>
<br />
	<span style="color: #aaaaaa;"><I>asked <?php if ($this->_tpl_vars['v_q']['firstname'] != ""): ?>by <?php echo $this->_tpl_vars['v_q']['firstname']; ?>
 <?php endif; ?>on <?php echo ((is_array($_tmp=$this->_tpl_vars['v_q']['date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%b %d, %Y') : smarty_modifier_date_format($_tmp, '%b %d, %Y')); ?>
</I></a>
	
	<?php if ($this->_tpl_vars['v_q']['answer'] != ""): ?>
		<div style="padding-left: 50px; padding-top: 10px;">
		<span style="color: #006500; font-weight: bold; font-size: 12px;">BEST ANSWER</span><br />
		<?php echo $this->_tpl_vars['v_q']['answer']; ?>
<br />

		<?php if ($this->_tpl_vars['v_q']['operator_name'] != ""): ?>
		<span style="color: #aaaaaa;"><I>answered by <?php echo $this->_tpl_vars['v_q']['operator_first_name']; ?>
 (Staff) on <?php echo ((is_array($_tmp=$this->_tpl_vars['v_q']['answered_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%b %d, %Y') : smarty_modifier_date_format($_tmp, '%b %d, %Y')); ?>
</I></a>
		<?php endif; ?>
		</div>
	<?php endif; ?>
<br />
<?php endforeach; endif; unset($_from);  endif; ?>

        <?php elseif ($this->_tpl_vars['tab']['tpl'] == '_product_discussions_tpl_'): ?>

<div id="disqus_thread"></div>
<script type="text/javascript">
//<![CDATA[
<?php echo '
    /* * * CONFIGURATION VARIABLES * * */
    var disqus_shortname = \'s3stores\';
    
    /* * * DON\'T EDIT BELOW THIS LINE * * */
    (function() {
        var dsq = document.createElement(\'script\'); dsq.type = \'text/javascript\'; dsq.async = true;
        dsq.src = \'//\' + disqus_shortname + \'.disqus.com/embed.js\';
        (document.getElementsByTagName(\'head\')[0] || document.getElementsByTagName(\'body\')[0]).appendChild(dsq);
    })();
'; ?>

//]]>
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>


	<?php else: ?>

<?php if ($this->_tpl_vars['tab']['title'] == 'Shipping'): ?>
	<?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != '' || $this->_tpl_vars['show_dimensions']): ?>
	<br />
	<?php endif; ?>

<table width="100%" cellpadding="0" cellspacing="0">

<?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != ''): ?>
<tr id="product_weight_box">
        <td width="22%">Shipping weight:</td>
        <td nowrap="nowrap"><span id="product_weight"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
</span> <?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
</td>
</tr>
<?php if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?>
<meta itemprop="weight" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
 <?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
" />
<?php endif;  endif;  if ($this->_tpl_vars['show_dimensions']): ?>
<tr>
        <td width="22%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_shipping_dimensions']; ?>
:</td>
        <td nowrap="nowrap">
		<span id="product_weight">

<?php if ($this->_tpl_vars['show_dimensions_orderby_str'] != ""):  echo $this->_tpl_vars['show_dimensions_orderby_str']; ?>

<?php else:  echo $this->_tpl_vars['product']['dim_x']; ?>
" x <?php echo $this->_tpl_vars['product']['dim_y']; ?>
" x <?php echo $this->_tpl_vars['product']['dim_z']; ?>
"
<?php endif; ?>
		</span>
	</td>
</tr>
<?php endif; ?>
</table>

	<?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != '' || $this->_tpl_vars['show_dimensions']): ?>
	<br />
	<?php endif;  endif; ?>

		<?php echo $this->_tpl_vars['tab']['tpl']; ?>

	<?php endif; ?>
      </div>
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>

</div>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/ui_tabs.tpl"), $this); endif; ?>