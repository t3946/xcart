<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:52
         compiled from modules/Extra_Fields/product.tpl */ ?>
<?php unset($this->_sections['field']);
$this->_sections['field']['name'] = 'field';
$this->_sections['field']['loop'] = is_array($_loop=$this->_tpl_vars['extra_fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['field']['show'] = true;
$this->_sections['field']['max'] = $this->_sections['field']['loop'];
$this->_sections['field']['step'] = 1;
$this->_sections['field']['start'] = $this->_sections['field']['step'] > 0 ? 0 : $this->_sections['field']['loop']-1;
if ($this->_sections['field']['show']) {
    $this->_sections['field']['total'] = $this->_sections['field']['loop'];
    if ($this->_sections['field']['total'] == 0)
        $this->_sections['field']['show'] = false;
} else
    $this->_sections['field']['total'] = 0;
if ($this->_sections['field']['show']):

            for ($this->_sections['field']['index'] = $this->_sections['field']['start'], $this->_sections['field']['iteration'] = 1;
                 $this->_sections['field']['iteration'] <= $this->_sections['field']['total'];
                 $this->_sections['field']['index'] += $this->_sections['field']['step'], $this->_sections['field']['iteration']++):
$this->_sections['field']['rownum'] = $this->_sections['field']['iteration'];
$this->_sections['field']['index_prev'] = $this->_sections['field']['index'] - $this->_sections['field']['step'];
$this->_sections['field']['index_next'] = $this->_sections['field']['index'] + $this->_sections['field']['step'];
$this->_sections['field']['first']      = ($this->_sections['field']['iteration'] == 1);
$this->_sections['field']['last']       = ($this->_sections['field']['iteration'] == $this->_sections['field']['total']);
 if ($this->_tpl_vars['extra_fields'][$this->_sections['field']['index']]['active'] == 'Y' && $this->_tpl_vars['extra_fields'][$this->_sections['field']['index']]['field_value']): ?>
<tr>
	<td width="30%"><?php echo $this->_tpl_vars['extra_fields'][$this->_sections['field']['index']]['field']; ?>
</td>
	<td><?php echo $this->_tpl_vars['extra_fields'][$this->_sections['field']['index']]['field_value']; ?>
</td>
</tr>
<?php endif;  endfor; endif; ?>