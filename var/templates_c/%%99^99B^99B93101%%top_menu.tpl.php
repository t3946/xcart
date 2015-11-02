<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from customer/top_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/top_menu.tpl', 1, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/top_menu.tpl"), $this); endif;  if ($this->_tpl_vars['printable'] != ''):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/top_menu_printable.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
<table cellpadding="0" cellspacing="0" width="100%">
<?php if ($this->_tpl_vars['speed_bar']): ?>
<tr>
<td valign="top" align="right">
<table cellpadding="0" cellspacing="0">
<tr>
<?php unset($this->_sections['sb']);
$this->_sections['sb']['name'] = 'sb';
$this->_sections['sb']['loop'] = is_array($_loop=$this->_tpl_vars['speed_bar']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['sb']['show'] = true;
$this->_sections['sb']['max'] = $this->_sections['sb']['loop'];
$this->_sections['sb']['step'] = 1;
$this->_sections['sb']['start'] = $this->_sections['sb']['step'] > 0 ? 0 : $this->_sections['sb']['loop']-1;
if ($this->_sections['sb']['show']) {
    $this->_sections['sb']['total'] = $this->_sections['sb']['loop'];
    if ($this->_sections['sb']['total'] == 0)
        $this->_sections['sb']['show'] = false;
} else
    $this->_sections['sb']['total'] = 0;
if ($this->_sections['sb']['show']):

            for ($this->_sections['sb']['index'] = $this->_sections['sb']['start'], $this->_sections['sb']['iteration'] = 1;
                 $this->_sections['sb']['iteration'] <= $this->_sections['sb']['total'];
                 $this->_sections['sb']['index'] += $this->_sections['sb']['step'], $this->_sections['sb']['iteration']++):
$this->_sections['sb']['rownum'] = $this->_sections['sb']['iteration'];
$this->_sections['sb']['index_prev'] = $this->_sections['sb']['index'] - $this->_sections['sb']['step'];
$this->_sections['sb']['index_next'] = $this->_sections['sb']['index'] + $this->_sections['sb']['step'];
$this->_sections['sb']['first']      = ($this->_sections['sb']['iteration'] == 1);
$this->_sections['sb']['last']       = ($this->_sections['sb']['iteration'] == $this->_sections['sb']['total']);
 if ($this->_tpl_vars['speed_bar'][$this->_sections['sb']['index']]['active'] == 'Y'): ?>
<td valign="top"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/tab.tpl", 'smarty_include_vars' => array('tab_title' => "<a href=\"".($this->_tpl_vars['speed_bar'][$this->_sections['sb']['index']]['link'])."\">".($this->_tpl_vars['speed_bar'][$this->_sections['sb']['index']]['title'])."</a>")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
<td width="1"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
<?php endif;  endfor; endif; ?>
</tr>
</table>
</td>
</tr>
<?php endif; ?>
</table>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/top_menu.tpl"), $this); endif; ?>