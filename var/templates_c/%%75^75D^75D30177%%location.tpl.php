<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from location.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'location.tpl', 1, false),array('modifier', 'amp', 'location.tpl', 11, false),)), $this); ?>
<?php func_load_lang($this, "location.tpl","lbl_location_delimiter"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "location.tpl"), $this); endif;  if ($this->_tpl_vars['location']): ?>
<br>
<?php echo '';  unset($this->_sections['position']);
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
 echo '';  if ($this->_tpl_vars['location'][$this->_sections['position']['index']]['1'] != ""):  echo '';  if ($this->_tpl_vars['cat_for_itemscope'][$this->_sections['position']['index']] != ""):  echo '<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb" style="display: inline;">';  endif;  echo '<a href="';  echo ((is_array($_tmp=$this->_tpl_vars['location'][$this->_sections['position']['index']]['1'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp));  echo '" class="NavigationPath" ';  if ($this->_tpl_vars['cat_for_itemscope'][$this->_sections['position']['index']] != ""):  echo 'itemprop="url"';  endif;  echo '>';  endif;  echo '';  if ($this->_tpl_vars['cat_for_itemscope'][$this->_sections['position']['index']] != ""):  echo '<span itemprop="title">';  endif;  echo '';  echo $this->_tpl_vars['location'][$this->_sections['position']['index']]['0'];  echo '';  if ($this->_tpl_vars['cat_for_itemscope'][$this->_sections['position']['index']] != ""):  echo '</span>';  endif;  echo '';  if ($this->_tpl_vars['location'][$this->_sections['position']['index']]['1'] != ""):  echo '</a>';  if ($this->_tpl_vars['cat_for_itemscope'][$this->_sections['position']['index']] != ""):  echo '</div>';  endif;  echo '';  endif;  echo '';  if (! $this->_sections['position']['last']):  echo '';  echo $this->_tpl_vars['lng']['lbl_location_delimiter'];  echo '';  endif;  echo '';  endfor; endif;  echo ''; ?>

<br />
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "location.tpl"), $this); endif; ?>