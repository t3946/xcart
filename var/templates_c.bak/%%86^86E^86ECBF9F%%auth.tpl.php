<?php /* Smarty version 2.6.12, created on 2011-10-11 05:43:20
         compiled from auth.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'auth.tpl', 34, false),array('modifier', 'amp', 'auth.tpl', 81, false),)), $this); ?>
<?php func_load_lang($this, "auth.tpl","lbl_username,lbl_password,lbl_login,lbl_register,lbl_recover_password,lbl_insecure_login,txt_javascript_disabled,txt_javascript_enabled,lbl_authentication"); ?><?php ob_start();  if ($this->_tpl_vars['config']['Security']['use_https_login'] == 'Y'):  $this->assign('form_url', $this->_tpl_vars['https_location']);  else:  $this->assign('form_url', $this->_tpl_vars['current_location']);  endif; ?>
<form action="<?php echo $this->_tpl_vars['form_url']; ?>
/include/login.php" method="post" name="authform">
<input type="hidden" name="<?php echo $this->_tpl_vars['XCARTSESSNAME']; ?>
" value="<?php echo $this->_tpl_vars['XCARTSESSID']; ?>
" />
<table cellpadding="0" cellspacing="0" width="100%">
<?php if ($this->_tpl_vars['config']['Security']['use_secure_login_page'] == 'Y'): ?> <tr>
<td>
<?php $this->assign('slogin_url_add', "");  if ($this->_tpl_vars['usertype'] == 'C'):  $this->assign('slogin_url', $this->_tpl_vars['catalogs_secure']['customer']);  if ($this->_tpl_vars['catalogs_secure']['customer'] != $this->_tpl_vars['catalogs']['customer']):  $this->assign('slogin_url_add', "?".($this->_tpl_vars['XCARTSESSNAME'])."=".($this->_tpl_vars['XCARTSESSID']));  endif;  elseif ($this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] == 'Y' || $this->_tpl_vars['usertype'] == 'A'):  $this->assign('slogin_url', $this->_tpl_vars['catalogs_secure']['admin']);  elseif ($this->_tpl_vars['usertype'] == 'P'):  $this->assign('slogin_url', $this->_tpl_vars['catalogs_secure']['provider']);  elseif ($this->_tpl_vars['usertype'] == 'B'):  $this->assign('slogin_url', $this->_tpl_vars['catalogs_secure']['partner']);  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/secure_login.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
<?php else: ?> <tr>
<td class="VertMenuItems">
<font class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_username']; ?>
:</font><br />
<input type="text" name="username" size="16" value="<?php echo ((is_array($_tmp=@$this->_config[0]['vars']['default_login'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['username']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['username'])); ?>
" /><br />
<font class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_password']; ?>
:</font><br />
<input type="password" name="password" size="16" maxlength="64" value="<?php echo $this->_config[0]['vars']['default_password']; ?>
" /><br />
<input type="hidden" name="mode" value="login" />
<?php if ($this->_tpl_vars['active_modules']['Simple_Mode'] != "" && $this->_tpl_vars['usertype'] != 'C' && $this->_tpl_vars['usertype'] != 'B'): ?>
<input type="hidden" name="usertype" value="P" />
<?php else: ?>
<input type="hidden" name="usertype" value="<?php echo $this->_tpl_vars['usertype']; ?>
" />
<?php endif; ?>
<input type="hidden" name="redirect" value="<?php echo $this->_tpl_vars['redirect']; ?>
" />
</td></tr>
<tr>
<td height="24" class="VertMenuItems">
<a href="javascript: document.authform.submit();" class="VertMenuItems"><b><?php echo $this->_tpl_vars['lng']['lbl_login']; ?>
</b></a>
</td>
</tr>
<?php endif; ?> <?php if ($this->_tpl_vars['usertype'] == 'C' || ( $this->_tpl_vars['usertype'] == 'B' && $this->_tpl_vars['config']['XAffiliate']['partner_register'] == 'Y' )): ?>
<tr>
<td height="24" nowrap="nowrap" class="VertMenuItems">
<a href="register.php" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_register']; ?>
</a>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['login'] == ""): ?>
<tr>
<td height="24" nowrap="nowrap" class="VertMenuItems"><a href="help.php?section=Password_Recovery" class="VertMenuItems"><?php echo $this->_tpl_vars['lng']['lbl_recover_password']; ?>
</a></td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'P' && $this->_tpl_vars['active_modules']['Simple_Mode'] == 'Y' || $this->_tpl_vars['usertype'] == 'A'): ?>
<!-- insecure login form link -->
<tr>
<td class="VertMenuItems">
<br />
<div align="left"><a href="insecure_login.php" class="SmallNote"><?php echo $this->_tpl_vars['lng']['lbl_insecure_login']; ?>
</a></div>
</td>
</tr>
<!-- insecure login form link -->
<?php endif;  if ($this->_tpl_vars['usertype'] == 'C'): ?>
<tr>
<td class="VertMenuItems" align="right">
<br />
<?php if ($this->_tpl_vars['js_enabled']): ?>
<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['js_update_link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" class="SmallNote"><?php echo $this->_tpl_vars['lng']['txt_javascript_disabled']; ?>
</a>
<?php else: ?>
<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['js_update_link'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" class="SmallNote"><?php echo $this->_tpl_vars['lng']['txt_javascript_enabled']; ?>
</a>
<?php endif; ?>
</td>
</tr>
<?php endif; ?>
</table>
</form>
<?php $this->_smarty_vars['capture']['menu'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.tpl", 'smarty_include_vars' => array('dingbats' => "dingbats_authentification.gif",'menu_title' => $this->_tpl_vars['lng']['lbl_authentication'],'menu_content' => $this->_smarty_vars['capture']['menu'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
