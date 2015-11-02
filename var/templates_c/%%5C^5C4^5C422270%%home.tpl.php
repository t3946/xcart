<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:13
         compiled from modules/Fast_Lane_Checkout/home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Fast_Lane_Checkout/home.tpl', 1, false),array('function', 'config_load', 'modules/Fast_Lane_Checkout/home.tpl', 8, false),array('modifier', 'escape', 'modules/Fast_Lane_Checkout/home.tpl', 14, false),array('modifier', 'replace', 'modules/Fast_Lane_Checkout/home.tpl', 146, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Fast_Lane_Checkout/home.tpl"), $this); endif; ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php if ($this->_tpl_vars['current_storefront_info']['storefrontid'] != ""): ?>
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=<?php echo $this->_tpl_vars['current_storefront_info']['storefrontid']; ?>
&amp;type=F" type="image/vnd.microsoft.icon" />
<?php else: ?>
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/favicon.ico" type="image/vnd.microsoft.icon" />
<?php endif; ?>
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
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/US_City_List/jquery.autocomplete.css" />

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['SkinDir']; ?>
/lib/colorbox/colorbox.css" />
<script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/lib/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>

</head>
<body>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cidev_tracking_code.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


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
<td valign="top" align="center">
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td align="left" colspan="3" width="100%">
<!-- central space -->
<br />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/tabs_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['checkout_step'] >= 0): ?>


<?php if ($GLOBALS['_GET']['shipping_error'] == 'Y'): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog_message.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<table cellpadding="0" cellspacing="0" width="100%">                                                                                                                    

<tr>                                                                                                                                                                    
<td  bgcolor="#ffffff" colspan=3 style="padding-left: 10px; padding-right: 10px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/home_main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>                                           
</tr>                                                                                                                                                                   
</table>                       



<?php else: ?>


<table cellpadding="0" cellspacing="0" width="100%">                                                                                                                   
<tr>                                                                                                                                                                    
<td  bgcolor="#ffffff" colspan=3 style="padding-left: 10px; padding-right: 10px;"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Fast_Lane_Checkout/home_main.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>                                           
</tr>                                                                                                                                                                   
</table>                       



<?php endif; ?>

<!-- /central space -->
&nbsp;
</td>
</tr>
</table>
</td>
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

<?php if ($this->_tpl_vars['config']['Company']['cidev_google_adwords'] != ""): ?>
	<?php $this->assign('ecomm_prodid_replacement', "ecomm_prodid: ''"); ?>
	<?php $this->assign('ecomm_pagetype_replacement', "ecomm_pagetype: 'siteview'"); ?>
	<?php $this->assign('ecomm_totalvalue_replacement', "ecomm_totalvalue: ''"); ?>

	<?php if ($this->_tpl_vars['main'] == 'fast_lane_checkout' && $this->_tpl_vars['checkout_step'] == "-1"): ?>
		<?php $this->assign('ecomm_prodid_replacement', "ecomm_prodid: ".($this->_tpl_vars['productids_in_cart_imploded'])); ?>
		<?php $this->assign('ecomm_pagetype_replacement', "ecomm_pagetype: 'cart'"); ?>
		<?php $this->assign('ecomm_totalvalue_replacement', "ecomm_totalvalue: '".($this->_tpl_vars['cart']['total_cost'])."'"); ?>
	<?php endif; ?>

	<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['config']['Company']['cidev_google_adwords'])) ? $this->_run_mod_handler('replace', true, $_tmp, "ecomm_prodid: ''", ($this->_tpl_vars['ecomm_prodid_replacement'])) : smarty_modifier_replace($_tmp, "ecomm_prodid: ''", ($this->_tpl_vars['ecomm_prodid_replacement']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "ecomm_pagetype: 'siteview'", ($this->_tpl_vars['ecomm_pagetype_replacement'])) : smarty_modifier_replace($_tmp, "ecomm_pagetype: 'siteview'", ($this->_tpl_vars['ecomm_pagetype_replacement']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "ecomm_totalvalue: ''", ($this->_tpl_vars['ecomm_totalvalue_replacement'])) : smarty_modifier_replace($_tmp, "ecomm_totalvalue: ''", ($this->_tpl_vars['ecomm_totalvalue_replacement']))); ?>

<?php endif; ?>

 <?php if ($this->_tpl_vars['GTS_badge_code'] != ""): ?>
       <?php echo $this->_tpl_vars['GTS_badge_code']; ?>

 <?php endif; ?>
 <?php if ($this->_tpl_vars['GTS_order_confirmation_module_code'] != ""): ?>
       <?php echo $this->_tpl_vars['GTS_order_confirmation_module_code']; ?>

 <?php endif; ?>

</body>
</html>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Fast_Lane_Checkout/home.tpl"), $this); endif; ?>