<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:50
         compiled from head.tpl */ ?>
<CENTER>
<TABLE border="0" cellpadding="0" cellspacing="0"<?php if ($this->_tpl_vars['current_storefront'] == '0' || ! $this->_tpl_vars['current_storefront_info']['image'] || $this->_tpl_vars['current_storefront_info']['image']['image_size'] <= 0): ?> height="170" width="1102" background="/skin1_kolin/top_v6.gif"<?php else: ?> height="<?php echo $this->_tpl_vars['current_storefront_info']['image']['image_y']; ?>
" width="<?php echo $this->_tpl_vars['current_storefront_info']['image']['image_x']; ?>
" background="<?php if ($this->_tpl_vars['current_storefront_info']['image']['image_path'] != ''):  echo $this->_tpl_vars['current_storefront_info']['image']['image_path'];  else:  echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=<?php echo $this->_tpl_vars['current_storefront_info']['storefrontid']; ?>
&amp;type=S<?php endif; ?>"<?php endif; ?>>
<TR>
<TD align="right" valign="top" style="padding-top: 0px; padding-right: 10px;">
<img alt="Front Page" src="/skin1_kolin/images/front-page/S3-Logo-Small-v1.gif" style="width: 113px; height: 51px;">
</TD>
</TR>
<TR>
<TD align="right" valign="bottom" style="padding-bottom: 0px; padding-right: 5px; font-size: 14px; font-weight: bold;">
</TD>
</TR>
</TABLE>
</CENTER>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/top_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>