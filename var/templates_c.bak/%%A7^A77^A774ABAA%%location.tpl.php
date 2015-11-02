<?php /* Smarty version 2.6.12, created on 2011-10-11 05:54:16
         compiled from admin/main/location.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'amp', 'admin/main/location.tpl', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['category_location'] && $this->_tpl_vars['cat'] != ""):   print_r($current_category)  ?>
<font class="NavigationPath">
<?php echo '';  unset($this->_sections['position']);
$this->_sections['position']['name'] = 'position';
$this->_sections['position']['loop'] = is_array($_loop=$this->_tpl_vars['category_location']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 echo '';  if ($this->_tpl_vars['category_location'][$this->_sections['position']['index']]['1'] != ""):  echo '<a href="';  echo ((is_array($_tmp=$this->_tpl_vars['category_location'][$this->_sections['position']['index']]['1'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '';  if ($GLOBALS['HTTP_GET_VARS']['mode'] == 'info' || $this->_tpl_vars['current_category']['main_order_by'] > 500):  echo '';  if ($this->_sections['position']['first']):  echo '?mode=info';  else:  echo '&mode=info';  endif;  echo '';  endif;  echo '" class="NavigationPath">';  endif;  echo '';  echo $this->_tpl_vars['category_location'][$this->_sections['position']['index']]['0'];  echo '';  if ($this->_tpl_vars['category_location'][$this->_sections['position']['index']]['1'] != ""):  echo '</a>';  endif;  echo '';  if ($this->_sections['position']['last'] != 'true'):  echo '&nbsp;&gt;&nbsp;';  endif;  echo '';  endfor; endif;  echo '</font>'; ?>

<br /><br />
<?php endif; ?>