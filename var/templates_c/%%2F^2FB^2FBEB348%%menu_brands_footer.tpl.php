<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:02
         compiled from modules/Brands/menu_brands_footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Brands/menu_brands_footer.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "modules/Brands/menu_brands_footer.tpl","lbl_brands"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Brands/menu_brands_footer.tpl"), $this); endif;  if ($this->_tpl_vars['cidev_letters_arr'] != ''): ?>
<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td style="vertical-align: top;">
<span class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_brands']; ?>
: </span>

<?php $_from = $this->_tpl_vars['cidev_letters_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
&nbsp;<a href="/brands.php#<?php echo $this->_tpl_vars['item']; ?>
" class="NavigationPath"><?php echo $this->_tpl_vars['item']; ?>
</a>
<?php endforeach; endif; unset($_from); ?>
</td>

<?php if ($this->_tpl_vars['main'] == 'product'): ?>
<td align="right" style="padding-right: 25px;">

<a href="/brands.php?brandid=<?php echo $this->_tpl_vars['product']['brandid']; ?>
" class="NavigationPath">All <?php echo $this->_tpl_vars['brandid_brands_info'][$this->_tpl_vars['product']['brandid']]['brand']; ?>
 products</a>


</td>
<?php endif; ?>

</tr>
</table>
<?php endif; ?>



<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Brands/menu_brands_footer.tpl"), $this); endif; ?>