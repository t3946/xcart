<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:28
         compiled from main/register_additional_info.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'main/register_additional_info.tpl', 1, false),array('modifier', 'default', 'main/register_additional_info.tpl', 53, false),array('modifier', 'escape', 'main/register_additional_info.tpl', 69, false),array('modifier', 'replace', 'main/register_additional_info.tpl', 69, false),)), $this); ?>
<?php func_load_lang($this, "main/register_additional_info.tpl","lbl_additional_information,lbl_CHECKOUT_FIELD_DESCRIPTION_company,lbl_fill_in_examples_Company_name"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "main/register_additional_info.tpl"), $this); endif;  if ($this->_tpl_vars['usertype'] != 'P' && $this->_tpl_vars['usertype'] != 'A'): ?>
<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[
<?php echo '
  $(document).ready(function() {  
        $(\'#additional_values_2\').focusout(function() {
                if ($(\'#additional_values_2\').val() != ""){
                        if (document.getElementById("additional_values_2") && document.getElementById("additional_values_2_error")){
                                document.getElementById("additional_values_2_verified").style.display = \'\';                      
                                document.getElementById("additional_values_2_error").style.display = \'none\';     
                        }
                }
                else {
                        if (document.getElementById("additional_values_2_verified") && document.getElementById("additional_values_2_error")){
                                document.getElementById("additional_values_2_verified").style.display = \'none\';                      
                                document.getElementById("additional_values_2_error").style.display = \'none\';  
                        }
                }
        });

        $(\'#additional_values_1\').focusout(function() {
                if ($(\'#additional_values_1\').val() != ""){
                        if (document.getElementById("additional_values_1") && document.getElementById("additional_values_1_error")){
                                document.getElementById("additional_values_1_verified").style.display = \'\';                      
                                document.getElementById("additional_values_1_error").style.display = \'none\';     
                        }
                }
                else {
                        if (document.getElementById("additional_values_1_verified") && document.getElementById("additional_values_1_error")){
                                document.getElementById("additional_values_1_verified").style.display = \'none\';                      
                                document.getElementById("additional_values_1_error").style.display = \'none\';  
                        }
                }
        });
  });
'; ?>

//]]>
</script>
<?php endif; ?>


<?php if ($this->_tpl_vars['section'] != '' && $this->_tpl_vars['additional_fields'] != '' && ( ( $this->_tpl_vars['is_areas']['A'] == 'Y' && $this->_tpl_vars['section'] == 'A' ) || $this->_tpl_vars['section'] != 'A' )):  if ($this->_tpl_vars['hide_header'] == "" && $this->_tpl_vars['section'] == 'A'): ?>
<tr>
<td height="20" colspan="3"><font class="RegSectionTitle"><?php echo $this->_tpl_vars['lng']['lbl_additional_information']; ?>
</font><hr size="1" noshade="noshade" /></td>
</tr>
<?php endif;  $_from = $this->_tpl_vars['additional_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['v']):
 if ($this->_tpl_vars['section'] == $this->_tpl_vars['v']['section'] && $this->_tpl_vars['v']['avail'] == 'Y'): ?>
<tr>
<td class="cidev_padding_top" valign="top" align="right"><?php echo ((is_array($_tmp=@$this->_tpl_vars['v']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['v']['field']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['v']['field'])); ?>
 <?php if ($this->_tpl_vars['v']['required'] != 'Y'): ?><font style="font-size: 11px; font-family: italic; color: #8F8F8F;"><I>(optional)</I></font><?php endif; ?>

<?php if ($this->_tpl_vars['v']['title'] == 'Company' && ( $this->_tpl_vars['section'] == 'S' || $this->_tpl_vars['section'] == 'B' ) && $this->_tpl_vars['usertype'] == 'C'): ?>
<div class="cidev_checkout_descr"><?php echo $this->_tpl_vars['lng']['lbl_CHECKOUT_FIELD_DESCRIPTION_company']; ?>
</div>
<?php endif; ?>

</td>
<td valign="top"><?php if ($this->_tpl_vars['v']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td valign="top" nowrap="nowrap">
<?php if ($this->_tpl_vars['v']['type'] == 'T'):  if ($this->_tpl_vars['v']['title'] == 'Company' && ( $this->_tpl_vars['section'] == 'S' || $this->_tpl_vars['section'] == 'B' ) && $this->_tpl_vars['usertype'] == 'C'): ?>
<table cellpadding="0" cellspacing="0">
<tr>
<td valign="top" nowrap="nowrap">
<?php endif; ?>

<input type="text" name="additional_values[<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]" id="additional_values_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
" size="32" value="<?php if ($this->_tpl_vars['v']['value'] == "" && ( $this->_tpl_vars['new_login_type'] == 'P' || $this->_tpl_vars['new_login_type'] == 'A' ) && $this->_tpl_vars['main'] == 'user_add'):  echo $this->_tpl_vars['config']['Company']['operating_company_name'];  else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['v']['value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, "&amp;#039;", "'") : smarty_modifier_replace($_tmp, "&amp;#039;", "'"));  endif; ?>" <?php if ($this->_tpl_vars['v']['title'] == 'Company'): ?> placeholder="<?php echo $this->_tpl_vars['lng']['lbl_fill_in_examples_Company_name']; ?>
" <?php endif; ?> onkeyup="cidev_check_field_if_empty('additional_values_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
')" />

<?php if ($this->_tpl_vars['v']['title'] == 'Company' && ( $this->_tpl_vars['section'] == 'S' || $this->_tpl_vars['section'] == 'B' ) && $this->_tpl_vars['usertype'] == 'C'): ?>
</td>
<td id="additional_values_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
_verified" valign="top" nowrap="nowrap" <?php if ($this->_tpl_vars['v']['value'] == ""): ?>style="display: none;"<?php endif; ?>>
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-verified.png" alt="" />
</td>

<td id="additional_values_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
_error" valign="top" nowrap="nowrap" style="display: none;">
<img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/checkmark-error.png" alt="" />
</td>
</tr>
</table>
<?php endif; ?>

<?php elseif ($this->_tpl_vars['v']['type'] == 'C'): ?>
<input type="checkbox" name="additional_values[<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]" id="additional_values_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
" value="Y"<?php if ($this->_tpl_vars['v']['value'] == 'Y'): ?> checked="checked"<?php endif; ?> />
<?php elseif ($this->_tpl_vars['v']['type'] == 'S'): ?>
<select name="additional_values[<?php echo $this->_tpl_vars['v']['fieldid']; ?>
]" id="additional_values_<?php echo $this->_tpl_vars['v']['fieldid']; ?>
">
<?php $_from = $this->_tpl_vars['v']['variants']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
<option value='<?php echo ((is_array($_tmp=$this->_tpl_vars['o'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
'<?php if ($this->_tpl_vars['v']['value'] == $this->_tpl_vars['o']): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['o'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php endif;  if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['v']['value'] == "" && $this->_tpl_vars['v']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif;  endforeach; endif; unset($_from);  endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "main/register_additional_info.tpl"), $this); endif; ?>