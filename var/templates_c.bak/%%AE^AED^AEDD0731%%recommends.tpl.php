<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:52
         compiled from modules/Recommended_Products/recommends.tpl */ ?>
<?php func_load_lang($this, "modules/Recommended_Products/recommends.tpl","lbl_recommends"); ?><?php if ($this->_tpl_vars['recommends']):  ob_start(); ?>
<ul class="RPItems no_marker">
<?php unset($this->_sections['num']);
$this->_sections['num']['name'] = 'num';
$this->_sections['num']['loop'] = is_array($_loop=$this->_tpl_vars['recommends']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['num']['show'] = true;
$this->_sections['num']['max'] = $this->_sections['num']['loop'];
$this->_sections['num']['step'] = 1;
$this->_sections['num']['start'] = $this->_sections['num']['step'] > 0 ? 0 : $this->_sections['num']['loop']-1;
if ($this->_sections['num']['show']) {
    $this->_sections['num']['total'] = $this->_sections['num']['loop'];
    if ($this->_sections['num']['total'] == 0)
        $this->_sections['num']['show'] = false;
} else
    $this->_sections['num']['total'] = 0;
if ($this->_sections['num']['show']):

            for ($this->_sections['num']['index'] = $this->_sections['num']['start'], $this->_sections['num']['iteration'] = 1;
                 $this->_sections['num']['iteration'] <= $this->_sections['num']['total'];
                 $this->_sections['num']['index'] += $this->_sections['num']['step'], $this->_sections['num']['iteration']++):
$this->_sections['num']['rownum'] = $this->_sections['num']['iteration'];
$this->_sections['num']['index_prev'] = $this->_sections['num']['index'] - $this->_sections['num']['step'];
$this->_sections['num']['index_next'] = $this->_sections['num']['index'] + $this->_sections['num']['step'];
$this->_sections['num']['first']      = ($this->_sections['num']['iteration'] == 1);
$this->_sections['num']['last']       = ($this->_sections['num']['iteration'] == $this->_sections['num']['total']);
?>
	<li>::&nbsp;<a href="product.php?productid=<?php echo $this->_tpl_vars['recommends'][$this->_sections['num']['index']]['productid']; ?>
" class="VertMenuItems"><font size=2><?php echo $this->_tpl_vars['recommends'][$this->_sections['num']['index']]['product']; ?>
</font></a></li>
<?php endfor; endif; ?>
</ul>
<?php $this->_smarty_vars['capture']['recommends'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_recommends'],'content' => $this->_smarty_vars['capture']['recommends'],'extra' => 'width="100%" class="recommends no_padding_bottom"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>