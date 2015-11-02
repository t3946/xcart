<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from admin/help.tpl */ ?>
<?php func_load_lang($this, "admin/help.tpl","lbl_xcart_faqs,lbl_community_forums,lbl_support_helpdesk,lbl_license_agreement,lbl_help"); ?><?php ob_start(); ?>
<a href="http://www.x-cart.com/faq.html" class="VertMenuItems" target="_blank"><?php echo $this->_tpl_vars['lng']['lbl_xcart_faqs']; ?>
</a><br />
<a href="http://forum.x-cart.com/" class="VertMenuItems" target="_blank"><?php echo $this->_tpl_vars['lng']['lbl_community_forums']; ?>
</a><br />
<a href="http://secure.qualiteam.biz/" class="VertMenuItems" target="_blank"><?php echo $this->_tpl_vars['lng']['lbl_support_helpdesk']; ?>
</a><br />
<a href="http://www.x-cart.com/software_license_agreement.html" class="VertMenuItems" target="_blank"><?php echo $this->_tpl_vars['lng']['lbl_license_agreement']; ?>
</a><br />
<?php $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_help.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_help'],'menu_content' => $this->_smarty_vars['capture']['menu'],'link_href' => "help.php")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>