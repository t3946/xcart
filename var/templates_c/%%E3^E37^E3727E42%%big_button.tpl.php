<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:13
         compiled from modules/Fast_Lane_Checkout/big_button.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/big_button.tpl', 1, false),array('modifier', 'regex_replace', 'modules/Fast_Lane_Checkout/big_button.tpl', 4, false),array('modifier', 'escape', 'modules/Fast_Lane_Checkout/big_button.tpl', 57, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/big_button.tpl"), $this); endif;  if ($this->_tpl_vars['config']['Adaptives']['platform'] == 'MacPPC' && $this->_tpl_vars['config']['Adaptives']['browser'] == 'NN'):  $this->assign('js_to_href', 'Y');  endif;  if ($this->_tpl_vars['type'] == 'input'):  $this->assign('img_type', 'INPUT type="image"');  else:  $this->assign('img_type', 'IMG');  endif;  $this->assign('js_link', ((is_array($_tmp=$this->_tpl_vars['href'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^\s*javascript\s*:/Si", "") : smarty_modifier_regex_replace($_tmp, "/^\s*javascript\s*:/Si", "")));  if ($this->_tpl_vars['js_link'] == $this->_tpl_vars['href']): ?>
 <?php if ($this->_tpl_vars['js_onclick_to_href'] != ""):  $this->assign('js_link', "javascript: ".($this->_tpl_vars['js_onclick_to_href'])." self.location='".($this->_tpl_vars['href'])."';");  $this->assign('onclick', "javascript: ".($this->_tpl_vars['js_onclick_to_href'])." self.location='".($this->_tpl_vars['href'])."';"); ?>
 <?php else:  $this->assign('js_link', "javascript: self.location='".($this->_tpl_vars['href'])."'"); ?>
 <?php endif;  else:  $this->assign('js_link', $this->_tpl_vars['href']);  if ($this->_tpl_vars['js_to_href'] != 'Y'):  $this->assign('onclick', $this->_tpl_vars['href']);  $this->assign('href', "javascript: void(0);");  endif;  endif; ?>

<?php if ($this->_tpl_vars['config']['Adaptives']['platform'] != 'MacPPC' || $this->_tpl_vars['config']['Adaptives']['browser'] != 'NN'):  if ($this->_tpl_vars['color'] == 'red'):  $this->assign('bg_title_class', 'RedBackground');  $this->assign('sfx', '_r');  else:  $this->assign('bg_title_class', 'RedBackground');  $this->assign('sfx', '_r');  endif; ?>

<?php if ($this->_tpl_vars['button_title'] == 'Checkout'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('btn_to_checkout' => 'Y','js_link' => $this->_tpl_vars['js_link'],'button_type' => 'checkout','js_onclick_to_href' => $this->_tpl_vars['js_onclick_to_href'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else:  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('js_link' => $this->_tpl_vars['js_link'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php else: ?>
<a href="<?php echo $this->_tpl_vars['href']; ?>
"<?php if ($this->_tpl_vars['onclick'] != ''): ?> onclick="<?php echo $this->_tpl_vars['onclick']; ?>
"<?php endif;  if ($this->_tpl_vars['title'] != ''): ?> title="<?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif;  if ($this->_tpl_vars['target'] != ''): ?> target="<?php echo $this->_tpl_vars['target']; ?>
"<?php endif; ?>><font class="FormButton"><?php echo $this->_tpl_vars['button_title']; ?>
 <<?php echo $this->_tpl_vars['img_type']; ?>
 <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/go_image.tpl", 'smarty_include_vars' => array('full_url' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>></font></a>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/big_button.tpl"), $this); endif; ?>