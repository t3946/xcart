<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:51
         compiled from help.tpl */ ?>
<?php func_load_lang($this, "help.tpl","lbl_contact_us,lbl_help"); ?><?php ob_start(); ?>
<a href="help.php?section=contactus&amp;mode=update" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_contact_us']; ?>
</a><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pages_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_help.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_help'],'menu_content' => $this->_smarty_vars['capture']['menu'],'link_href' => "help.php")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>