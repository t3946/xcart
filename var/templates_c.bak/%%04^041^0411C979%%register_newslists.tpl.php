<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from modules/News_Management/register_newslists.tpl */ ?>
<?php func_load_lang($this, "modules/News_Management/register_newslists.tpl","lbl_newsletter,lbl_newsletter_signup_text"); ?><?php if ($this->_tpl_vars['active_modules']['News_Management'] && $this->_tpl_vars['newslists']): ?>

<?php if ($this->_tpl_vars['hide_header'] == ""): ?>
<tr>
<td height="20" colspan="3"><b><?php echo $this->_tpl_vars['lng']['lbl_newsletter']; ?>
</b><hr size="1" noshade="noshade" /></td>
</tr>
<?php endif; ?>

<tr>
<td colspan="3"><?php echo $this->_tpl_vars['lng']['lbl_newsletter_signup_text']; ?>
</td>
</tr>

<tr>
<td colspan="2">&nbsp;</td>
<td>
<table border="0">

<?php unset($this->_sections['idx']);
$this->_sections['idx']['name'] = 'idx';
$this->_sections['idx']['loop'] = is_array($_loop=$this->_tpl_vars['newslists']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['idx']['show'] = true;
$this->_sections['idx']['max'] = $this->_sections['idx']['loop'];
$this->_sections['idx']['step'] = 1;
$this->_sections['idx']['start'] = $this->_sections['idx']['step'] > 0 ? 0 : $this->_sections['idx']['loop']-1;
if ($this->_sections['idx']['show']) {
    $this->_sections['idx']['total'] = $this->_sections['idx']['loop'];
    if ($this->_sections['idx']['total'] == 0)
        $this->_sections['idx']['show'] = false;
} else
    $this->_sections['idx']['total'] = 0;
if ($this->_sections['idx']['show']):

            for ($this->_sections['idx']['index'] = $this->_sections['idx']['start'], $this->_sections['idx']['iteration'] = 1;
                 $this->_sections['idx']['iteration'] <= $this->_sections['idx']['total'];
                 $this->_sections['idx']['index'] += $this->_sections['idx']['step'], $this->_sections['idx']['iteration']++):
$this->_sections['idx']['rownum'] = $this->_sections['idx']['iteration'];
$this->_sections['idx']['index_prev'] = $this->_sections['idx']['index'] - $this->_sections['idx']['step'];
$this->_sections['idx']['index_next'] = $this->_sections['idx']['index'] + $this->_sections['idx']['step'];
$this->_sections['idx']['first']      = ($this->_sections['idx']['iteration'] == 1);
$this->_sections['idx']['last']       = ($this->_sections['idx']['iteration'] == $this->_sections['idx']['total']);
 $this->assign('listid', $this->_tpl_vars['newslists'][$this->_sections['idx']['index']]['listid']); ?>
<tr>
<td><input type="checkbox" name="subscription[<?php echo $this->_tpl_vars['listid']; ?>
]" checked /></td>
<td><?php echo $this->_tpl_vars['newslists'][$this->_sections['idx']['index']]['name']; ?>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><i><?php echo $this->_tpl_vars['newslists'][$this->_sections['idx']['index']]['descr']; ?>
</i></td>
</tr>
<?php endfor; endif; ?>

</table>
</td>
</tr>

<?php endif; ?>