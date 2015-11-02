<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/main/product.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/product.tpl', 1, false),array('function', 'math', 'customer/main/product.tpl', 69, false),array('function', 'currency', 'customer/main/product.tpl', 152, false),array('modifier', 'price_format', 'customer/main/product.tpl', 74, false),array('modifier', 'amp', 'customer/main/product.tpl', 90, false),array('modifier', 'escape', 'customer/main/product.tpl', 106, false),array('modifier', 'default', 'customer/main/product.tpl', 177, false),array('modifier', 'formatprice', 'customer/main/product.tpl', 417, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/product.tpl","lbl_sp_ttl_bonus_points,lbl_in_stock_top,lbl_out_stock,lbl_save,lbl_add_more,lbl_add_to_cart,lbl_more_images,lbl_product_question_pre_instructions,lbl_FIELD_DESCRIPTION_Product_question,lbl_shipping_dimensions"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/product.tpl"), $this); endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "form_validation_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['use_schema_org'] == 'Y'):  if ($this->_tpl_vars['current_storefront'] == '0'):  if ($this->_tpl_vars['product']['clean_url'] != ""): ?>
<meta itemprop="url" content="http://www.artistsupplysource.com/<?php echo $this->_tpl_vars['product']['clean_url']; ?>
/" />
<?php else: ?>
<meta itemprop="url" content="http://www.artistsupplysource.com/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<?php endif;  else:  if ($this->_tpl_vars['product']['clean_url'] != ""): ?>
<meta itemprop="url" content="http://<?php echo $this->_tpl_vars['cidev_store_domain']; ?>
/<?php echo $this->_tpl_vars['product']['clean_url']; ?>
/" />
<?php else: ?>
<meta itemprop="url" content="http://<?php echo $this->_tpl_vars['cidev_store_domain']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<?php endif;  endif;  endif; ?>

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

<?php if ($this->_tpl_vars['product']['new_notify_in_stock_price'] != ""): ?>
        <?php $this->assign('current_price', $this->_tpl_vars['product']['new_notify_in_stock_price']);  else: ?>
        <?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
                <?php $this->assign('current_price', $this->_tpl_vars['product']['map_price']); ?>
        <?php else: ?>
                <?php $this->assign('current_price', $this->_tpl_vars['product']['taxed_price']); ?>
        <?php endif;  endif; ?>

<?php if ($this->_tpl_vars['product_wholesale']['0']['price'] != "" && $this->_tpl_vars['product']['new_notify_in_stock_price'] == "" && $this->_tpl_vars['product']['map_price'] <= $this->_tpl_vars['product']['taxed_price']): ?>
        <?php $this->assign('current_price', $this->_tpl_vars['product_wholesale']['0']['price']);  endif; ?>

<?php if ($this->_tpl_vars['product']['min_amount'] > 1 && $this->_tpl_vars['product']['mult_order_quantity'] == 'Y'): ?>
        <?php echo smarty_function_math(array('assign' => 'itemprop_price','equation' => "y*x",'y' => $this->_tpl_vars['product']['min_amount'],'x' => $this->_tpl_vars['current_price']), $this);?>

<?php else: ?>
        <?php $this->assign('itemprop_price', $this->_tpl_vars['current_price']);  endif; ?>

<meta itemprop="price" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['itemprop_price'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
"/>
<meta itemprop="priceCurrency" content="USD"/>
<meta itemprop="seller" content="S3 Stores Inc."/>
</div>

 <?php endif; ?>


<div class="product-details">
  <?php if ($this->_tpl_vars['active_modules']['Special_Offers'] || ( $this->_tpl_vars['product']['appearance']['has_market_price'] && $this->_tpl_vars['product']['appearance']['market_price_discount'] > 0 )): ?>
    <?php $this->assign('custom_top_info', 'true'); ?>
  <?php endif; ?>
  <div class="top-info ui-body ui-body-b ui-overlay-shadow">
    <div class="ui-grid-<?php if ($this->_tpl_vars['active_modules']['Special_Offers'] && $this->_tpl_vars['product']['bonus_points'] > 0): ?>a<?php else: ?>solo<?php endif; ?>">
      <div class="ui-block-a">
        <h1><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['producttitle'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</h1>
      </div>
      <?php if ($this->_tpl_vars['active_modules']['Special_Offers'] && $this->_tpl_vars['product']['bonus_points'] > 0): ?>
        <div class="ui-block-b">
          <div class="right-block bp-info">
            <ul data-role="listview" data-inset="true">
              <li data-theme="e" class="bp-info">
                +<?php echo $this->_tpl_vars['product']['bonus_points']; ?>
&nbsp;<?php echo $this->_tpl_vars['lng']['lbl_sp_ttl_bonus_points']; ?>

              </li>
            </ul>
          </div>     
        </div>
      <?php endif; ?>
    </div>
    <div class="ui-grid-a">
      <div class="ui-block-a">
        <div class="sku<?php if ($this->_tpl_vars['product']['appearance']['has_market_price'] && $this->_tpl_vars['product']['appearance']['market_price_discount'] > 0): ?> save-mark-here<?php endif; ?>" id="product_code"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['productcode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
        <?php if ($this->_tpl_vars['product']['distribution'] == "" && ! ( $this->_tpl_vars['product']['product_type'] == 'C' && $this->_tpl_vars['active_modules']['Product_Configurator'] )): ?>
          <div class="product-quantity-text-top<?php if ($this->_tpl_vars['product']['avail'] > 0 || $this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'): ?> in-stock<?php endif; ?>">

            <?php if ($this->_tpl_vars['product']['avail'] > 0 || $this->_tpl_vars['config']['General']['unlimited_products'] == 'Y'): ?>
              <?php echo $this->_tpl_vars['lng']['lbl_in_stock_top']; ?>

            <?php else: ?>
              <?php echo $this->_tpl_vars['lng']['lbl_out_stock']; ?>

            <?php endif; ?>

          </div>
        <?php endif; ?>
      </div>

      <?php if (! ( $this->_tpl_vars['product']['product_type'] == 'C' && $this->_tpl_vars['active_modules']['Product_Configurator'] )): ?>
        <div class="ui-block-b">
          <div class="right-block">
            <ul data-role="listview" data-inset="true">
              <?php if ($this->_tpl_vars['product']['appearance']['has_market_price'] && $this->_tpl_vars['product']['appearance']['market_price_discount'] > 0): ?>
                <?php echo '<li data-theme="c" class="save-percent-container" id="save_percent_box"><span class="save">';  echo $this->_tpl_vars['lng']['lbl_save'];  echo '&nbsp;<span id="save_percent">';  echo $this->_tpl_vars['product']['appearance']['market_price_discount'];  echo '</span>%</span></li>'; ?>

              <?php endif; ?>

<?php if (( $this->_tpl_vars['config']['General']['unlimited_products'] == 'N' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['variants'] == '' && $this->_tpl_vars['product_feed_enabled'] == 'Y' && $this->_tpl_vars['notify_when_in_stock'][$this->_tpl_vars['product']['productid']] != 'Y' ) || ! ( $this->_tpl_vars['product']['avail'] > 0 || $this->_tpl_vars['config']['General']['unlimited_products'] == 'Y' )): ?>

<?php else: ?>
              <li data-theme="b" id="top-cart-button">
                <?php echo '<a href="';  echo $this->_tpl_vars['catalogs']['customer'];  echo '/cart.php"';  if ($this->_tpl_vars['product']['lead_time_message'] != ""):  echo 'onclick="javascript: if (confirm(\'';  echo $this->_tpl_vars['product']['lead_time_message'];  echo '\')) $(\'#orderform-';  echo $this->_tpl_vars['product']['productid'];  echo '\').submit();"';  else:  echo 'onclick="javascript: $(\'#orderform-';  echo $this->_tpl_vars['product']['productid'];  echo '\').submit();"';  endif;  echo '>';  echo smarty_function_currency(array('value' => $this->_tpl_vars['product']['taxed_price'],'tag_id' => ""), $this); echo '';  if ($this->_tpl_vars['product']['appearance']['added_to_cart']):  echo '';  echo $this->_tpl_vars['lng']['lbl_add_more'];  echo '';  else:  echo '';  echo $this->_tpl_vars['lng']['lbl_add_to_cart'];  echo '';  endif;  echo '</a>'; ?>

              </li>
<?php endif; ?>
            </ul>
          </div>
        </div>	   
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="product-details">
  <div class="image">
    <div class="image-box"<?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] && $this->_tpl_vars['images'] != ''): ?> style="display: block;"<?php endif; ?>>
      <?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] && $this->_tpl_vars['images'] != ''): ?>
        <ul data-role="listview" data-inset="true">
          <li data-icon="false">
            <a href="<?php echo $this->_tpl_vars['current_location']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
&mobile_mode=get_detailed_images">
            <?php endif; ?>
            <img src="<?php if ($this->_tpl_vars['product']['image_url']):  echo ((is_array($_tmp=$this->_tpl_vars['product']['image_url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  else:  echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?type=<?php echo ((is_array($_tmp=@$this->_tpl_vars['type'])) ? $this->_run_mod_handler('default', true, $_tmp, 'T') : smarty_modifier_default($_tmp, 'T')); ?>
&amp;id=<?php echo $this->_tpl_vars['product']['productid'];  endif; ?>" id="product_thumbnail" style="width: <?php echo $this->_tpl_vars['product']['image_x']; ?>
px; height: <?php echo $this->_tpl_vars['product']['image_y']; ?>
px;" alt="<?php echo $this->_tpl_vars['product']['product']; ?>
" />
            <?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] && $this->_tpl_vars['images'] != ''): ?>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($this->_tpl_vars['active_modules']['Detailed_Product_Images'] && $this->_tpl_vars['images'] != ''): ?>
          <li data-icon="plus" data-theme="b">
            <a href="<?php echo $this->_tpl_vars['current_location']; ?>
/product.php?productid=<?php echo $this->_tpl_vars['product']['productid']; ?>
&mobile_mode=get_detailed_images" ><?php echo $this->_tpl_vars['lng']['lbl_more_images']; ?>
</a>
          </li>
        </ul>
      <?php endif; ?>
    </div>
  </div>
  <div class="details">
    <?php if ($this->_tpl_vars['product']['product_type'] == 'C' && $this->_tpl_vars['active_modules']['Product_Configurator']): ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Configurator/pconf_customer_product.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php else: ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/product_details.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != ""): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/product_buttons.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<?php if ($this->_tpl_vars['product_tabs']): ?>
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/check_email_script.js" type="text/javascript"></script>
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/cidev_ajax.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[
<?php echo '
function check_question_email_form() {

        if ($("#email").val()!="" && $("#phone").val()!="" && $("#question").val()!=""){

                if (checkEmailAddress(document.product_question_email_form.email, \'Y\')){
                  send_question_email_form();
                }

        } else {
                alert("Please fill in all fields");
                return false;
        }
}

function send_question_email_form(){

        cidev_xmlHttp=cidev_createHttpRequestObject();
        if (cidev_xmlHttp.readyState==4 || cidev_xmlHttp.readyState==0){

                var cidev_parameters = \'cidev_mode=send&email=\' + $("#email").val() + \'&phone=\' + $("#phone").val() + \'&question=\' + $("#question").val() + \'&productid=\' + $(\'#question_productid\').val();

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
//                cidev_xmlHttp.setRequestHeader(\'Content-length\',cidev_parameters.length);
//                cidev_xmlHttp.setRequestHeader(\'Connection\',\'close\');
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
//                        checkEmailAddress(document.product_question_email_form.email, \'Y\');
                }
        });
  });
'; ?>

//]]>
</script>


  <?php $_from = $this->_tpl_vars['product_tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ind'] => $this->_tpl_vars['tab']):
?>
    <div data-role="collapsible" data-collapsed="true">
      <h3><?php echo $this->_tpl_vars['tab']['title']; ?>
</h3>
      <div>

        <?php if ($this->_tpl_vars['tab']['tpl'] == '_product_description_'): ?>

          <?php if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?><span itemprop="description"><?php endif;  echo ((is_array($_tmp=@$this->_tpl_vars['product']['fulldescr'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['descr']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['descr']));  if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?></span><?php endif; ?>

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

        <?php elseif ($this->_tpl_vars['tab']['tpl'] == '_product_question_tpl_'): ?>
<div id="product_question_pre">
<?php echo $this->_tpl_vars['lng']['lbl_product_question_pre_instructions']; ?>

<br />
<br />
<form name="product_question_email_form" action="" method="POST" >
<table cellpadding="1" cellspacing="3" width="100%">

 <tr>
  <td align="right" class="cidev_padding_top">Your email:</td>
  <td><font class="Star">*</font></td>
  <td nowrap="nowrap">
        <input type="text" id="email" name="email" size="32" maxlength="128" value="" />
        <input type="hidden" id="question_productid" name="question_productid" size="32" maxlength="128" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
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
  <td colspan="3" align="center">
        <input type="button" name="Submit question" value="Submit question" onclick="javasript: check_question_email_form();" />
  </td>
 </tr>

</table>
</form>
</div>

<div id="product_question_after"></div>


<?php if ($this->_tpl_vars['product']['product_questions'] != ""): ?>
<br />
<br />
<?php $_from = $this->_tpl_vars['product']['product_questions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k_q'] => $this->_tpl_vars['v_q']):
?>

        <?php echo $this->_tpl_vars['v_q']['question']; ?>
<br />

        <?php if ($this->_tpl_vars['v_q']['answer'] != ""): ?>
                <div style="padding-left: 50px;"><?php echo $this->_tpl_vars['v_q']['answer']; ?>
</div>
        <?php endif; ?>

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

<?php if ($this->_tpl_vars['product']['upc_ean_isbn']): ?>
<tr>
        <td width="22%" nowrap="nowrap"><?php echo $this->_tpl_vars['product']['upc_ean_isbn']['type']; ?>
:</td>
        <td nowrap="nowrap"><?php if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?><span itemprop="gtin13"><?php endif;  echo $this->_tpl_vars['product']['upc_ean_isbn']['value'];  if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?></span><?php endif; ?></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != ''): ?>

<?php if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?>
<meta itemprop="weight" content="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
 <?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
" />
<?php endif; ?>

<tr id="product_weight_box">
        <td width="22%">Shipping weight:</td>
        <td nowrap="nowrap"><span id="product_weight"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
</span> <?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['show_dimensions']): ?>
<tr>
        <td width="22%" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_shipping_dimensions']; ?>
:</td>
        <td nowrap="nowrap"><span id="product_weight"><?php echo $this->_tpl_vars['product']['dim_x']; ?>
" x <?php echo $this->_tpl_vars['product']['dim_y']; ?>
" x <?php echo $this->_tpl_vars['product']['dim_z']; ?>
"</span></td>
</tr>
<?php endif; ?>
</table>
        <?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != '' || $this->_tpl_vars['show_dimensions']): ?>
        <br />
        <?php endif;  endif; ?>

                <?php echo $this->_tpl_vars['tab']['tpl']; ?>

        <?php endif; ?>


      </div>
    </div>
  <?php endforeach; endif; unset($_from);  endif; ?>



<?php if ($this->_tpl_vars['active_modules']['Product_Options'] && ( $this->_tpl_vars['product_options'] != '' || $this->_tpl_vars['product_wholesale'] != '' ) && ( $this->_tpl_vars['product']['product_type'] != 'C' || ! $this->_tpl_vars['active_modules']['Product_Configurator'] )): ?>
  <script type="text/javascript">
    //<![CDATA[
    check_options();
    //]]>
  </script>
<?php endif; ?>


<?php if ($this->_tpl_vars['config']['Security']['ssl_seal'] != ""): ?>
<br /><?php echo $this->_tpl_vars['config']['Security']['ssl_seal']; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Upselling_Products'] != ""):  if ($this->_tpl_vars['product_links']): ?>
<p />
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Upselling_Products/related_products.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['similar_products'] != ""): ?>
<br />
<p />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/similar_products.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/product.tpl"), $this); endif; ?>