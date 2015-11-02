<?php /* Smarty version 2.6.12, created on 2011-10-11 07:04:01
         compiled from modules/XML_Sitemap/config.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'modules/XML_Sitemap/config.tpl', 20, false),array('modifier', 'substitute', 'modules/XML_Sitemap/config.tpl', 23, false),array('modifier', 'strip_tags', 'modules/XML_Sitemap/config.tpl', 25, false),array('modifier', 'truncate', 'modules/XML_Sitemap/config.tpl', 45, false),)), $this); ?>
<?php func_load_lang($this, "modules/XML_Sitemap/config.tpl","xmlmap_generate_section,xmlmap_generate_note,lbl_go,txt_domain_specific_options,xmlmap_extraurls_section,lbl_page_url,lbl_delete_selected,xmlmap_addurl_section,lbl_page_url,lbl_add,lbl_xml_sitemap_generation"); ?><br />
<?php ob_start();  if ($this->_tpl_vars['option'] != 'Multiple_Storefronts'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['xmlmap_generate_section'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<form name="xmlmap_generate" method="post" action="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
<input type="hidden" name="xmlmap[config]" value="generate" />
<?php $this->assign('xseo_xmlmap_url', ($this->_tpl_vars['xmlmap_location'])."/sitemap.xml");  echo ((is_array($_tmp=$this->_tpl_vars['lng']['xmlmap_generate_note'])) ? $this->_run_mod_handler('substitute', true, $_tmp, 'url', $this->_tpl_vars['xseo_xmlmap_url']) : smarty_modifier_substitute($_tmp, 'url', $this->_tpl_vars['xseo_xmlmap_url'])); ?>

<br /><br />
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_go'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>
<br /><br />
<?php endif; ?>

<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && $this->_tpl_vars['option'] != 'Multiple_Storefronts' && $this->_tpl_vars['current_storefront'] != 0):  echo $this->_tpl_vars['lng']['txt_domain_specific_options']; ?>
<br /><br /><?php endif;  if ($this->_tpl_vars['xmlmap_extra'] != ''):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['xmlmap_extraurls_section'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<form name="xmlmap_delurls" method="post" action="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
<input type="hidden" name="xmlmap[config]" value="delurls" />
<table width="100%">
<tr class="TableHead">
<td>&nbsp;</td>
<td width="100%"><?php echo $this->_tpl_vars['lng']['lbl_page_url']; ?>
</td>
</tr>
<?php $_from = $this->_tpl_vars['xmlmap_extra']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['url']):
?>
<tr>
<td>
<input type="checkbox" value="<?php echo $this->_tpl_vars['url']['id']; ?>
" name="xmlmap[del_extra][]"<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && $this->_tpl_vars['option'] != 'Multiple_Storefronts' && $this->_tpl_vars['current_storefront'] != 0): ?> disabled="disabled"<?php endif; ?> /></td>
<td><a href="<?php echo $this->_tpl_vars['url']['url']; ?>
" target="_blank"><?php echo ((is_array($_tmp=$this->_tpl_vars['url']['url'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 55, "...") : smarty_modifier_truncate($_tmp, 55, "...")); ?>
</a></td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr>
<td>
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_delete_selected'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && $this->_tpl_vars['option'] != 'Multiple_Storefronts' && $this->_tpl_vars['current_storefront'] != 0): ?> disabled="disabled"<?php endif; ?> />
</td>
</tr>
</table>
</form>
<br /><br />
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['xmlmap_addurl_section'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<form name="xmlmap_addurl" method="post" action="<?php echo ((is_array($_tmp=$GLOBALS['HTTP_SERVER_VARS']['REQUEST_URI'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
<input type="hidden" name="xmlmap[config]" value="addurl" />
<table width="100%">
<tr class="TableHead">
<td width="100%"><?php echo $this->_tpl_vars['lng']['lbl_page_url']; ?>
</td>
</tr>
<tr>
<td>
<input style="width : 99%;" type="text" size="55" name="xmlmap[url]"<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && $this->_tpl_vars['option'] != 'Multiple_Storefronts' && $this->_tpl_vars['current_storefront'] != 0): ?> disabled="disabled"<?php endif; ?> />
</td>
</tr>
<tr>
<td>
<input type="submit" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_add'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts'] && $this->_tpl_vars['option'] != 'Multiple_Storefronts' && $this->_tpl_vars['current_storefront'] != 0): ?> disabled="disabled"<?php endif; ?> />
</td>
</tr>
</table>

</form>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_xml_sitemap_generation'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>