<?php /* Smarty version 2.6.12, created on 2011-10-11 06:23:27
         compiled from customer/main/payment_wait.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'customer/main/payment_wait.tpl', 3, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/payment_wait.tpl","msg_order_is_being_placed,msg_order_is_being_placed"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<html>
<head>
<title><?php echo $this->_tpl_vars['lng']['msg_order_is_being_placed']; ?>
</title>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/<?php echo $this->_config[0]['vars']['CSSFile']; ?>
" />
</head>
<body>
<table cellpadding="0" cellspacing="0" align="center" class="Container" width="100%">
<tr>
	<td class="LCSBackground" height="30">&nbsp;</td>
</tr>
<tr>
	<td height="1"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr>
	<td class="LCSBackground" height="1"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<tr>
	<td style="padding-left: 30px; padding-top: 10px; height: 90%;">

<table cellspacing="1" cellpadding="2" width="100%" style="height: 100%;">
<tr>
	<td valign="top"><h1><?php echo $this->_tpl_vars['lng']['msg_order_is_being_placed']; ?>
</h1></td>
</tr>
<tr>
	<td valign="top" height="95%">
