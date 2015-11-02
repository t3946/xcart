<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from admin/menu_admin.tpl */ ?>
<?php func_load_lang($this, "admin/menu_admin.tpl","lbl_summary,lbl_sf,lbl_db_backup_restore,lbl_import_export,lbl_sf,lbl_membership_levels,lbl_credit_card_types,lbl_titles,lbl_edit_templates,lbl_files,lbl_sf,lbl_general_settings,lbl_sf,lbl_images_location,lbl_languages,lbl_webmaster_mode,lbl_modules,lbl_payment_methods,lbl_patch_upgrade,lbl_html_catalog,lbl_static_pages,lbl_info_pages,lbl_sf,lbl_speed_bar,lbl_sf,lbl_multiple_storefronts,lbl_administration"); ?><?php ob_start(); ?>
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/general.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_summary'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/db_backup.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_db_backup_restore']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/import.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_import_export'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/memberships.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_membership_levels']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/card_types.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_credit_card_types']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/titles.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_titles']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/file_edit.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_edit_templates']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/file_manage.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_files'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/configuration.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_general_settings'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/images_location.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_images_location']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/languages.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_languages']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/editor_mode.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_webmaster_mode']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/modules.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_modules']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/payment_methods.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_payment_methods']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/patch.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_patch_upgrade']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/html_catalog.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_html_catalog']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/pages.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_static_pages']; ?>
</a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/categories.php?mode=info" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_info_pages'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/speed_bar.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_speed_bar'];  if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?> <?php echo $this->_tpl_vars['lng']['lbl_sf'];  endif; ?></a><br />
<?php if ($this->_tpl_vars['active_modules']['Multiple_Storefronts']): ?>
	<a href="<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
/multiple_storefronts.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_multiple_storefronts']; ?>
</a><br />
<?php endif;  $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_categorie.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_administration'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>