<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:18
         compiled from customer/main/welcome.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/welcome.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/welcome.tpl","lbl_new_arrivals,lbl_mobile_show_all_new_arrivals,lbl_on_sale,lbl_mobile_show_all_on_sale,lbl_bestsellers,lbl_featured_products,lbl_help"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/welcome.tpl"), $this); endif; ?>

<div class="welcome-page">

<?php if ($this->_tpl_vars['e_products_found'] == 'Y'): ?>

        <?php if ($this->_tpl_vars['current_storefront'] == '41'): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products_new_style.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php else: ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/navigation.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php else: ?>


  <?php if ($this->_tpl_vars['active_modules']['New_Arrivals'] && $this->_tpl_vars['config']['New_Arrivals']['new_arrivals_home'] == 'Y' && $this->_tpl_vars['xcart_mobile_config']['new_arrivals'] == 'Y'): ?>
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3><?php echo $this->_tpl_vars['lng']['lbl_new_arrivals']; ?>
</h3>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['new_arrivals'],'new_arrivals_show_date' => 'Y','is_new_arrivals_products' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_mobile_show_all_new_arrivals'],'href' => "new_arrivals.php",'data_theme' => 'a','style' => 'link')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['On_Sale'] && $this->_tpl_vars['config']['On_Sale']['on_sale_home'] == 'Y' && $this->_tpl_vars['xcart_mobile_config']['on_sale'] == 'Y'): ?>
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3><?php echo $this->_tpl_vars['lng']['lbl_on_sale']; ?>
</h3>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['on_sale_products'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_mobile_show_all_on_sale'],'href' => "on_sale.php",'data_theme' => 'a','style' => 'link')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['active_modules']['Bestsellers'] && $this->_tpl_vars['xcart_mobile_config']['bestsellers'] == 'Y' && $this->_tpl_vars['bestsellers']): ?>
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3><?php echo $this->_tpl_vars['lng']['lbl_bestsellers']; ?>
</h3>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['bestsellers'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
  <?php endif; ?>
  <?php if ($this->_tpl_vars['f_products'] && $this->_tpl_vars['xcart_mobile_config']['featured'] == 'Y'): ?>
    <div data-role="collapsible" data-theme="c" data-content-theme="c" data-inset="false">
      <h3 class="ui-collapsible-heading"><?php echo $this->_tpl_vars['lng']['lbl_featured_products']; ?>
</h3>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/main/products.tpl", 'smarty_include_vars' => array('products' => $this->_tpl_vars['f_products'],'featured' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
  <?php endif; ?>

<?php endif; ?>

</div>
<?php echo '
  <script type=text/javascript>
    //<![CDATA[
    $(function() {
      $(\'.welcome-page .ui-collapsible\').filter(\':first\').contents().each(function() {
        $(this)
                .removeClass(\'ui-collapsible-heading-collapsed\')
                .find(\'.ui-icon\')
                .removeClass(\'ui-icon-plus\')
                .addClass(\'ui-icon-minus\');
        $(this).removeClass(\'ui-collapsible-content-collapsed\');
      }).andSelf().removeClass(\'ui-collapsible-collapsed\');
    });
    //]]>
  </script>
'; ?>



<?php echo $this->_tpl_vars['config']['Company']['cidev_main_page_code']; ?>



<div style="margin: 9px 10px 0px 10px; padding: 8px; background-color: #EFEDDF;">

<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;" border="0">
<tr>
<td align="left" style="vertical-align: top;" colspan="4">
<span class="ProductPrice" style="font-size: 20px; font-weight: bold;"><?php echo $this->_tpl_vars['lng']['lbl_help']; ?>
</span>
</td>
</tr>

<tr>
<td>
        <table cellspacing="7" cellpadding="7" width="100%">
<?php unset($this->_sections['pg']);
$this->_sections['pg']['name'] = 'pg';
$this->_sections['pg']['loop'] = is_array($_loop=$this->_tpl_vars['pages_menu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pg']['show'] = true;
$this->_sections['pg']['max'] = $this->_sections['pg']['loop'];
$this->_sections['pg']['step'] = 1;
$this->_sections['pg']['start'] = $this->_sections['pg']['step'] > 0 ? 0 : $this->_sections['pg']['loop']-1;
if ($this->_sections['pg']['show']) {
    $this->_sections['pg']['total'] = $this->_sections['pg']['loop'];
    if ($this->_sections['pg']['total'] == 0)
        $this->_sections['pg']['show'] = false;
} else
    $this->_sections['pg']['total'] = 0;
if ($this->_sections['pg']['show']):

            for ($this->_sections['pg']['index'] = $this->_sections['pg']['start'], $this->_sections['pg']['iteration'] = 1;
                 $this->_sections['pg']['iteration'] <= $this->_sections['pg']['total'];
                 $this->_sections['pg']['index'] += $this->_sections['pg']['step'], $this->_sections['pg']['iteration']++):
$this->_sections['pg']['rownum'] = $this->_sections['pg']['iteration'];
$this->_sections['pg']['index_prev'] = $this->_sections['pg']['index'] - $this->_sections['pg']['step'];
$this->_sections['pg']['index_next'] = $this->_sections['pg']['index'] + $this->_sections['pg']['step'];
$this->_sections['pg']['first']      = ($this->_sections['pg']['iteration'] == 1);
$this->_sections['pg']['last']       = ($this->_sections['pg']['iteration'] == $this->_sections['pg']['total']);
?>
                <tr>
                        <td align="left" valign="top">

<?php if ($this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['new_link'] != ""): ?>
<a href="<?php echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['new_link']; ?>
" class="VertMenuItems" style="font-size: 18px;"><?php echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['title']; ?>
</a>
<?php else:  if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['pageid']): ?><a href="/pages.php?pageid=<?php echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['pageid']; ?>
" class="VertMenuItems" style="font-size: 18px;"><?php else: ?><font class="VertMenuItems" style="font-size: 16px;"><?php endif;  echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['title'];  if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['pageid']): ?></a><?php else: ?></font><?php endif;  endif; ?>
<br />

                        </td>
                </tr>
<?php endfor; endif; ?>
        </table>
</td>
</tr>
</table>

</div>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/welcome.tpl"), $this); endif; ?>