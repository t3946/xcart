<?php /* Smarty version 2.6.12, created on 2011-10-11 06:16:25
         compiled from main/edit_image.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'main/edit_image.tpl', 8, false),array('modifier', 'escape', 'main/edit_image.tpl', 8, false),array('modifier', 'replace', 'main/edit_image.tpl', 15, false),array('modifier', 'substitute', 'main/edit_image.tpl', 28, false),)), $this); ?>
<?php func_load_lang($this, "main/edit_image.tpl","lbl_plus,lbl_change_image,lbl_delete_image,lbl_reset,lbl_submit,txt_image_note"); ?><?php if ($this->_tpl_vars['idtag'] == ''):  $this->assign('idtag', 'edit_image');  endif; ?>
<img id="<?php echo $this->_tpl_vars['idtag']; ?>
" src="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?type=<?php echo $this->_tpl_vars['type']; ?>
&amp;id=<?php echo $this->_tpl_vars['id']; ?>
&amp;ts=<?php echo time();  if ($this->_tpl_vars['already_loaded']): ?>&amp;tmp=Y<?php endif; ?>"<?php if ($this->_tpl_vars['image_x'] != 0): ?> width="<?php echo $this->_tpl_vars['image_x']; ?>
"<?php endif;  if ($this->_tpl_vars['image_y'] != 0): ?> height="<?php echo $this->_tpl_vars['image_y']; ?>
"<?php endif; ?> alt="<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/image_property.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>"/>
<table  cellpadding="0" cellspacing="0">
<tr>
	<td id="<?php echo $this->_tpl_vars['idtag']; ?>
_btns_td">
        <?php if ($this->_tpl_vars['source'] == 'PD'): ?>
            <input type="button" id="<?php echo $this->_tpl_vars['type']; ?>
_<?php echo $this->_tpl_vars['idtag']; ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_plus'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick='javascript: popup_image_selection("<?php echo $this->_tpl_vars['type']; ?>
", "<?php echo $this->_tpl_vars['id']; ?>
", "<?php echo $this->_tpl_vars['idtag']; ?>
");' />
            <input type="file" size="25" name="<?php echo $this->_tpl_vars['idtag']; ?>
" onchange="javascript: $('#<?php echo $this->_tpl_vars['type']; ?>
_<?php echo $this->_tpl_vars['idtag']; ?>
').attr('disabled', 'disabled');" />
        <?php else: ?>
            <input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_change_image'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick='javascript: popup_image_selection("<?php echo $this->_tpl_vars['type']; ?>
", "<?php echo $this->_tpl_vars['id']; ?>
", "<?php echo $this->_tpl_vars['idtag']; ?>
");' />
        <?php endif;  if ($this->_tpl_vars['id'] != '' && $this->_tpl_vars['no_delete'] == ''): ?>
&nbsp;&nbsp;
<input id="<?php echo $this->_tpl_vars['idtag']; ?>
_delete" type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_image'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: <?php if ($this->_tpl_vars['delete_js'] != ''):  echo ((is_array($_tmp=$this->_tpl_vars['delete_js'])) ? $this->_run_mod_handler('replace', true, $_tmp, '"', '\"') : smarty_modifier_replace($_tmp, '"', '\"'));  else: ?>self.location='<?php echo $this->_tpl_vars['delete_url']; ?>
';<?php endif; ?>" />
<?php endif;  if ($this->_tpl_vars['type'] != 'T' && $this->_tpl_vars['type'] != 'P'): ?>
<span style="<?php if (! $this->_tpl_vars['already_loaded']): ?>display: none; <?php endif; ?>padding-left: 10px;" id="<?php echo $this->_tpl_vars['idtag']; ?>
_reset">
<input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_reset'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: popup_image_selection_reset('<?php echo $this->_tpl_vars['type']; ?>
', '<?php echo $this->_tpl_vars['id']; ?>
', '<?php echo $this->_tpl_vars['idtag']; ?>
');" />
<input id="skip_image_<?php echo $this->_tpl_vars['type']; ?>
" type="hidden" name="skip_image[<?php echo $this->_tpl_vars['type']; ?>
]" value="" />
</span>
<?php endif; ?>
	</td>
</tr>
<?php if ($this->_tpl_vars['button_name'] != 'no_button'): ?>
<tr style="display: none;" id="<?php echo $this->_tpl_vars['idtag']; ?>
_text">
<?php if ($this->_tpl_vars['button_name'] == ''):  $this->assign('button_name', $this->_tpl_vars['lng']['lbl_submit']);  endif; ?>
	<td style="padding-top: 10px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_image_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'button_name', $this->_tpl_vars['button_name']) : smarty_modifier_substitute($_tmp, 'button_name', $this->_tpl_vars['button_name'])); ?>
</td>
</tr> 
<?php endif; ?>
<tr style="display: none;" id="err_size_text_<?php echo $this->_tpl_vars['type']; ?>
_<?php echo $this->_tpl_vars['id']; ?>
">
	<td class="ErrorMessage" style="padding-top: 10px;" id="err_size_text_<?php echo $this->_tpl_vars['type']; ?>
_td_<?php echo $this->_tpl_vars['id']; ?>
">&nbsp;</td>
</tr> 
</table>