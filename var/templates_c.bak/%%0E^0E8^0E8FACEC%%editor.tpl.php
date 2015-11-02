<?php /* Smarty version 2.6.12, created on 2011-10-11 06:16:25
         compiled from modules/HTML_Editor/editor.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'modules/HTML_Editor/editor.tpl', 70, false),)), $this); ?>
<?php func_load_lang($this, "modules/HTML_Editor/editor.tpl","txt_advanced_editor_warning"); ?><?php if ($this->_tpl_vars['config']['UA']['browser'] == 'MSIE'): ?>
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/bg.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/blank.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/brkspace.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnAbsolute.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnBackColor.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnBold.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnBookmark.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCenter.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnClean.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnContentBlock.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCopy.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustom1.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustom2.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustom3.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustom4.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustom5.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustom6.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustom7.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCustomObject.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnCut.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnDelete.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnFlash.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnForeColor.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnForm.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnFull.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnFullScreen.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnGuideline.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnHyperlink.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnImage.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnIndent.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnInternalImage.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnInternalLink.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnItalic.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnLTR.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnLeft.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnLine.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnList.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnMedia.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnNumber.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnOpenAsset.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnOutdent.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnPaste.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnPasteText.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnPasteWord.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnPreview.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnPrint.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnRTL.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnRedo.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnRemoveFormat.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnRight.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnSave.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnSearch.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnSource.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnSpellCheck.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnStrikethrough.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnStyle.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnStyleSelect.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnSubscript.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnSuperscript.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnSymbol.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnTable.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnTableEdit.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnUnderline.gif" alt="" width="0" height="0" style="display: none;" />
<img src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/HTML_Editor/scripts/icons/btnUndo.gif" alt="" width="0" height="0" style="display: none;" />
<?php endif; ?>
<script type="text/javascript">
<!--
var txt_advanced_editor_warning = "<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['txt_advanced_editor_warning'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'javascript') : smarty_modifier_escape($_tmp, 'javascript')); ?>
";
var isHTML_Editor = (
		(localBFamily == 'MSIE' && parseInt(localVersion) >= 5) || 
		(localBrowser == 'Netscape' && parseInt(localVersion) >= 8) || 
		((localBrowser == 'Firefox' || localBrowser == 'Mozilla' ) && parseInt(localVersion) >= 1) || 
		(localBrowser == 'Opera' && parseInt(localVersion) >= 9)
	) && localPlatform != 'MacPPC' && localPlatform != 'Mac';
var isHTML_EditorFF = (localBrowser == 'Firefox');
-->
</script>
<?php if ($this->_tpl_vars['shop_language'] == 'DE'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "modules/HTML_Editor/scripts/language/german/editor_lang.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "modules/HTML_Editor/scripts/innovaeditor.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>