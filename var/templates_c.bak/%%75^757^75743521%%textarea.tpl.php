<?php /* Smarty version 2.6.12, created on 2011-10-11 06:16:25
         compiled from main/textarea.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'main/textarea.tpl', 3, false),array('modifier', 'escape', 'main/textarea.tpl', 68, false),array('modifier', 'default', 'main/textarea.tpl', 81, false),)), $this); ?>
<?php func_load_lang($this, "main/textarea.tpl","lbl_features,lbl_apply_features,lbl_remove_features,lbl_default_editor,lbl_default_editor,lbl_advanced_editor,lbl_advanced_editor"); ?><?php if ($this->_tpl_vars['active_modules']['HTML_Editor'] && ! $this->_tpl_vars['disabled']):  $this->assign('id', ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/[^\w\d_]/", "") : smarty_modifier_regex_replace($_tmp, "/[^\w\d_]/", ""))); ?>
<script type="text/javascript">
<!--
lbl_features = '<?php echo $this->_tpl_vars['lng']['lbl_features']; ?>
';
var abbrs = "<?php echo $this->_tpl_vars['abbreviations']; ?>
".split(',');
<?php echo '
	for (i = 0; i < abbrs.length; i++) {
		abbrs[i] = jQuery.trim(abbrs[i]);
	}

	function implode( glue, pieces ) {
		return ( ( pieces instanceof Array ) ? pieces.join ( glue ) : pieces );
	}

	function ignore_abbreviations(a,b) {
		if (b.length > 0 && jQuery.inArray(b, abbrs) == -1) {
				b = b + "<br />\\r\\n";
		}
		return b;
	}

	function features_makeup(id) {
		var text = $(\'#\' + id).val();
		var text_upper = text.toUpperCase();
		if (!text.match(/^\\s*<b>\\s*Features:\\s*<\\/b>\\s*<br\\s*[\\/]?>\\s*/i)) {
			// step 1: replace substrings like [word].
			var features = text.replace(/\\b([a-zA-Z_-]+[\\.\\?!])/g, ignore_abbreviations);
			// step 2: if there is no <br /> in the end of the string add it
			features = features.replace(/(.{1,6})\\s*$/gm, function(a,b) { if (b != \'<br />\') return b + "<br />"; else return a});
			// step 3: delete all spaces from the beggining of the strings
			features = features.replace(/^\\s*(\\S*)/gm, \'$1\');
			// step 4: add * in the beginning of every string
			features = features.replace(/^(.+)\\s*$/gm, \'* $1\');
			var newtext = "<b>" + lbl_features + ":</b><br />\\r\\n";
			newtext = newtext + features;
			$(\'#\' + id).val(newtext);
		}
	}

	function remove_features(id) {
		var text = $(\'#\' + id).val();
		if (text.match(/^\\s*<b>\\s*Features:\\s*<\\/b>\\s*<br\\s*[\\/]?>\\s*/i)) {
			// step 1: delete "<b>Features:</b><br />..."
			var features = text.replace(/^\\s*<b>\\s*Features:\\s*<\\/b>\\s*<br\\s*[\\/]?>\\s*/i, \'\');
			// step 2: delete "*" from the beginning of the string
			features = features.replace(/^\\*\\s*/gm,\'\');
			// step 3: delete <br /> from the end of the string
			features = features.replace(/<br\\s*[\\/]?>\\s*$/gmi,\'\');
			$(\'#\' + id).val(features);
		}
	}
'; ?>

-->
</script>
<div class="AELinkBox" style="width: 576px;">
<?php if ($this->_tpl_vars['name'] == 'fulldescr'): ?>
	<a href="javascript: void(0)" class="features" title="" onclick="javasctip: features_makeup('<?php echo $this->_tpl_vars['id']; ?>
');"><?php echo $this->_tpl_vars['lng']['lbl_apply_features']; ?>
</a>&nbsp;&nbsp;&nbsp;
	<a href="javascript: void(0)" title="" onclick="javasctip: remove_features('<?php echo $this->_tpl_vars['id']; ?>
');"><?php echo $this->_tpl_vars['lng']['lbl_remove_features']; ?>
</a>&nbsp;&nbsp;&nbsp;
<?php endif; ?>
<a href="javascript: void(0);" style="display: none;" id="<?php echo $this->_tpl_vars['id']; ?>
Dis" onclick="javascript: disableEditor('<?php echo $this->_tpl_vars['id']; ?>
','<?php echo $this->_tpl_vars['name']; ?>
', <?php echo $this->_tpl_vars['id']; ?>
Editor);"><?php echo $this->_tpl_vars['lng']['lbl_default_editor']; ?>
</a>
<b id="<?php echo $this->_tpl_vars['id']; ?>
DisB"><?php echo $this->_tpl_vars['lng']['lbl_default_editor']; ?>
</b>
&nbsp;&nbsp;
<a href="javascript: void(0);" id="<?php echo $this->_tpl_vars['id']; ?>
Enb" onclick="javascript: enableEditor('<?php echo $this->_tpl_vars['id']; ?>
','<?php echo $this->_tpl_vars['name']; ?>
', <?php echo $this->_tpl_vars['id']; ?>
Editor);"><?php echo $this->_tpl_vars['lng']['lbl_advanced_editor']; ?>
</a>
<b id="<?php echo $this->_tpl_vars['id']; ?>
EnbB" style="display: none;"><?php echo $this->_tpl_vars['lng']['lbl_advanced_editor']; ?>
</b>
</div>
<textarea id="<?php echo $this->_tpl_vars['id']; ?>
" name="<?php echo $this->_tpl_vars['name']; ?>
"<?php if ($this->_tpl_vars['cols']): ?> cols="<?php echo $this->_tpl_vars['cols']; ?>
"<?php endif;  if ($this->_tpl_vars['rows']): ?> rows="<?php echo $this->_tpl_vars['rows']; ?>
"<?php endif;  if ($this->_tpl_vars['class']): ?> class="<?php echo $this->_tpl_vars['class']; ?>
"<?php endif; ?> style="width: 576px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
<div id="<?php echo $this->_tpl_vars['id']; ?>
Box" style="width: 576px;">
<textarea id="<?php echo $this->_tpl_vars['id']; ?>
Adv"<?php if ($this->_tpl_vars['cols']): ?> cols="<?php echo $this->_tpl_vars['cols']; ?>
"<?php endif;  if ($this->_tpl_vars['rows']): ?> rows="<?php echo $this->_tpl_vars['rows']; ?>
"<?php endif;  if ($this->_tpl_vars['class']): ?> class="<?php echo $this->_tpl_vars['class']; ?>
"<?php endif; ?> style="width: 576px;"><?php echo ((is_array($_tmp=$this->_tpl_vars['data'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
<script type="text/javascript">
<!--

	if (isHTML_Editor) {
		if (!isHTML_EditorFF)
			document.getElementById('<?php echo $this->_tpl_vars['id']; ?>
Box').style.display = 'none';

		var <?php echo $this->_tpl_vars['id']; ?>
Editor = new InnovaEditor('<?php echo $this->_tpl_vars['id']; ?>
Editor');
		<?php echo $this->_tpl_vars['id']; ?>
Editor.width = 576;
		if (navigator.appName.indexOf('Microsoft')!=-1)
			<?php echo $this->_tpl_vars['id']; ?>
Editor.height = <?php echo ((is_array($_tmp=@$this->_tpl_vars['rows'])) ? $this->_run_mod_handler('default', true, $_tmp, 30) : smarty_modifier_default($_tmp, 30)); ?>
*13;
		else if (navigator.appName.indexOf('Netscape')!=-1)
			<?php echo $this->_tpl_vars['id']; ?>
Editor.height = <?php echo ((is_array($_tmp=@$this->_tpl_vars['rows'])) ? $this->_run_mod_handler('default', true, $_tmp, 30) : smarty_modifier_default($_tmp, 30)); ?>
*14;
		else
			<?php echo $this->_tpl_vars['id']; ?>
Editor.height = <?php echo ((is_array($_tmp=@$this->_tpl_vars['rows'])) ? $this->_run_mod_handler('default', true, $_tmp, 30) : smarty_modifier_default($_tmp, 30)); ?>
*12;

		<?php echo $this->_tpl_vars['id']; ?>
Editor.mode = '<?php echo ((is_array($_tmp=@$this->_tpl_vars['html_editor_mode'])) ? $this->_run_mod_handler('default', true, $_tmp, 'XHTMLBody') : smarty_modifier_default($_tmp, 'XHTMLBody')); ?>
';
		<?php echo $this->_tpl_vars['id']; ?>
Editor.REPLACE("<?php echo $this->_tpl_vars['id']; ?>
Adv");
		if (isHTML_EditorFF)
			document.getElementById('<?php echo $this->_tpl_vars['id']; ?>
Box').style.display = 'none';

		var reg = new RegExp("(;|^)<?php echo $this->_tpl_vars['id']; ?>
EditorEnabled=Y","");
		if (document.cookie.search(reg) != -1)
			document.getElementById('<?php echo $this->_tpl_vars['id']; ?>
Enb').onclick;

	} else
		document.getElementById('<?php echo $this->_tpl_vars['id']; ?>
Box').style.display = 'none';
-->
</script>
</div>
<?php else: ?>
<textarea id="<?php echo $this->_tpl_vars['id']; ?>
" name="<?php echo $this->_tpl_vars['name']; ?>
"<?php if ($this->_tpl_vars['cols']): ?> cols="<?php echo $this->_tpl_vars['cols']; ?>
"<?php endif;  if ($this->_tpl_vars['rows']): ?> rows="<?php echo $this->_tpl_vars['rows']; ?>
"<?php endif;  if ($this->_tpl_vars['class']): ?> class="<?php echo $this->_tpl_vars['class']; ?>
"<?php endif;  if ($this->_tpl_vars['style']): ?> style="<?php echo $this->_tpl_vars['style']; ?>
"<?php endif;  if ($this->_tpl_vars['disabled']): ?> disabled="disabled"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['data'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>
<?php endif; ?>