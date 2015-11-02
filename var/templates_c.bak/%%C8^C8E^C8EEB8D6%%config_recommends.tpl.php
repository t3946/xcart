<?php /* Smarty version 2.6.12, created on 2011-10-11 05:40:36
         compiled from modules/XPayments_Connector/config_recommends.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'modules/XPayments_Connector/config_recommends.tpl', 43, false),)), $this); ?>
<?php func_load_lang($this, "modules/XPayments_Connector/config_recommends.tpl","txt_xpc_requirements_failed,txt_xpc_sys_check_failed,lbl_xpc_recommendations,lbl_xpc_recommend_payment_methods"); ?><table cellpadding="10" cellspacing="0" class="general-settings">
<tr>
  <td>

<?php if ($this->_tpl_vars['system_requirements_errors']): ?>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['txt_xpc_requirements_failed'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <ul>
    <?php $_from = $this->_tpl_vars['system_requirements_errors']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
      <li><?php echo $this->_tpl_vars['e']; ?>
</li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
  <br />
<?php endif; ?>

<?php if ($this->_tpl_vars['check_sys_errs']): ?>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['txt_xpc_sys_check_failed'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <ul>
    <?php $_from = $this->_tpl_vars['check_sys_errs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
      <li><?php echo $this->_tpl_vars['e']; ?>
</li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
  <br />
<?php endif; ?>

<?php if ($this->_tpl_vars['xpc_recommends']): ?>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/subheader.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_xpc_recommendations'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <table cellpadding="7" cellspacing="1">

    <?php $_from = $this->_tpl_vars['xpc_recommends']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type'] => $this->_tpl_vars['recommends']):
?>

      <?php $_from = $this->_tpl_vars['recommends']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['recommendation']):
?>

        <tr<?php echo smarty_function_cycle(array('values' => ', class="TableSubHead"'), $this);?>
>
          <td>
            <img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/<?php if ($this->_tpl_vars['type'] == 'E'): ?>icon_error_small.gif<?php else: ?>icon_warning_small.gif<?php endif; ?>" />
          </td>
          <td>
            <?php if ($this->_tpl_vars['key'] == 'payment_methods'): ?>

              <?php echo $this->_tpl_vars['lng']['lbl_xpc_recommend_payment_methods']; ?>
<br />
              <ul>
                <?php $_from = $this->_tpl_vars['recommendation']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['payment_module']):
?>
                  <li><?php echo $this->_tpl_vars['payment_module']; ?>
</li>
                <?php endforeach; endif; unset($_from); ?>
              </ul>

            <?php else: ?>

              <?php echo $this->_tpl_vars['recommendation']; ?>


            <?php endif; ?>
          </td>
        </tr>

      <?php endforeach; endif; unset($_from); ?>

    <?php endforeach; endif; unset($_from); ?>

  </table>

<?php endif; ?>
  </td>
</tr>
</table>