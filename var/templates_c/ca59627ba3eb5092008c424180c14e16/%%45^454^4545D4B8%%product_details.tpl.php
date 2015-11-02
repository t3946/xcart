<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/main/product_details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/product_details.tpl', 1, false),array('function', 'math', 'customer/main/product_details.tpl', 109, false),array('function', 'currency', 'customer/main/product_details.tpl', 362, false),array('function', 'load_defer_code', 'customer/main/product_details.tpl', 428, false),array('modifier', 'escape', 'customer/main/product_details.tpl', 42, false),array('modifier', 'price_format', 'customer/main/product_details.tpl', 117, false),array('modifier', 'string_format', 'customer/main/product_details.tpl', 117, false),array('modifier', 'replace', 'customer/main/product_details.tpl', 117, false),array('modifier', 'default', 'customer/main/product_details.tpl', 147, false),array('modifier', 'substitute', 'customer/main/product_details.tpl', 167, false),array('modifier', 'strip', 'customer/main/product_details.tpl', 181, false),array('modifier', 'func_mobile_variants_has_wl', 'customer/main/product_details.tpl', 259, false),array('modifier', 'formatprice', 'customer/main/product_details.tpl', 312, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/product_details.tpl","lbl_quantity,txt_out_of_stock,lbl_list_price,lbl_price,lbl_quantity,txt_out_of_stock,txt_need_min_amount_mult,txt_need_min_amount,lbl_quantity,txt_product_downloadable,txt_need_min_amount,lbl_weight,lbl_yes,lbl_no,lbl_ask_question_about_product,txt_pconf_product_is_bundled,lbl_pconf_add_to_configuration,lbl_note,lbl_pconf_slot_out_of_stock_note,txt_add_to_configuration_note"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/product_details.tpl"), $this); endif; ?><script type="text/javascript">
  //<![CDATA[
  var has_options = false;
  <?php echo '
    // workaround for the disabled "ajax add to cart" widget
  var ajax = {widgets: {
          add2cart : function () { return false }
        }
      }
  '; ?>

    //]]>
</script>

<?php if ($this->_tpl_vars['product']['new_notify_in_stock_price'] != ""): ?>
        <?php $this->assign('current_price', $this->_tpl_vars['product']['new_notify_in_stock_price']);  else: ?>
        <?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
                <?php $this->assign('current_price', $this->_tpl_vars['product']['map_price']); ?>
        <?php else: ?>
                <?php $this->assign('current_price', $this->_tpl_vars['product']['taxed_price']); ?>
        <?php endif;  endif; ?>


<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'N' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['variants'] == '' && $this->_tpl_vars['product_feed_enabled'] == 'Y'): ?>
<form name="notifyform" method="post" action="product.php">
<input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
<input type="hidden" id="notify_mode" name="mode" value="" />
<input type="hidden" id="notify_email" name="notify_email" value="" />
</form>
<?php endif; ?>



<form name="orderform" method="post" action="<?php echo $this->_tpl_vars['catalogs']['customer']; ?>
/cart.php" onsubmit="javascript: return FormValidation(this);" id="orderform-<?php echo $this->_tpl_vars['product']['productid']; ?>
">
  <input type="hidden" name="mode" value="<?php if ($this->_tpl_vars['active_modules']['Gift_Registry'] && $this->_tpl_vars['wishlistid']): ?>wl2cart<?php else: ?>add<?php endif; ?>" />
  <input type="hidden" name="productid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
  <input type="hidden" name="cat" value="<?php echo ((is_array($_tmp=$GLOBALS['_GET']['cat'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
  <input type="hidden" name="page" value="<?php echo ((is_array($_tmp=$GLOBALS['_GET']['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
  <?php if ($this->_tpl_vars['active_modules']['Gift_Registry'] && $this->_tpl_vars['wishlistid']): ?>
    <input type="hidden" name="fwlitem" value="<?php echo $this->_tpl_vars['wishlistid']; ?>
" />
    <input type="hidden" name="eventid" value="<?php echo $this->_tpl_vars['eventid']; ?>
" />
  <?php endif; ?>
  <?php if ($this->_tpl_vars['product']['forsale'] != 'B' || ( $this->_tpl_vars['product']['forsale'] == 'B' && $GLOBALS['_GET']['pconf'] != "" && $this->_tpl_vars['active_modules']['Product_Configurator'] )): ?>
    <div class="product-properties">
        <?php if ($this->_tpl_vars['product']['appearance']['empty_stock'] && ( $this->_tpl_vars['variants'] == '' || ( $this->_tpl_vars['variants'] != '' && $this->_tpl_vars['product']['avail'] <= 0 ) )): ?>
          <label class="property-name product-input"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
</label>
          <span class="property-value">
            <script type="text/javascript">
              //<![CDATA[
              var min_avail = 1;
              var avail = 0;
              var product_avail = 0;
              //]]>
            </script>
            <strong><?php echo $this->_tpl_vars['lng']['txt_out_of_stock']; ?>
</strong>
          </span>
        <?php elseif (! $this->_tpl_vars['product']['appearance']['force_1_amount'] && $this->_tpl_vars['product']['forsale'] != 'B'): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Product_Options/customer_options.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table width="100%" cellpadding="0" cellspacing="0" style="font-size: 20px;">

<?php if ($this->_tpl_vars['current_price'] > 0 && $this->_tpl_vars['product']['list_price'] > 0 && $this->_tpl_vars['product']['list_price'] > $this->_tpl_vars['current_price']): ?>
<tr>
<td nowrap="nowrap" class="BlackT" width="30%" valign="top"><?php echo $this->_tpl_vars['lng']['lbl_list_price']; ?>
:</td>
<td><font style=" font-size: 20px; color: #848C84;"><strike><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['product']['list_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></strike></font></td>
</tr>
<?php endif; ?>

<tr>
<td width="30%" class="ProductPriceConverting" valign="top"><?php echo $this->_tpl_vars['lng']['lbl_price']; ?>
:</td>
<td width="70%" valign="top">
<?php if ($this->_tpl_vars['current_price'] != 0 || $this->_tpl_vars['variant_price_no_empty']): ?>

                <?php if ($this->_tpl_vars['product']['new_notify_in_stock_price'] != "" && $this->_tpl_vars['current_price'] == $this->_tpl_vars['product']['new_notify_in_stock_price']): ?>
                <input type="hidden" name="new_notify_in_stock_price" id="new_notify_in_stock_price" />
        <?php endif; ?>
        
<font class="ProductPriceConverting"><span id="product_price" style="white-space: nowrap;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "currency.tpl", 'smarty_include_vars' => array('value' => $this->_tpl_vars['current_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span></font>
<font class="MarketPrice"> <span id="product_alt_price" style="white-space: nowrap;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/alter_currency_value.tpl", 'smarty_include_vars' => array('alter_currency_value' => $this->_tpl_vars['current_price'],'plain_text_message' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></span></font>
<?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
<br />
<span class="map_price_help"><?php echo $this->_tpl_vars['config']['Product_Page']['map_bridge_text']; ?>
</span>
<?php endif;  else: ?>
<input type="text" size="7" name="price" />
<?php endif; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['current_price'] > 0 && $this->_tpl_vars['product']['list_price'] > 0 && $this->_tpl_vars['product']['list_price'] > $this->_tpl_vars['current_price']):  echo smarty_function_math(array('equation' => "100-(price/lprice)*100",'price' => $this->_tpl_vars['current_price'],'lprice' => $this->_tpl_vars['product']['list_price'],'format' => "%3.5f",'assign' => 'discount'), $this);?>

<?php if ($this->_tpl_vars['discount'] >= 1):  echo smarty_function_math(array('equation' => "lprice - price",'price' => $this->_tpl_vars['current_price'],'lprice' => $this->_tpl_vars['product']['list_price'],'format' => "%3.5f",'assign' => 'saved_price'), $this);?>

<TR id="save_percent_box"<?php if ($this->_tpl_vars['product']['taxed_price'] >= $this->_tpl_vars['product']['list_price']): ?> style="display: none;"<?php endif; ?>>
<TD nowrap="nowrap">
<font style="font-size: 20px; color: #CC3333;">You save:</font>
</TD>
<TD nowrap="nowrap" style="font-size: 12px; font-weight: normal; color: #CC3333;">
<SPAN id="save_percent">$<?php echo ((is_array($_tmp=$this->_tpl_vars['saved_price'])) ? $this->_run_mod_handler('price_format', true, $_tmp) : price_format($_tmp)); ?>
 (<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['discount'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%3.0f") : smarty_modifier_string_format($_tmp, "%3.0f")))) ? $this->_run_mod_handler('replace', true, $_tmp, ' ', "") : smarty_modifier_replace($_tmp, ' ', "")); ?>
%)</SPAN>
</TD>
</TR>
<?php endif;  endif; ?>


          <script type="text/javascript">
            //<![CDATA[
            var min_avail = <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['appearance']['min_quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
;
            var avail = <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['appearance']['max_quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
;
            var product_avail = <?php echo ((is_array($_tmp=@$this->_tpl_vars['product']['avail'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
            //]]>
          </script>

<tr>
<TD nowrap="nowrap" style="font-size: 20px; font-weight: normal;">
<?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
:
</TD>
<TD>
<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'N' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['variants'] == ''): ?>
<b><?php echo $this->_tpl_vars['lng']['txt_out_of_stock']; ?>
</b>
<?php else: ?>
            <input type="number" class="qty-input" id="product_avail" name="amount" maxlength="11" size="6" onkeyup="check_wholesale(this.value);" value="<?php echo ((is_array($_tmp=@$GLOBALS['_GET']['quantity'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['product']['min_amount']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['product']['min_amount'])); ?>
" style="width: 100px; height: 50px;" />


<font style="font-size: 20px; color: #CC3333;"><?php if ($this->_tpl_vars['product']['mult_order_quantity'] == 'Y'):  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount_mult'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount']));  else:  echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount']));  endif; ?></font>

<?php endif; ?>
</TD>
</TR>

</TABLE>
            <div class="clearing"></div>
          <div class="ui-select" style="display: none;">
            <div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-d" data-iconpos="right" data-theme="c" class="ui-btn ui-btn-icon-right ui-corner-bottom ui-controlgroup-last ui-btn-up-c">
              <span class="ui-btn-inner ui-corner-bottom ui-controlgroup-last">
              <?php echo '<span class="ui-btn-text">';  echo ((is_array($_tmp=$this->_smarty_vars['capture']['qty_title'])) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp));  echo ':&nbsp;<span id="qty_select">1</span></span>'; ?>

              <span class="ui-icon ui-icon-arrow-d ui-icon-shadow">&nbsp;</span>
            </span>
            <select data-role="none" id="product_avail" name="amount"<?php if ($this->_tpl_vars['active_modules']['Product_Options'] != '' && ( $this->_tpl_vars['product_options'] != '' || $this->_tpl_vars['product_wholesale'] != '' )): ?> onchange="javascript: check_wholesale(this.value);"<?php endif; ?> disabled="disabled" style="display: none;">
              <option value="<?php echo $this->_tpl_vars['product']['appearance']['min_quantity']; ?>
"<?php if ($GLOBALS['_GET']['quantity'] == $this->_tpl_vars['product']['appearance']['min_quantity']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['product']['appearance']['min_quantity']; ?>
</option>
            </select>
          </div>
        </div>
    </div>
  <?php else: ?>
    <label class="property-name product-input"><?php echo $this->_tpl_vars['lng']['lbl_quantity']; ?>
</label>
    <span class="property-value">
      <script type="text/javascript">
        //<![CDATA[
        var min_avail = 1;
        var avail = 1;
        var product_avail = 1;
        //]]>
      </script>
      <span class="product-one-quantity">1</span>
      <input type="hidden" name="amount" value="1" />
      <?php if ($this->_tpl_vars['product']['distribution'] != ""): ?>
        <?php echo $this->_tpl_vars['lng']['txt_product_downloadable']; ?>

      <?php endif; ?>
    </span>
  <?php endif; ?>


<table style="font-size: 20px; font-weight: normal;">
<?php if ($this->_tpl_vars['product']['eta_date_in_future'] == 'Y'): ?>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td>Expected availability:</td>
<td><?php echo $this->_tpl_vars['product']['eta_date_dd_month_yyyy']; ?>
</td>
</tr>
<?php if ($this->_tpl_vars['product']['allow_pre_orders'] != 'Y'): ?>
<tr><td colspan="2">Sorry we don't take pre-orders.</td></tr>
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['config']['General']['unlimited_products'] == 'N' && ( $this->_tpl_vars['product']['avail'] <= 0 || $this->_tpl_vars['product']['avail'] < $this->_tpl_vars['product']['min_amount'] ) && $this->_tpl_vars['variants'] == '' && $this->_tpl_vars['product_feed_enabled'] == 'Y' && $this->_tpl_vars['notify_when_in_stock'][$this->_tpl_vars['product']['productid']] != 'Y'): ?>

        <?php if ($this->_tpl_vars['product']['eta_date_in_future'] != 'Y'): ?>
        <tr><td colspan="2">&nbsp;</td></tr>
        <?php endif; ?>

<tr id="notify_tr1">
<td colspan="2">
<I><a href="javascript: void(0);" onclick="javascript: $('#notify_tr1').hide(); $('#notify_tr2').show();" >Notify me when it's in stock</a></I>
</td>
</tr>

<tr id="notify_tr2" style="display: none;">
<td>Your email address:</td>
<td>
<input type="text" name="notify_email" value="" />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => 'Notify me','style' => 'button','href' => "javascript:if (checkEmailAddress(document.orderform.notify_email, 'Y')) ".($this->_tpl_vars['ldelim'])."document.notifyform.mode.value='notify';document.notifyform.notify_email.value=document.orderform.notify_email.value;document.notifyform.submit()".($this->_tpl_vars['rdelim']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<tr>
<tr><td colspan="2">&nbsp;</td></tr>
<?php endif; ?>
</table>


  <?php if ($this->_tpl_vars['product']['forsale'] != 'B' && ( $this->_tpl_vars['product_wholesale'] || func_mobile_variants_has_wl($this->_tpl_vars['variants']) )): ?>
    <div id="wl-prices-wrapper"<?php if (! $this->_tpl_vars['product_wholesale']): ?> style="display: none;"<?php endif; ?>>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/product_prices.tpl", 'smarty_include_vars' => array('mobile_skin' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
  <?php endif; ?>


  <ul class="properties-list">
    <?php if ($this->_tpl_vars['product']['min_amount'] > 1): ?>
      <li>
        <span class="property-value product-min-amount"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_need_min_amount'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['min_amount']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['min_amount'])); ?>
</span>
      </li>
    <?php endif; ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['product']['weight'] != "0.00" || $this->_tpl_vars['variants'] != ''): ?>
    <li id="product_weight_box"<?php if ($this->_tpl_vars['product']['weight'] == '0.00'): ?> style="display: none;"<?php endif; ?>>
      <label class="property-name"><?php echo $this->_tpl_vars['lng']['lbl_weight']; ?>
</label>
      <span class="property-value">
        <span id="product_weight"><?php echo ((is_array($_tmp=$this->_tpl_vars['product']['weight'])) ? $this->_run_mod_handler('formatprice', true, $_tmp) : smarty_modifier_formatprice($_tmp)); ?>
</span> <?php echo $this->_tpl_vars['config']['General']['weight_symbol']; ?>

      </span>
    </li>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Extra_Fields'] && $this->_tpl_vars['extra_fields']): ?>
    <?php $_from = $this->_tpl_vars['extra_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
      <?php if ($this->_tpl_vars['v']['active'] == 'Y' && $this->_tpl_vars['v']['field_value']): ?>
        <li>
          <label class="property-name"><?php echo $this->_tpl_vars['v']['field']; ?>
:</label>
          <span class="property-value"><?php echo $this->_tpl_vars['v']['field_value']; ?>
</span>
        </li>
      <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Feature_Comparison'] && $this->_tpl_vars['product']['features']['options']): ?>
    <?php $_from = $this->_tpl_vars['product']['features']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
?>
      <li>
        <label class="property-name">
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/option_hint.tpl", 'smarty_include_vars' => array('opt' => $this->_tpl_vars['v'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </label>
        <span class="property-value">
          <?php if ($this->_tpl_vars['v']['option_type'] == 'S'): ?>
            <?php echo $this->_tpl_vars['v']['variants'][$this->_tpl_vars['v']['value']]['variant_name']; ?>

          <?php elseif ($this->_tpl_vars['v']['option_type'] == 'M'): ?>
            <?php $_from = $this->_tpl_vars['v']['variants']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
              <?php if ($this->_tpl_vars['o']['selected'] != ''): ?>
                <?php echo $this->_tpl_vars['o']['variant_name']; ?>
<br />
              <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?>
          <?php elseif ($this->_tpl_vars['v']['option_type'] == 'B'): ?>
            <?php if ($this->_tpl_vars['v']['value'] == 'Y'): ?>
              <?php echo $this->_tpl_vars['lng']['lbl_yes']; ?>

            <?php else: ?>
              <?php echo $this->_tpl_vars['lng']['lbl_no']; ?>

            <?php endif; ?>
          <?php elseif (( $this->_tpl_vars['v']['option_type'] == 'N' || $this->_tpl_vars['v']['option_type'] == 'D' ) && $this->_tpl_vars['v']['value'] != ''): ?>
            <?php echo $this->_tpl_vars['v']['formated_value']; ?>

          <?php else: ?>
            <?php echo ((is_array($_tmp=$this->_tpl_vars['v']['value'])) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", "<br />") : smarty_modifier_replace($_tmp, "\n", "<br />")); ?>

          <?php endif; ?>
        </span>
      </li>
    <?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>
</ul>
<?php if ($this->_tpl_vars['product']['appearance']['buy_now_buttons_enabled']): ?>
  <?php if ($this->_tpl_vars['product']['forsale'] != 'B'): ?>
    <div class="ui-grid-solo">
      <div class="ui-block-a add-to-cart-button">
        <?php ob_start(); ?>
          <?php echo smarty_function_currency(array('value' => $this->_tpl_vars['product']['taxed_price'],'tag_id' => "",'assign' => 'top_price'), $this);?>

          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/add_to_cart.tpl", 'smarty_include_vars' => array('style' => 'link','type' => 'input','title_price' => $this->_tpl_vars['top_price'],'additional_button_class' => "main-button",'data_inline' => 'false','button_id' => "bottom-cart-button")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php $this->_smarty_vars['capture']['add_to_cart_button'] = ob_get_contents(); ob_end_clean(); ?>
        <?php echo $this->_smarty_vars['capture']['add_to_cart_button']; ?>

      </div>
    </div>
    <div class="ui-grid-<?php if ($this->_tpl_vars['config']['Company']['support_department'] != ""): ?>a<?php else: ?>solo<?php endif; ?>">
      <div class="ui-block-a">
        <?php if ($this->_tpl_vars['product']['appearance']['dropout_actions']): ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/add_to_list.tpl", 'smarty_include_vars' => array('id' => $this->_tpl_vars['product']['productid'],'js_if_condition' => "FormValidation()",'data_inline' => 'false')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php elseif ($this->_tpl_vars['product']['appearance']['buy_now_add2wl_enabled']): ?>
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/add_to_wishlist.tpl", 'smarty_include_vars' => array('href' => "javascript: if (FormValidation()) submitForm(document.orderform, 'add2wl', arguments[0]);",'data_inline' => 'false')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
      </div>
      <?php if ($this->_tpl_vars['config']['Company']['support_department'] != ""): ?>
        <div class="ui-block-b ask-question">
          <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_ask_question_about_product'],'style' => 'link','href' => "javascript: return !popupOpen(xcart_web_dir + '/popup_ask.php?productid=".($this->_tpl_vars['product']['productid'])."')",'data_inline' => 'false')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        </div>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <?php echo $this->_tpl_vars['lng']['txt_pconf_product_is_bundled']; ?>

  <?php endif; ?>
  <?php if ($GLOBALS['_GET']['pconf'] != "" && $this->_tpl_vars['active_modules']['Product_Configurator']): ?>
    <input type="hidden" name="slot" value="<?php echo $GLOBALS['_GET']['slot']; ?>
" />
    <input type="hidden" name="addproductid" value="<?php echo $this->_tpl_vars['product']['productid']; ?>
" />
    <div class="button-row">
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_pconf_add_to_configuration'],'href' => "javascript: if (FormValidation()) ".($this->_tpl_vars['ldelim'])."document.orderform.productid.value='".($GLOBALS['_GET']['pconf'])."'; document.orderform.action='pconf.php'; document.orderform.submit();".($this->_tpl_vars['rdelim']),'additional_button_class' => "light-button")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <?php if ($this->_tpl_vars['product']['appearance']['empty_stock']): ?>
      <p class="message">
        <strong><?php echo $this->_tpl_vars['lng']['lbl_note']; ?>
:</strong> <?php echo $this->_tpl_vars['lng']['lbl_pconf_slot_out_of_stock_note']; ?>

      </p>
    <?php endif; ?>
    <?php if ($this->_tpl_vars['product']['appearance']['min_quantity'] == $this->_tpl_vars['product']['appearance']['max_quantity']): ?>
      <p><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_add_to_configuration_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'items', $this->_tpl_vars['product']['appearance']['min_quantity']) : smarty_modifier_substitute($_tmp, 'items', $this->_tpl_vars['product']['appearance']['min_quantity'])); ?>
</p>
    <?php endif; ?>
  <?php endif;  endif; ?>
</form>
  <script type="text/javascript">
    //<![CDATA[
//    setTimeout(check_options, 200);
//    has_options = true;
check_options();
    //]]>
  </script>

<script type="text/javascript">
//<![CDATA[
<?php echo '
$(document).ready(function() {
        window.onload = check_wholesale($(\'#product_avail\').val());
});
'; ?>

//]]>
</script>


<?php if ($this->_tpl_vars['product_details_standalone']): ?>
  <?php echo smarty_function_load_defer_code(array('type' => 'css'), $this);?>

  <?php echo smarty_function_load_defer_code(array('type' => 'js'), $this);?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/product_details.tpl"), $this); endif; ?>