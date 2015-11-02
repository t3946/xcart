<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:52
         compiled from customer/main/similar_products.tpl */ ?>
<?php func_load_lang($this, "customer/main/similar_products.tpl","lbl_other,lbl_similar_products"); ?><?php ob_start(); ?>
	<ul class="PRItems no_marker">
		<li>::&nbsp;<a href="home.php?cat=<?php echo $this->_tpl_vars['current_category']['categoryid']; ?>
&amp;path=alt" title="" class="VertMenuItems"><font size="2"><?php echo $this->_tpl_vars['lng']['lbl_other']; ?>
 <?php echo $this->_tpl_vars['current_category']['category']; ?>
</font></a></li>
	</ul>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_similar_products'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%" class="recommends no_padding_bottom"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>