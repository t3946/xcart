<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:29
         compiled from modules/Image_Verification/spambot_arrest.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Image_Verification/spambot_arrest.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "modules/Image_Verification/spambot_arrest.tpl","lbl_word_verification,lbl_type_the_characters,lbl_get_a_different_code,lbl_type_the_characters,lbl_get_a_different_code,lbl_type_the_characters,lbl_get_a_different_code"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Image_Verification/spambot_arrest.tpl"), $this); endif;  if (! $this->_tpl_vars['id']):  $this->assign('id', 'image');  endif;  if ($this->_tpl_vars['mode'] == 'advanced'): ?>
<tr>	
	<td colspan="3">

<?php if ($this->_tpl_vars['reg_id'] != ""): ?>     <div id="<?php echo $this->_tpl_vars['reg_id']; ?>
" <?php if ($this->_tpl_vars['antibot_err'] == "" && $this->_tpl_vars['show_code'] != 'Y'): ?>style="display:none;"<?php endif; ?>>
<?php endif; ?>
<table width="100%">
<tr>
	<td colspan="2" class="RegSectionTitle"><?php echo $this->_tpl_vars['lng']['lbl_word_verification']; ?>
<hr size="1" noshade="noshade" /></td>
</tr>
<tr>
	<td colspan="2"><?php echo $this->_tpl_vars['lng']['lbl_type_the_characters']; ?>
</td>
</tr>


<tr>
	<td nowrap="nowrap" 
<?php if ($this->_tpl_vars['active_modules']['Image_Verification'] && $this->_tpl_vars['show_antibot']['on_login'] == 'Y' && $this->_tpl_vars['login_antibot_on']): ?>
width="194" align="right" class="FormButton"
<?php else: ?>
align="left" width="10%"
<?php endif; ?>
>
<img src="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/antibot_image.php?section=<?php echo $this->_tpl_vars['id']; ?>
" id="<?php echo $this->_tpl_vars['id']; ?>
" alt="" /><br />
<?php if ($this->_tpl_vars['js_enabled'] == 'Y'): ?>
<a class="VertMenuItems" href="javascript: change_antibot_image('<?php echo $this->_tpl_vars['id']; ?>
');"><?php echo $this->_tpl_vars['lng']['lbl_get_a_different_code']; ?>
</a>&nbsp;&nbsp;&nbsp;
<?php endif; ?>
	</td>
	<td align="left">
<input type="text" name="antibot_input_str" />
<?php if ($this->_tpl_vars['antibot_err']): ?>
<font class="Star">&nbsp;&lt;&lt;</font>
<?php endif;  if ($this->_tpl_vars['is_flc']): ?>
<input type="hidden" name="login_antibot_on" value="1" />
<?php endif; ?>
	</td>
</tr>



</table>
<?php if ($this->_tpl_vars['reg_id'] != ""): ?>
    </div>
<?php endif; ?>
	</td>
</tr>

<?php elseif ($this->_tpl_vars['mode'] == 'simple'): ?>

<tr>
	<td colspan="3">

<br />

<table cellpadding="3" cellspacing="1">
<tr>
	<td colspan="2"><?php echo $this->_tpl_vars['lng']['lbl_type_the_characters']; ?>
:</td>
</tr>
<tr>
	<td  align="left" width="10%">
<img src="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/antibot_image.php?section=<?php echo $this->_tpl_vars['id']; ?>
" id="<?php echo $this->_tpl_vars['id']; ?>
"alt="" /><br />
<?php if ($this->_tpl_vars['js_enabled'] == 'Y'): ?>
<a href="javascript: change_antibot_image('<?php echo $this->_tpl_vars['id']; ?>
');"><?php echo $this->_tpl_vars['lng']['lbl_get_a_different_code']; ?>
</a>
<?php endif; ?>
	</td>
	<td align="left">
<input type="text" name="antibot_input_str" />
<?php if ($this->_tpl_vars['antibot_err']): ?>
<font class="Star">&nbsp;&lt;&lt;</font>
<?php endif; ?>
	</td>
</tr>
</table>

	</td>
</tr>
<?php elseif ($this->_tpl_vars['mode'] == 'simple_column'): ?>
<tr>
	<td colspan="3">

	<br />

<table cellpadding="3" cellspacing="1">
<tr>
	<td colspan="2"><?php echo $this->_tpl_vars['lng']['lbl_type_the_characters']; ?>
:</td>
</tr>
<tr>
	<td  align="left" width="10%" colspan="2">
	<img src="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/antibot_image.php?section=<?php echo $this->_tpl_vars['id']; ?>
" id="<?php echo $this->_tpl_vars['id']; ?>
"alt="" /><br />
	<?php if ($this->_tpl_vars['js_enabled'] == 'Y'): ?>
	<a href="javascript: change_antibot_image('<?php echo $this->_tpl_vars['id']; ?>
');"><?php echo $this->_tpl_vars['lng']['lbl_get_a_different_code']; ?>
</a>
	<?php endif; ?>
	</td>
</tr>	
<tr>	
	<td align="left" colspan="2">
	<input type="text" name="antibot_input_str" />
	<?php if ($this->_tpl_vars['antibot_err']): ?>
	<font class="Star">&nbsp;&lt;&lt;</font>
	<?php endif; ?>
	</td>
</tr>
</table>

</td>
</tr>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Image_Verification/spambot_arrest.tpl"), $this); endif; ?>