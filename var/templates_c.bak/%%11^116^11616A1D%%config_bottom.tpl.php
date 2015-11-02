<?php /* Smarty version 2.6.12, created on 2011-10-11 05:40:36
         compiled from modules/XPayments_Connector/config_bottom.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'strip_tags', 'modules/XPayments_Connector/config_bottom.tpl', 20, false),array('modifier', 'escape', 'modules/XPayments_Connector/config_bottom.tpl', 20, false),array('function', 'cycle', 'modules/XPayments_Connector/config_bottom.tpl', 63, false),)), $this); ?>
<?php func_load_lang($this, "modules/XPayments_Connector/config_bottom.tpl","lbl_xpc_test_module,txt_xpc_test_module_note,lbl_xpc_test_module,lbl_xpc_import_payment_methods,txt_xpc_import_payment_modules_note,lbl_xpc_request_payment_methods,txt_xpc_returned_payment_methods,lbl_payment_method,lbl_xpc_pm_id,lbl_xpc_auth,lbl_xpc_capture,lbl_xpc_void,lbl_xpc_refund,lbl_yes,lbl_no,lbl_yes,lbl_no,lbl_yes,lbl_no,lbl_yes,lbl_no,lbl_xpc_import_payment_methods,txt_xpc_import_payment_methods_warn"); ?>
<?php if ($this->_tpl_vars['is_module_configured']): ?>

  <br />
  <br />

  <a name="test_module"></a>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_xpc_test_module'],'class' => 'black')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <?php echo $this->_tpl_vars['lng']['txt_xpc_test_module_note']; ?>


  <br />
  <br />

  <input type="button" name="test_module" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_xpc_test_module'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='configuration.php?option=XPayments_Connector&amp;mode=test_module';" />

  <br />
  <br />
  <br />

  <a name="import"></a>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_xpc_import_payment_methods'],'class' => 'black')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <?php echo $this->_tpl_vars['lng']['txt_xpc_import_payment_modules_note']; ?>


  <br />
  <br />

  <input type="button" name="import_payment_methods" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_xpc_request_payment_methods'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='configuration.php?option=XPayments_Connector&amp;mode=export#export';" />

  <?php if ($this->_tpl_vars['pm_list']): ?>

    <br />
    <br />

    <?php echo $this->_tpl_vars['lng']['txt_xpc_returned_payment_methods']; ?>


    <br />
    <br />

    <table cellpadding="5" cellspacing="1" border="0">

      <tr>
        <td colspan="6" align="right"><a href="configuration.php?option=XPayments_Connector&amp;mode=clear">Clear</a></td>
      </tr>

      <tr class="TableHead">
        <td><?php echo $this->_tpl_vars['lng']['lbl_payment_method']; ?>
</td>
        <td><?php echo $this->_tpl_vars['lng']['lbl_xpc_pm_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['lng']['lbl_xpc_auth']; ?>
</td>
        <td><?php echo $this->_tpl_vars['lng']['lbl_xpc_capture']; ?>
</td>
        <td><?php echo $this->_tpl_vars['lng']['lbl_xpc_void']; ?>
</td>
        <td><?php echo $this->_tpl_vars['lng']['lbl_xpc_refund']; ?>
</td>
      </tr>

      <?php $_from = $this->_tpl_vars['pm_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pm']):
?>
        <tr<?php echo smarty_function_cycle(array('values' => ', class="TableSubHead"'), $this);?>
>
          <td><?php echo $this->_tpl_vars['pm']['name']; ?>
</td>
          <td><?php echo $this->_tpl_vars['pm']['id']; ?>
</td>
          <td><?php if ($this->_tpl_vars['pm']['transactionTypes'][@XPC_TRAN_TYPE_AUTH]):  echo $this->_tpl_vars['lng']['lbl_yes'];  else:  echo $this->_tpl_vars['lng']['lbl_no'];  endif; ?></td>
          <td><?php if ($this->_tpl_vars['pm']['transactionTypes'][@XPC_TRAN_TYPE_CAPTURE]):  echo $this->_tpl_vars['lng']['lbl_yes'];  else:  echo $this->_tpl_vars['lng']['lbl_no'];  endif; ?></td>
          <td><?php if ($this->_tpl_vars['pm']['transactionTypes'][@XPC_TRAN_TYPE_VOID]):  echo $this->_tpl_vars['lng']['lbl_yes'];  else:  echo $this->_tpl_vars['lng']['lbl_no'];  endif; ?></td>
          <td><?php if ($this->_tpl_vars['pm']['transactionTypes'][@XPC_TRAN_TYPE_REFUND]):  echo $this->_tpl_vars['lng']['lbl_yes'];  else:  echo $this->_tpl_vars['lng']['lbl_no'];  endif; ?></td>
        </tr>
      <?php endforeach; endif; unset($_from); ?>

    </table>

    <br />
    <br />

    <input type="button" name="import" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['lng']['lbl_xpc_import_payment_methods'])) ? $this->_run_mod_handler('strip_tags', true, $_tmp, false) : smarty_modifier_strip_tags($_tmp, false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onclick="javascript: self.location='configuration.php?option=XPayments_Connector&amp;mode=import';" />

    <?php if ($this->_tpl_vars['pm_found']): ?>
      <br />
      <?php echo $this->_tpl_vars['lng']['txt_xpc_import_payment_methods_warn']; ?>

    <?php endif; ?>

  <?php endif; ?>

<?php endif; ?>