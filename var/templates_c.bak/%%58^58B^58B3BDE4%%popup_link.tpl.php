<?php /* Smarty version 2.6.12, created on 2011-10-11 06:47:04
         compiled from modules/HTML_Editor/popup_link.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'modules/HTML_Editor/popup_link.tpl', 3, false),)), $this); ?>
<?php func_load_lang($this, "modules/HTML_Editor/popup_link.tpl","lbl_advanced_editor"); ?><div class="AELinkBox"<?php if ($this->_tpl_vars['width']): ?> style="width: <?php echo $this->_tpl_vars['width']; ?>
;"<?php endif; ?>>
<a href="javascript: void(0);" onclick="javascript: if (isHTML_Editor) window.open('<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/wysiwyg.php?id=<?php echo ((is_array($_tmp=$this->_tpl_vars['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
','WYSIWYG','width=600,height=400,toolbar=no,status=no,scrollbars=yes,resizable=yes,menubar=no,location=no,direction=no'); else if (window.txt_advanced_editor_warning) alert(txt_advanced_editor_warning);"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_advanced_editor'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
</div>
