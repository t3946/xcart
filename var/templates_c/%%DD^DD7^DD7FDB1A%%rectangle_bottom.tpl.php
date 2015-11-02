<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:02
         compiled from rectangle_bottom.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'rectangle_bottom.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "rectangle_bottom.tpl"), $this); endif; ?>	</td>
</tr>
<?php if ($this->_tpl_vars['active_modules']['Users_online'] != ""): ?>
<tr>
	<td><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "modules/Users_online/menu_users_online.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
<?php else: ?>
<tr>
	<td><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
<?php endif; ?>
<tr>
	<td class="BottomRow">
<?php if ($this->_tpl_vars['printable'] != ''): ?>
<hr size="1" noshade="noshade" />
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bottom.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>
</table>
</td><tr></table>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "rectangle_bottom.tpl"), $this); endif; ?>