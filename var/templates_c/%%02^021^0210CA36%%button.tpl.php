<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from buttons/button.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'buttons/button.tpl', 1, false),array('modifier', 'regex_replace', 'buttons/button.tpl', 4, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "buttons/button.tpl"), $this); endif;  if ($this->_tpl_vars['config']['Adaptives']['platform'] == 'MacPPC' && $this->_tpl_vars['config']['Adaptives']['browser'] == 'NN'):  $this->assign('js_to_href', 'Y');  endif;  if ($this->_tpl_vars['type'] == 'input'):  $this->assign('img_type', 'INPUT type="image"');  else:  $this->assign('img_type', 'IMG');  endif;  $this->assign('js_link', ((is_array($_tmp=$this->_tpl_vars['href'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/^\s*javascript\s*:/Si", "") : smarty_modifier_regex_replace($_tmp, "/^\s*javascript\s*:/Si", "")));  if ($this->_tpl_vars['js_link'] == $this->_tpl_vars['href']): ?>
 <?php if ($this->_tpl_vars['js_onclick_to_href'] != ""):  $this->assign('js_link', "javascript: ".($this->_tpl_vars['js_onclick_to_href'])." self.location='".($this->_tpl_vars['href'])."';");  $this->assign('onclick', "javascript: ".($this->_tpl_vars['js_onclick_to_href'])." self.location='".($this->_tpl_vars['href'])."';"); ?>
 <?php else:  $this->assign('js_link', "javascript: self.location='".($this->_tpl_vars['href'])."'"); ?>
 <?php endif;  else:  $this->assign('js_link', $this->_tpl_vars['href']);  if ($this->_tpl_vars['js_to_href'] != 'Y'):  $this->assign('onclick', $this->_tpl_vars['href']);  $this->assign('href', "javascript: void(0);");  endif;  endif; ?>

<?php if ($this->_tpl_vars['usertype'] == 'C'):  if ($this->_tpl_vars['class'] == 'ajax_button'):  $this->assign('class', 'new_button_green');  else:  $this->assign('class', 'new_button_blue');  endif;  endif; ?>


<?php if ($this->_tpl_vars['button_type'] == 'submit_order'): ?>
<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_submit_order"></span>
<?php elseif ($this->_tpl_vars['button_type'] == 'continue'): ?>
<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_continue"></span>
<?php elseif ($this->_tpl_vars['button_type'] == 'submit'): ?>
<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_submit"></span>
<?php elseif ($this->_tpl_vars['button_type'] == 'checkout'): ?> 


<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn_to_checkout" class="btn_atcart_checkout"></span>
<?php elseif ($this->_tpl_vars['add_to_cart_btn'] == 'big'): ?>
<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn-add-to-cart" class="btn_atcart_big"></span>
<?php elseif ($this->_tpl_vars['add_to_cart_btn'] == 'small'): ?>
<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn-add-to-cart" class="btn_atcart_small"></span>
<?php elseif ($this->_tpl_vars['new_add_to_cart_btn'] == 'Y'): ?>
<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn-add-to-cart" class="btn_atcart_b">
<span class="t"><?php echo $this->_tpl_vars['button_title']; ?>
</span>
</span>

<?php elseif ($this->_tpl_vars['btn_to_checkout'] == 'Y' || ( $GLOBALS['_GET']['mode'] == 'checkout' && ( $this->_tpl_vars['button_title'] == 'Submit' || $this->_tpl_vars['button_title'] == 'Continue' || $this->_tpl_vars['button_title'] == 'Submit order' ) ) || ( $GLOBALS['_GET']['mode'] == 'update' && $GLOBALS['_GET']['action'] == 'cart' && $this->_tpl_vars['button_title'] == 'Submit' )): ?>


<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn_to_checkout" class="btn_to_checkout">
<span class="t"><?php echo $this->_tpl_vars['button_title']; ?>
</span>
</span>


<?php elseif ($this->_tpl_vars['btn_other'] == 'Y'): ?>
<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" id="btn_other" class="btn_other">
<span class="t"><?php echo $this->_tpl_vars['button_title']; ?>
</span>
</span>
<?php else:  if ($this->_tpl_vars['class'] == 'new_button_green' || $this->_tpl_vars['class'] == 'new_button_blue'): ?>

	<?php if ($this->_tpl_vars['class'] == 'new_button_blue'): ?>

		<span class="cidev_new_button cidev_new_white" onclick="<?php echo $this->_tpl_vars['js_link']; ?>
"><?php echo $this->_tpl_vars['button_title']; ?>
</span>

	<?php else: ?>
		<span onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" class="<?php echo $this->_tpl_vars['class']; ?>
" style="cursor: pointer;">
		<?php echo $this->_tpl_vars['button_title']; ?>

		</span>
	<?php endif;  else: ?>
<table border="0" cellspacing="0" cellpadding="0" onclick="<?php echo $this->_tpl_vars['js_link']; ?>
" style="cursor: pointer;" valign="middle"<?php if ($this->_tpl_vars['title'] != ''): ?> title="<?php echo $this->_tpl_vars['title']; ?>
"<?php endif;  if ($this->_tpl_vars['class']): ?> class="<?php echo $this->_tpl_vars['class']; ?>
"<?php endif; ?>>
<tr>
<td class="Button2Off" valign="middle" onMouseOver="this.className='Button2On'" onMouseOut="this.className='Button2Off'"><font class="Button2" <?php if ($this->_tpl_vars['b_size'] != ""): ?>style="font-size: <?php echo $this->_tpl_vars['b_size']; ?>
px;"<?php endif; ?>><?php if ($this->_tpl_vars['b'] == '1'): ?><b><?php endif;  if ($this->_tpl_vars['blue_link'] == 'Y'): ?><span style="color: blue;"><?php endif;  echo $this->_tpl_vars['button_title'];  if ($this->_tpl_vars['blue_link'] == 'Y'): ?></span><?php endif;  if ($this->_tpl_vars['b'] == 1): ?></b><?php endif; ?></font></td>
</tr>
</table>
<?php endif; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "buttons/button.tpl"), $this); endif; ?>