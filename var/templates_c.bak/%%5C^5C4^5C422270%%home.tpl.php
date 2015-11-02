<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from modules/Fast_Lane_Checkout/home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'modules/Fast_Lane_Checkout/home.tpl', 3, false),array('modifier', 'escape', 'modules/Fast_Lane_Checkout/home.tpl', 9, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo smarty_function_config_load(array('file' => ($this->_tpl_vars['skin_config'])), $this);?>

<html>
<head>
<title>
<?php if ($this->_tpl_vars['config']['SEO']['page_title_format'] == 'A'): ?>
<?php unset($this->_sections['position']);
$this->_sections['position']['name'] = 'position';
$this->_sections['position']['loop'] = is_array($_loop=$this->_tpl_vars['location']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['position']['show'] = true;
$this->_sections['position']['max'] = $this->_sections['position']['loop'];
$this->_sections['position']['step'] = 1;
$this->_sections['position']['start'] = $this->_sections['position']['step'] > 0 ? 0 : $this->_sections['position']['loop']-1;
if ($this->_sections['position']['show']) {
    $this->_sections['position']['total'] = $this->_sections['position']['loop'];
    if ($this->_sections['position']['total'] == 0)
        $this->_sections['position']['show'] = false;
} else
    $this->_sections['position']['total'] = 0;
if ($this->_sections['position']['show']):

            for ($this->_sections['position']['index'] = $this->_sections['position']['start'], $this->_sections['position']['iteration'] = 1;
                 $this->_sections['position']['iteration'] <= $this->_sections['position']['total'];
                 $this->_sections['position']['index'] += $this->_sections['position']['step'], $this->_sections['position']['iteration']++):
$this->_sections['position']['rownum'] = $this->_sections['position']['iteration'];
$this->_sections['position']['index_prev'] = $this->_sections['position']['index'] - $this->_sections['position']['step'];
$this->_sections['position']['index_next'] = $this->_sections['position']['index'] + $this->_sections['position']['step'];
$this->_sections['position']['first']      = ($this->_sections['position']['iteration'] == 1);
$this->_sections['position']['last']       = ($this->_sections['position']['iteration'] == $this->_sections['position']['total']);
?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['location'][$this->_sections['position']['index']]['0'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php if (! $this->_sections['position']['last']): ?> :: <?php endif; ?>
<?php endfor; endif; ?>
<?php else: ?>
<?php unset($this->_sections['position']);
$this->_sections['position']['name'] = 'position';
$this->_sections['position']['loop'] = is_array($_loop=$this->_tpl_vars['location']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['position']['step'] = ((int)-1) == 0 ? 1 : (int)-1;
$this->_sections['position']['show'] = true;
$this->_sections['position']['max'] = $this->_sections['position']['loop'];
$this->_sections['position']['start'] = $this->_sections['position']['step'] > 0 ? 0 : $this->_sections['position']['loop']-1;
if ($this->_sections['position']['show']) {
    $this->_sections['position']['total'] = min(ceil(($this->_sections['position']['step'] > 0 ? $this->_sections['position']['loop'] - $this->_sections['position']['start'] : $this->_sections['position']['start']+1)/abs($this->_sections['position']['step'])), $this->_sections['position']['max']);
    if ($this->_sections['position']['total'] == 0)
        $this->_sections['position']['show'] = false;
} else
    $this->_sections['position']['total'] = 0;
if ($this->_sections['position']['show']):

            for ($this->_sections['position']['index'] = $this->_sections['position']['start'], $this->_sections['position']['iteration'] = 1;
                 $this->_sections['position']['iteration'] <= $this->_sections['position']['total'];
                 $this->_sections['position']['index'] += $this->_sections['position']['step'], $this->_sections['position']['iteration']++):
$this->_sections['position']['rownum'] = $this->_sections['position']['iteration'];
$this->_sections['position']['index_prev'] = $this->_sections['position']['index'] - $this->_sections['position']['step'];
$this->_sections['position']['index_next'] = $this->_sections['position']['index'] + $this->_sections['position']['step'];
$this->_sections['position']['first']      = ($this->_sections['position']['iteration'] == 1);
$this->_sections['position']['last']       = ($this->_sections['position']['iteration'] == $this->_sections['position']['total']);
?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['location'][$this->_sections['position']['index']]['0'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

<?php if (! $this->_sections['position']['last']): ?> :: <?php endif; ?>
<?php endfor; endif; ?>
<?php endif; ?>
</title>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "meta.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/<?php echo $this->_config[0]['vars']['CSSFile']; ?>
" />
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/modules/Fast_Lane_Checkout/<?php echo $this->_config[0]['vars']['CSSFile']; ?>
" />
</head>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "rectangle_top.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php if ($this->_tpl_vars['active_modules']['SnS_connector']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/SnS_connector/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<!-- main area -->
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td valign="top" width="150"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
<td width="6">&nbsp;</td>
<td valign="top" align="center">
<table cellpadding="0" cellspacing="0" width="700">
<tr>
<td align="left" colspan="3" width="50%">
<!-- central space -->
<br />
<?php if ($this->_tpl_vars['checkout_step'] >= 0): ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/tabs_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellpadding="0" cellspacing="0" width="100%">                                                                                                                    
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/top-left.jpg"></td>
<td style="background-color: #fbfbf3; text-align: center;" height="10"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/top-right.jpg"></td></tr>
<tr>                                                                                                                                                                    
<td bgcolor="#fbfbf3" colspan=3 style="padding-left: 10px; padding-right: 10px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/home_main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>                                           
</tr>                                                                                                                                                                   
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/bottom-left.jpg"></td>
<td width="100%" style="background-color: #fbfbf3; text-align: center;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/bottom-right.jpg"></td></tr>
</table>                       



<?php else: ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table cellpadding="0" cellspacing="0" width="100%">                                                                                                                    
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/top-left.jpg"></td>
<td style="background-color: #fbfbf3; text-align: center;" height="10" width="100%"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/top-right.jpg"></td></tr>
<tr>                                                                                                                                                                    
<td bgcolor="#fbfbf3" colspan=3 style="padding-left: 10px; padding-right: 10px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/home_main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>                                           
</tr>                                                                                                                                                                   
<tr><td height="10" width="9" style="background-color: #fbfbf3; text-align: left;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/bottom-left.jpg"></td>
<td width="100%" style="background-color: #fbfbf3; text-align: center;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" height="10"></td>
<td height="10" width="9" style="background-color: #fbfbf3; text-align: right;"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/bottom-right.jpg"></td></tr>
</table>                       



<?php endif; ?>

<!-- /central space -->
&nbsp;
</td>
</tr>
</table>
</td>
<td width="6">&nbsp;</td>
<td valign="top" width="150"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /> </td>
</tr>
</table>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "rectangle_bottom.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "ga_code.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body>
</html>