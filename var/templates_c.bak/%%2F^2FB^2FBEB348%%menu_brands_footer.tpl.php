<?php /* Smarty version 2.6.12, created on 2011-10-11 05:37:51
         compiled from modules/Brands/menu_brands_footer.tpl */ ?>
<?php func_load_lang($this, "modules/Brands/menu_brands_footer.tpl","lbl_brands,lbl_other_brands"); ?><?php if ($this->_tpl_vars['brands_menu'] != '' && $this->_tpl_vars['brands_per_column'] > 0): ?>
<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td style="vertical-align: top;">
<span class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_brands']; ?>
:</span><br />
<?php $this->assign('cell_counter', 1);  unset($this->_sections['mid']);
$this->_sections['mid']['name'] = 'mid';
$this->_sections['mid']['loop'] = is_array($_loop=$this->_tpl_vars['brands_menu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['mid']['show'] = true;
$this->_sections['mid']['max'] = $this->_sections['mid']['loop'];
$this->_sections['mid']['step'] = 1;
$this->_sections['mid']['start'] = $this->_sections['mid']['step'] > 0 ? 0 : $this->_sections['mid']['loop']-1;
if ($this->_sections['mid']['show']) {
    $this->_sections['mid']['total'] = $this->_sections['mid']['loop'];
    if ($this->_sections['mid']['total'] == 0)
        $this->_sections['mid']['show'] = false;
} else
    $this->_sections['mid']['total'] = 0;
if ($this->_sections['mid']['show']):

            for ($this->_sections['mid']['index'] = $this->_sections['mid']['start'], $this->_sections['mid']['iteration'] = 1;
                 $this->_sections['mid']['iteration'] <= $this->_sections['mid']['total'];
                 $this->_sections['mid']['index'] += $this->_sections['mid']['step'], $this->_sections['mid']['iteration']++):
$this->_sections['mid']['rownum'] = $this->_sections['mid']['iteration'];
$this->_sections['mid']['index_prev'] = $this->_sections['mid']['index'] - $this->_sections['mid']['step'];
$this->_sections['mid']['index_next'] = $this->_sections['mid']['index'] + $this->_sections['mid']['step'];
$this->_sections['mid']['first']      = ($this->_sections['mid']['iteration'] == 1);
$this->_sections['mid']['last']       = ($this->_sections['mid']['iteration'] == $this->_sections['mid']['total']);
 $this->assign('cell_counter', $this->_tpl_vars['cell_counter']+1); ?>
<a href="brands.php?brandid=<?php echo $this->_tpl_vars['brands_menu'][$this->_sections['mid']['index']]['brandid']; ?>
" class="NavigationPath"><?php echo $this->_tpl_vars['brands_menu'][$this->_sections['mid']['index']]['brand']; ?>
</a>
<?php if ($this->_tpl_vars['cell_counter'] == $this->_tpl_vars['brands_per_column']):  $this->assign('cell_counter', 0); ?>
</td><td style="vertical-align: top;">
<?php else: ?>
<br />
<?php endif;  endfor; endif;  if ($this->_tpl_vars['show_other_brands']): ?>
<a href="brands.php" class="NavigationPath"><?php echo $this->_tpl_vars['lng']['lbl_other_brands']; ?>
</a>
<?php endif; ?>
</td>
</tr>
</table>
<?php endif; ?>