<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:51
         compiled from customer/special.tpl */ ?>
<?php func_load_lang($this, "customer/special.tpl","lbl_special"); ?><?php if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != "" || $this->_tpl_vars['active_modules']['Gift_Registry'] != "" || $this->_tpl_vars['active_modules']['Special_Offers'] != "" || ( $this->_tpl_vars['active_modules']['Manufacturers'] != "" && $this->_tpl_vars['config']['Manufacturers']['manufacturers_menu'] != 'Y' ) || ( $this->_tpl_vars['active_modules']['Feature_Comparison'] != "" && $this->_tpl_vars['is_fc_display_menu'] == 'Y' )):  ob_start();  if ($this->_tpl_vars['active_modules']['Gift_Certificates'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Certificates/gc_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Gift_Registry'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Gift_Registry/giftreg_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Feature_Comparison'] != "" && $this->_tpl_vars['is_fc_display_menu'] == 'Y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Feature_Comparison/customer_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Survey'] && $this->_tpl_vars['surveys_is_avail']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Survey/menu_special.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['active_modules']['Special_Offers'] != ""):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Special_Offers/menu_special.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_categorie.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_special'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>