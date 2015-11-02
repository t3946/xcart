<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:02
         compiled from bottom.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'bottom.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "bottom.tpl","lbl_help"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "bottom.tpl"), $this); endif;  if ($this->_tpl_vars['usertype'] == 'C'): ?>

<?php if ($this->_tpl_vars['main'] != 'fast_lane_checkout' && $this->_tpl_vars['pages_menu'] != ""): ?>
<div style="margin: 9px 10px 0px 10px; padding: 8px; background-color: #EFEDDF;">

<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;" border="0">
<tr>
<td align="left" style="vertical-align: top;" colspan="4">
<span class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_help']; ?>
</span>
</td>
</tr>

<tr>
<td>
        <table cellspacing="0" cellpadding="0" width="100%">
                <tr>

<?php $this->assign('cell_counter', 0); ?>

<?php unset($this->_sections['pg']);
$this->_sections['pg']['name'] = 'pg';
$this->_sections['pg']['loop'] = is_array($_loop=$this->_tpl_vars['pages_menu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pg']['show'] = true;
$this->_sections['pg']['max'] = $this->_sections['pg']['loop'];
$this->_sections['pg']['step'] = 1;
$this->_sections['pg']['start'] = $this->_sections['pg']['step'] > 0 ? 0 : $this->_sections['pg']['loop']-1;
if ($this->_sections['pg']['show']) {
    $this->_sections['pg']['total'] = $this->_sections['pg']['loop'];
    if ($this->_sections['pg']['total'] == 0)
        $this->_sections['pg']['show'] = false;
} else
    $this->_sections['pg']['total'] = 0;
if ($this->_sections['pg']['show']):

            for ($this->_sections['pg']['index'] = $this->_sections['pg']['start'], $this->_sections['pg']['iteration'] = 1;
                 $this->_sections['pg']['iteration'] <= $this->_sections['pg']['total'];
                 $this->_sections['pg']['index'] += $this->_sections['pg']['step'], $this->_sections['pg']['iteration']++):
$this->_sections['pg']['rownum'] = $this->_sections['pg']['iteration'];
$this->_sections['pg']['index_prev'] = $this->_sections['pg']['index'] - $this->_sections['pg']['step'];
$this->_sections['pg']['index_next'] = $this->_sections['pg']['index'] + $this->_sections['pg']['step'];
$this->_sections['pg']['first']      = ($this->_sections['pg']['iteration'] == 1);
$this->_sections['pg']['last']       = ($this->_sections['pg']['iteration'] == $this->_sections['pg']['total']);
?>

<?php if ($this->_tpl_vars['cell_counter'] == '0'): ?>
                        <td width="25%" align="left" valign="top">
<?php endif; ?>

<?php if ($this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['new_link'] != ""): ?>
<a href="<?php echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['new_link']; ?>
" class="VertMenuItems"><?php echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['title']; ?>
</a>
<?php else: ?>
<?php if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['pageid']): ?><a href="/pages.php?pageid=<?php echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['pageid']; ?>
" class="VertMenuItems"><?php else: ?><font class="VertMenuItems"><?php endif;  echo $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['title'];  if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['pages_menu'][$this->_sections['pg']['index']]['pageid']): ?></a><?php else: ?></font><?php endif; ?>
<?php endif; ?>
<br />

<?php $this->assign('cell_counter', $this->_tpl_vars['cell_counter']+1); ?>

<?php if ($this->_tpl_vars['cell_counter'] == $this->_tpl_vars['count_pages_menu_in_cell']): ?>
                        </td>
<?php $this->assign('cell_counter', 0); ?>
<?php endif; ?>

<?php endfor; endif; ?>

<?php if ($this->_tpl_vars['cell_counter'] > 0): ?>
                        </td>
<?php endif; ?>

                </tr>
        </table>
</td>
</tr>
</table>

</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Company']['cidev_footer_code'] != ""): ?>
<?php echo $this->_tpl_vars['config']['Company']['cidev_footer_code']; ?>

<?php else: ?>
<?php echo $this->_tpl_vars['config']['Storefront_common_details']['common_footer_code']; ?>

<?php endif; ?>

</td></td>

<TR>
<TD class="Bottom" align="left" colspan="4" style="padding-left: 10px;" >
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "copyright.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if ($this->_tpl_vars['usertype'] == 'C'): ?></TD>
</TR>
</TABLE>
<?php endif; ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "bottom.tpl"), $this); endif; ?>