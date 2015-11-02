<?php /* Smarty version 2.6.12, created on 2011-10-11 05:40:15
         compiled from main/order_status.tpl */ ?>
<?php func_load_lang($this, "main/order_status.tpl","lbl_wrong_status,lbl_not_dcs_status,lbl_not_finished,lbl_not_paid,lbl_queued,lbl_processed,lbl_backordered,lbl_declined,lbl_failed,lbl_refunded,lbl_complete,lbl_shipped,lbl_not_finished,lbl_not_paid,lbl_queued,lbl_processed,lbl_declined,lbl_backordered,lbl_failed,lbl_complete,lbl_shipped,lbl_refunded"); ?><?php if ($this->_tpl_vars['usertype'] == 'A' && $this->_tpl_vars['current_membership_flag'] == 'FS'):  $this->assign('limited', 'Y');  endif;  if ($this->_tpl_vars['extended'] == "" && $this->_tpl_vars['status'] == ""):  echo $this->_tpl_vars['lng']['lbl_wrong_status']; ?>

<?php elseif ($this->_tpl_vars['mode'] == 'select' && ( $this->_tpl_vars['limited'] == "" || $this->_tpl_vars['extended'] != "" )): ?>
<select name="<?php echo $this->_tpl_vars['name']; ?>
" <?php echo $this->_tpl_vars['extra']; ?>
>
<?php if ($this->_tpl_vars['extended'] != "" && $this->_tpl_vars['limited'] == ""): ?><option value=""></option>
<?php if ($this->_tpl_vars['usertype'] == 'A' || $this->_tpl_vars['usertype'] == 'P'): ?>
<option value="not_DCS"<?php if ($this->_tpl_vars['status'] == 'not_DCS'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_dcs_status']; ?>
</option>
<?php endif;  endif;  if ($this->_tpl_vars['limited'] == ""): ?>
<option value="I"<?php if ($this->_tpl_vars['status'] == 'I'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_finished']; ?>
</option>
<option value="N"<?php if ($this->_tpl_vars['status'] == 'N'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_not_paid']; ?>
</option>
<option value="Q"<?php if ($this->_tpl_vars['status'] == 'Q'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_queued']; ?>
</option>
<option value="P"<?php if ($this->_tpl_vars['status'] == 'P'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_processed']; ?>
</option>
<option value="B"<?php if ($this->_tpl_vars['status'] == 'B'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_backordered']; ?>
</option>
<option value="D"<?php if ($this->_tpl_vars['status'] == 'D'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_declined']; ?>
</option>
<option value="F"<?php if ($this->_tpl_vars['status'] == 'F'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_failed']; ?>
</option>
<option value="R"<?php if ($this->_tpl_vars['status'] == 'R'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_refunded']; ?>
</option>
<?php endif; ?>
<option value="C"<?php if ($this->_tpl_vars['status'] == 'C'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_complete']; ?>
</option>
<option value="S"<?php if ($this->_tpl_vars['status'] == 'S'): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['lng']['lbl_shipped']; ?>
</option>
</select>
<?php elseif ($this->_tpl_vars['mode'] == 'static' || $this->_tpl_vars['limited'] != ""):  if ($this->_tpl_vars['status'] == 'I'):  echo $this->_tpl_vars['lng']['lbl_not_finished'];  elseif ($this->_tpl_vars['status'] == 'N'):  echo $this->_tpl_vars['lng']['lbl_not_paid'];  elseif ($this->_tpl_vars['status'] == 'Q'):  echo $this->_tpl_vars['lng']['lbl_queued'];  elseif ($this->_tpl_vars['status'] == 'P'):  echo $this->_tpl_vars['lng']['lbl_processed'];  elseif ($this->_tpl_vars['status'] == 'D'):  echo $this->_tpl_vars['lng']['lbl_declined'];  elseif ($this->_tpl_vars['status'] == 'B'):  echo $this->_tpl_vars['lng']['lbl_backordered'];  elseif ($this->_tpl_vars['status'] == 'F'):  echo $this->_tpl_vars['lng']['lbl_failed'];  elseif ($this->_tpl_vars['status'] == 'C'):  echo $this->_tpl_vars['lng']['lbl_complete'];  elseif ($this->_tpl_vars['status'] == 'S'):  echo $this->_tpl_vars['lng']['lbl_shipped'];  elseif ($this->_tpl_vars['status'] == 'R'):  echo $this->_tpl_vars['lng']['lbl_refunded'];  endif;  endif; ?>