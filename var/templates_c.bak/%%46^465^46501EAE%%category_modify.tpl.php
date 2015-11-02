<?php /* Smarty version 2.6.12, created on 2011-10-11 07:02:02
         compiled from admin/main/category_modify.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'admin/main/category_modify.tpl', 53, false),array('modifier', 'escape', 'admin/main/category_modify.tpl', 96, false),array('modifier', 'strip_tags', 'admin/main/category_modify.tpl', 152, false),)), $this); ?>
<?php func_load_lang($this, "admin/main/category_modify.tpl","lbl_add_category,lbl_modify_category,lbl_current_category,lbl_root_level,lbl_root_level,txt_category_disabled,lbl_category_icon,lbl_save,lbl_position,lbl_category,lbl_category_already_exists,lbl_category_wrong_value,lbl_description,lbl_membership,lbl_availability,lbl_enabled,lbl_disabled,lbl_save,lbl_category_location_title,lbl_main_parent_category,lbl_root_level,lbl_main_parent_category_id,lbl_additional_parent_categories,lbl_additional_parent_categories_ids,lbl_update"); ?><script type="text/javascript" language="JavaScript 1.2">
<!--
window.name = "catmodwin";

<?php echo '
function updateCategoryIds() {
	var elm = document.getElementById(\'additional_parent_select\');
	if (elm) {
		txt = \'\';
		for (var i=0; i < elm.options.length; i++) {
			if (elm.options[i].selected) {
				if (txt) {
					txt = txt + \',\';
				}
				txt = txt + elm.options[i].value;
			}
		}
	}
	output = document.getElementById(\'additional_parent_input\');
	if (output) {
		output.value = txt;
	}
}
'; ?>

-->
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "main/popup_image_selection.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['active_modules']['HTML_Editor']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/HTML_Editor/editor.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['section'] != 'lng'): ?>

<?php if ($this->_tpl_vars['mode'] == 'add'):  $this->assign('title', $this->_tpl_vars['lng']['lbl_add_category']);  else:  $this->assign('title', $this->_tpl_vars['lng']['lbl_modify_category']);  endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "page_title.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_tools.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<br />

<?php ob_start();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/location.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<table width="100%">

<tr>
	<td align="center" class="TopLabel"><?php if ($this->_tpl_vars['current_category']['avail'] != 'N'): ?><a href="<?php echo $this->_tpl_vars['current_category']['customer_url']; ?>
" title="" target="_blank"><?php endif;  echo $this->_tpl_vars['lng']['lbl_current_category']; ?>
: "<?php echo ((is_array($_tmp=@$this->_tpl_vars['current_category']['category'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['lng']['lbl_root_level']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['lng']['lbl_root_level'])); ?>
"
<?php if ($this->_tpl_vars['current_category']['avail'] == 'N'): ?>
<div class="ErrorMessage"><?php echo $this->_tpl_vars['lng']['txt_category_disabled']; ?>
</div>
<?php else: ?>
</a>
<?php endif; ?>
	</td>
</tr>

</table>

<br /><br />

<form name="addform" action="category_modify.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="mode" value="<?php echo $this->_tpl_vars['mode']; ?>
" />
<?php if ($this->_tpl_vars['mode'] == 'add'): ?>
<input type="hidden" name="parent" value="<?php echo $this->_tpl_vars['cat']; ?>
" />
<?php else: ?>
<input type="hidden" name="cat" value="<?php echo $this->_tpl_vars['cat']; ?>
" />
<?php endif; ?>

<table cellpadding="3" cellspacing="1" width="100%">

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_category_icon']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/edit_image.tpl", 'smarty_include_vars' => array('type' => 'C','id' => $this->_tpl_vars['cat'],'delete_url' => "category_modify.php?mode=delete_icon&amp;cat=".($this->_tpl_vars['cat']),'button_name' => $this->_tpl_vars['lng']['lbl_save'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_position']; ?>
:</td>
	<td width="10" height="10">&nbsp;</td>
	<td height="10">
<input type="text" name="order_by" size="5" value="<?php if ($this->_tpl_vars['category_error'] != ""):  echo $GLOBALS['HTTP_POST_VARS']['order_by'];  elseif ($this->_tpl_vars['mode'] != 'add'):  echo $this->_tpl_vars['current_category']['order_by'];  endif; ?>" />
</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_category']; ?>
:</td>
	<td width="10" height="10"><font class="CustomerMessage">*</font></td>
	<td height="10">
<input type="text" name="category_name" id="category_name" maxlength="255" size="94" value="<?php if ($this->_tpl_vars['category_error'] != ""):  echo ((is_array($_tmp=$GLOBALS['HTTP_POST_VARS']['category_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  elseif ($this->_tpl_vars['mode'] != 'add'):  echo ((is_array($_tmp=$this->_tpl_vars['current_category']['category'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?>" />
<?php if ($this->_tpl_vars['category_error'] != ""):  if ($this->_tpl_vars['category_error'] == '2'): ?>
<font color="red">&lt;&lt; <?php echo $this->_tpl_vars['lng']['lbl_category_already_exists']; ?>
</font>
<?php else: ?>
<font color="red">&lt;&lt; <?php echo $this->_tpl_vars['lng']['lbl_category_wrong_value']; ?>
</font>
<?php endif;  endif; ?>
&nbsp;<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "capitalize_js.tpl", 'smarty_include_vars' => array('id' => 'category_name')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap" valign="top"><?php echo $this->_tpl_vars['lng']['lbl_description']; ?>
:</td>
	<td width="10" height="10"><font class="CustomerMessage"></font></td>
	<td height="10">
<?php if ($this->_tpl_vars['category_error'] != ""):  $this->assign('data', $GLOBALS['HTTP_POST_VARS']['description']);  elseif ($this->_tpl_vars['mode'] != 'add'):  $this->assign('data', $this->_tpl_vars['current_category']['description']);  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/textarea.tpl", 'smarty_include_vars' => array('name' => 'description','cols' => 65,'rows' => 15)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_membership']; ?>
:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/membership_selector.tpl", 'smarty_include_vars' => array('data' => $this->_tpl_vars['current_category'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_availability']; ?>
:</td>
	<td width="10" height="10"><font class="CustomerMessage"></font></td>
	<td height="10">
<select name="avail">
	<option value='Y' <?php if (( $this->_tpl_vars['current_category']['avail'] == 'Y' )): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_enabled']; ?>
</option>
	<option value='N' <?php if (( $this->_tpl_vars['current_category']['avail'] == 'N' )): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_disabled']; ?>
</option>
</select>
	</td>
</tr>
<tr>
	<td colspan="2" class="FormButton">&nbsp;</td>
	<td class="SubmitBox"><input type="submit" value=" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_save'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 " /></td>
</tr>

<?php if ($this->_tpl_vars['mode'] != 'add'): ?>

<tr>
	<td colspan="3"><br /><br /><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_category_location_title'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_main_parent_category']; ?>
:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10">
<select name="cat_location_text" style="width: 80%;" onchange="javascript: document.getElementById('main_parent_input').value=this.options[this.selectedIndex].value;">
	<option value="0"><?php echo $this->_tpl_vars['lng']['lbl_root_level']; ?>
</option>
<?php $_from = $this->_tpl_vars['allcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['moving_enabled'] && $this->_tpl_vars['catid'] == $this->_tpl_vars['current_category']['parentid']): ?>
	<option value="<?php echo $this->_tpl_vars['catid']; ?>
"<?php if ($this->_tpl_vars['catid'] == $this->_tpl_vars['current_category']['parentid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['c']['category_path']; ?>
</option>
<?php endif;  endforeach; endif; unset($_from); ?>
</select>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_main_parent_category_id']; ?>
:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10"><input type="text" name="cat_location" id="main_parent_input" value="<?php echo $this->_tpl_vars['current_category']['parentid']; ?>
" /></td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_additional_parent_categories']; ?>
:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10">
<select name="additional_cat_location_text" id="additional_parent_select" multiple="multiple" size="8" style="width: 80%;" onchange="javascript: updateCategoryIds();">
<?php $_from = $this->_tpl_vars['allcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 if ($this->_tpl_vars['c']['moving_enabled'] && $this->_tpl_vars['c']['additional_parent_selected']): ?>
	<option value="<?php echo $this->_tpl_vars['catid']; ?>
" selected="selected"><?php echo $this->_tpl_vars['c']['category_path']; ?>
</option>
<?php endif;  endforeach; endif; unset($_from); ?>
</select>
	</td>
</tr>

<tr>
	<td height="10" class="FormButton" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_additional_parent_categories_ids']; ?>
:</td>
	<td width="10" height="10"><font class="FormButtonOrange"></font></td>
	<td height="10"><input type="text" name="additional_cat_location" id="additional_parent_input" style="width: 80%;" value="<?php echo '';  $this->assign('need_comma', false);  echo '';  $_from = $this->_tpl_vars['allcategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['catid'] => $this->_tpl_vars['c']):
 echo '';  if ($this->_tpl_vars['c']['moving_enabled'] && $this->_tpl_vars['c']['additional_parent_selected']):  echo '';  if ($this->_tpl_vars['need_comma']):  echo ',';  else:  echo '';  $this->assign('need_comma', true);  echo '';  endif;  echo '';  echo $this->_tpl_vars['c']['categoryid'];  echo '';  endif;  echo '';  endforeach; endif; unset($_from);  echo ''; ?>
" /></td>
</tr>
<tr>
	<td colspan="2" class="FormButton">&nbsp;</td>
	<td class="SubmitBox"><input type="button" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_update'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: submitForm(this, 'move');" /></td>
</tr>

<?php endif; ?>

</table>
</form>

<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['title'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php elseif ($this->_tpl_vars['section'] == 'lng' && $this->_tpl_vars['mode'] != 'add' && $this->_tpl_vars['cat'] > 0): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "admin/main/category_lng.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>
