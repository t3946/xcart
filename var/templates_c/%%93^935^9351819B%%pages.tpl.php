<?php /* Smarty version 2.6.12, created on 2015-11-02 03:07:18
         compiled from customer/main/pages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/pages.tpl', 1, false),array('function', 'eval', 'customer/main/pages.tpl', 6, false),array('modifier', 'replace', 'customer/main/pages.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/pages.tpl"), $this); endif; ?><br>
<?php ob_start();  if ($this->_tpl_vars['page_content'] != ''):  if ($this->_tpl_vars['config']['General']['parse_smarty_tags'] == 'Y'):  echo smarty_function_eval(array('var' => $this->_tpl_vars['page_content']), $this);?>

<?php else: ?>
<span class="SPItems-description"><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['page_content'])) ? $this->_run_mod_handler('replace', true, $_tmp, "<linktous-text-sample>", ($this->_tpl_vars['linktous_text_sample'])) : smarty_modifier_replace($_tmp, "<linktous-text-sample>", ($this->_tpl_vars['linktous_text_sample']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "<linktous-banner-sample>", ($this->_tpl_vars['linktous_banner_sample'])) : smarty_modifier_replace($_tmp, "<linktous-banner-sample>", ($this->_tpl_vars['linktous_banner_sample']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "<linktous-text-code>", ($this->_tpl_vars['linktous_text_code'])) : smarty_modifier_replace($_tmp, "<linktous-text-code>", ($this->_tpl_vars['linktous_text_code']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "<linktous-banner-code>", ($this->_tpl_vars['linktous_banner_code'])) : smarty_modifier_replace($_tmp, "<linktous-banner-code>", ($this->_tpl_vars['linktous_banner_code']))); ?>
</span>
<?php endif;  endif;  $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['page_data']['title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','use_h1' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/pages.tpl"), $this); endif; ?>