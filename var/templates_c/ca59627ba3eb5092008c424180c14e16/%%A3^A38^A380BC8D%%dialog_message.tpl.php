<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:06
         compiled from customer/dialog_message.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'customer/dialog_message.tpl', 1, false),array('modifier', 'lower', 'customer/dialog_message.tpl', 7, false),array('modifier', 'default', 'customer/dialog_message.tpl', 7, false),array('modifier', 'escape', 'customer/dialog_message.tpl', 7, false),)), $this); ?>
<?php func_load_lang($this, "customer/dialog_message.tpl","lbl_close"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "customer/dialog_message.tpl"), $this); endif;  if ($this->_tpl_vars['top_message']['content'] != "" || $this->_tpl_vars['alt_content'] != ""): ?>
  <div id="dialog-message" class="ui-body ui-body-e">
    <div class="box message-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['top_message']['type'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 'i') : smarty_modifier_default($_tmp, 'i')); ?>
"<?php if ($this->_tpl_vars['top_message']['title']): ?> title="<?php echo ((is_array($_tmp=$this->_tpl_vars['top_message']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>>

      <?php if ($this->_tpl_vars['top_message']['type'] == 'E'): ?>
        <?php $this->assign('dialog_icon', 'alert'); ?>
      <?php elseif ($this->_tpl_vars['top_message']['type'] == 'W'): ?>
        <?php $this->assign('dialog_icon', 'gear'); ?>
      <?php else: ?>
        <?php $this->assign('dialog_icon', 'info'); ?>
      <?php endif; ?>
      <h3 class="ui-title"><span class="ui-icon ui-icon-<?php echo $this->_tpl_vars['dialog_icon']; ?>
 ui-icon-shadow">&nbsp;</span><?php if ($this->_tpl_vars['top_message']['title']):  echo ((is_array($_tmp=$this->_tpl_vars['top_message']['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif; ?></h3>
      <div class="ui-content-e">
        <?php echo ((is_array($_tmp=@$this->_tpl_vars['top_message']['content'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['alt_content']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['alt_content'])); ?>

      </div>
      <?php if ($this->_tpl_vars['top_message']['no_close'] == ""): ?>
        <div class="ui-grid-b">
          <div class="ui-block-a">&nbsp;</div>
          <div class="ui-block-b">&nbsp;</div>
          <div class="ui-block-c">
            <a data-role="button" data-theme="e" data-icon="delete" data-iconpos="right" href="#" onclick="javascript: $('#dialog-message').remove();"><?php echo $this->_tpl_vars['lng']['lbl_close']; ?>
</a>
          </div>
        </div>
      <?php endif; ?>
          </div>
  </div>
<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "customer/dialog_message.tpl"), $this); endif; ?>