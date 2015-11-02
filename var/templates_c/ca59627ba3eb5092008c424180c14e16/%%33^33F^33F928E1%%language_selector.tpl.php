<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/main/language_selector.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/main/language_selector.tpl', 1, false),array('modifier', 'count', 'customer/main/language_selector.tpl', 5, false),array('modifier', 'amp', 'customer/main/language_selector.tpl', 15, false),array('modifier', 'escape', 'customer/main/language_selector.tpl', 15, false),)), $this); ?>
<?php func_load_lang($this, "customer/main/language_selector.tpl","lbl_select_language,lbl_close"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/main/language_selector.tpl"), $this); endif;  if (count($this->_tpl_vars['all_languages']) > 1): ?>
  <?php ob_start(); ?>
    <ul data-role="listview" data-theme="c">
      <li data-role="list-divider" data-theme="b">
        <?php echo $this->_tpl_vars['lng']['lbl_select_language']; ?>

      </li>
      <?php $_from = $this->_tpl_vars['all_languages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['languages'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['languages']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['l']):
        $this->_foreach['languages']['iteration']++;
?>
        <li>
          <?php if ($this->_tpl_vars['store_language'] == $this->_tpl_vars['l']['code']): ?>
            <?php $this->assign('current_lang', $this->_tpl_vars['l']); ?>
            <img class="ui-li-icon" src="<?php if (! $this->_tpl_vars['l']['is_url']):  echo $this->_tpl_vars['current_location'];  endif;  echo ((is_array($_tmp=$this->_tpl_vars['l']['tmbn_url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['l']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['l']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" width="<?php echo $this->_tpl_vars['l']['image_x']; ?>
" height="<?php echo $this->_tpl_vars['l']['image_y']; ?>
" /><?php echo $this->_tpl_vars['l']['language']; ?>

          <?php else: ?>
            <a href="<?php echo $GLOBALS['_SERVER']['PHP_SELF']; ?>
?<?php if ($GLOBALS['_SERVER']['QUERY_STRING']):  echo $GLOBALS['_SERVER']['QUERY_STRING']; ?>
&<?php endif; ?>sl=<?php echo $this->_tpl_vars['l']['code']; ?>
"><img class="ui-li-icon" src="<?php if (! $this->_tpl_vars['l']['is_url']):  echo $this->_tpl_vars['current_location'];  endif;  echo ((is_array($_tmp=$this->_tpl_vars['l']['tmbn_url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['l']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['l']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" width="<?php echo $this->_tpl_vars['l']['image_x']; ?>
" height="<?php echo $this->_tpl_vars['l']['image_y']; ?>
" /><?php echo $this->_tpl_vars['l']['language']; ?>
</a>
          <?php endif; ?>
        </li>
      <?php endforeach; endif; unset($_from); ?>
    </ul>
  <?php $this->_smarty_vars['capture']['lang_selector'] = ob_get_contents(); ob_end_clean(); ?>
  
  <a href="#popup-lang" data-role="button" data-inline="false" data-theme="a" data-rel="popup"><img src="<?php if (! $this->_tpl_vars['current_lang']['is_url']):  echo $this->_tpl_vars['current_location'];  endif;  echo ((is_array($_tmp=$this->_tpl_vars['current_lang']['tmbn_url'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['current_lang']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" width="<?php echo $this->_tpl_vars['current_lang']['image_x']; ?>
" height="<?php echo $this->_tpl_vars['current_lang']['image_y']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['current_lang']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['current_lang']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
  
  <div id="popup-lang" data-role="popup">
    <a href="<?php echo $this->_tpl_vars['php_url']['url'];  if ($this->_tpl_vars['php_url']['query_string']): ?>?<?php echo $this->_tpl_vars['php_url']['query_string'];  endif; ?>#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right"><?php echo $this->_tpl_vars['lng']['lbl_close']; ?>
</a>
    <?php echo $this->_smarty_vars['capture']['lang_selector']; ?>

  </div>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/main/language_selector.tpl"), $this); endif; ?>