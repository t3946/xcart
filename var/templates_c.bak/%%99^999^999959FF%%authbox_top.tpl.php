<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:57
         compiled from authbox_top.tpl */ ?>
<?php func_load_lang($this, "authbox_top.tpl","txt_logged_in,lbl_logoff"); ?><form action="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/include/login.php" method="post" name="loginform">
<div class="AuthText"><?php echo $this->_tpl_vars['login']; ?>
 <?php echo $this->_tpl_vars['lng']['txt_logged_in']; ?>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "buttons/button.tpl", 'smarty_include_vars' => array('button_title' => $this->_tpl_vars['lng']['lbl_logoff'],'href' => "javascript: document.loginform.submit();",'js_to_href' => 'Y','type' => 'input')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<input type="hidden" name="mode" value="logout" />
<input type="hidden" name="redirect" value="<?php echo $this->_tpl_vars['redirect']; ?>
" />
</form>