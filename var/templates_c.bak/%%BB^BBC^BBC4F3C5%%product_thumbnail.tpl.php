<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from product_thumbnail.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'product_thumbnail.tpl', 2, false),array('modifier', 'escape', 'product_thumbnail.tpl', 2, false),)), $this); ?>
<?php if ($this->_tpl_vars['config']['Appearance']['show_thumbnails'] == 'Y'): ?><img<?php if ($this->_tpl_vars['id'] != ''): ?> id="<?php echo $this->_tpl_vars['id']; ?>
"<?php endif; ?> src="<?php if ($this->_tpl_vars['tmbn_url']):  echo $this->_tpl_vars['tmbn_url'];  else:  if ($this->_tpl_vars['full_url']):  echo $this->_tpl_vars['http_location'];  else:  echo $this->_tpl_vars['xcart_web_dir'];  endif; ?>/image.php?type=<?php echo ((is_array($_tmp=@$this->_tpl_vars['type'])) ? $this->_run_mod_handler('default', true, $_tmp, 'T') : smarty_modifier_default($_tmp, 'T')); ?>
&amp;id=<?php echo $this->_tpl_vars['productid'];  endif; ?>"<?php if ($this->_tpl_vars['image_x'] != 0): ?> width="<?php echo $this->_tpl_vars['image_x']; ?>
"<?php endif;  if ($this->_tpl_vars['image_y'] != 0): ?> height="<?php echo $this->_tpl_vars['image_y']; ?>
"<?php endif; ?> alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['product'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><?php endif; ?>