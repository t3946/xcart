<?php /* Smarty version 2.6.12, created on 2011-10-11 06:21:51
         compiled from main/register_shipping_address.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'amp', 'main/register_shipping_address.tpl', 100, false),array('modifier', 'default', 'main/register_shipping_address.tpl', 113, false),)), $this); ?>
<?php func_load_lang($this, "main/register_shipping_address.tpl","lbl_shipping_address,txt_fields_are_mandatory,lbl_title,lbl_first_name,lbl_last_name,lbl_address,lbl_address_2,lbl_city,lbl_county,lbl_country,lbl_state,lbl_zip_code"); ?><?php if ($this->_tpl_vars['is_areas']['S'] == 'Y'):  if ($this->_tpl_vars['hide_header'] == ""): ?>
<tr>
<td colspan="3" class="RegSectionTitle"><?php echo $this->_tpl_vars['lng']['lbl_shipping_address']; ?>
<hr size="1" noshade="noshade" /></td>
</tr>
<?php endif; ?>

<tr>
<td colspan="3"><?php echo $this->_tpl_vars['lng']['txt_fields_are_mandatory']; ?>
</td>
</tr>

<?php if ($this->_tpl_vars['default_fields']['s_title']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_title']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_title']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap"> 
<select name="s_title" id="s_title">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/title_selector.tpl", 'smarty_include_vars' => array('field' => $this->_tpl_vars['userinfo']['s_titleid'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</select> 
</td> 
</tr> 
 <?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_firstname']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_first_name']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_firstname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap"> 
<input type="text" id="s_firstname" name="s_firstname" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['s_firstname']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_firstname'] == "" && $this->_tpl_vars['default_fields']['s_firstname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
 <?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_lastname']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_last_name']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_lastname']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="s_lastname" name="s_lastname" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['s_lastname']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_lastname'] == "" && $this->_tpl_vars['default_fields']['s_lastname']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_additional_info.tpl", 'smarty_include_vars' => array('section' => 'S')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['default_fields']['s_address']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_address']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_address']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="s_address" name="s_address" size="32" maxlength="64" value="<?php echo $this->_tpl_vars['userinfo']['s_address']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_address'] == "" && $this->_tpl_vars['default_fields']['s_address']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_address_2']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_address_2']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_address_2']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="s_address_2" name="s_address_2" size="32" maxlength="64" value="<?php echo $this->_tpl_vars['userinfo']['s_address_2']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_address_2'] == "" && $this->_tpl_vars['default_fields']['s_address_2']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_city']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_city']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_city']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="s_city" name="s_city" size="32" maxlength="64" value="<?php echo $this->_tpl_vars['userinfo']['s_city']; ?>
" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_city'] == "" && $this->_tpl_vars['default_fields']['s_city']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_county']['avail'] == 'Y' && $this->_tpl_vars['config']['General']['use_counties'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_county']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_county']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/counties.tpl", 'smarty_include_vars' => array('counties' => $this->_tpl_vars['counties'],'name' => 's_county','default' => $this->_tpl_vars['userinfo']['s_county'],'country_name' => 's_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if (( $this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_county'] == "" && $this->_tpl_vars['default_fields']['s_county']['required'] == 'Y' ) || $this->_tpl_vars['error'] == 's_county'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_country']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_country']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_country']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<select name="s_country" id="s_country" size="1" onchange="check_zip_code()">
<?php unset($this->_sections['country_idx']);
$this->_sections['country_idx']['name'] = 'country_idx';
$this->_sections['country_idx']['loop'] = is_array($_loop=$this->_tpl_vars['countries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['country_idx']['show'] = true;
$this->_sections['country_idx']['max'] = $this->_sections['country_idx']['loop'];
$this->_sections['country_idx']['step'] = 1;
$this->_sections['country_idx']['start'] = $this->_sections['country_idx']['step'] > 0 ? 0 : $this->_sections['country_idx']['loop']-1;
if ($this->_sections['country_idx']['show']) {
    $this->_sections['country_idx']['total'] = $this->_sections['country_idx']['loop'];
    if ($this->_sections['country_idx']['total'] == 0)
        $this->_sections['country_idx']['show'] = false;
} else
    $this->_sections['country_idx']['total'] = 0;
if ($this->_sections['country_idx']['show']):

            for ($this->_sections['country_idx']['index'] = $this->_sections['country_idx']['start'], $this->_sections['country_idx']['iteration'] = 1;
                 $this->_sections['country_idx']['iteration'] <= $this->_sections['country_idx']['total'];
                 $this->_sections['country_idx']['index'] += $this->_sections['country_idx']['step'], $this->_sections['country_idx']['iteration']++):
$this->_sections['country_idx']['rownum'] = $this->_sections['country_idx']['iteration'];
$this->_sections['country_idx']['index_prev'] = $this->_sections['country_idx']['index'] - $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['index_next'] = $this->_sections['country_idx']['index'] + $this->_sections['country_idx']['step'];
$this->_sections['country_idx']['first']      = ($this->_sections['country_idx']['iteration'] == 1);
$this->_sections['country_idx']['last']       = ($this->_sections['country_idx']['iteration'] == $this->_sections['country_idx']['total']);
?>
<option value="<?php echo $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']; ?>
"<?php if ($this->_tpl_vars['userinfo']['s_country'] == $this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code']): ?> selected="selected"<?php elseif ($this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country_code'] == $this->_tpl_vars['config']['General']['default_country'] && $this->_tpl_vars['userinfo']['s_country'] == ""): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['country_idx']['index']]['country'])) ? $this->_run_mod_handler('amp', true, $_tmp) : smarty_modifier_amp($_tmp)); ?>
</option>
<?php endfor; endif; ?>
</select>
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_country'] == "" && $this->_tpl_vars['default_fields']['s_country']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_state']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_state']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_state']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/states.tpl", 'smarty_include_vars' => array('states' => $this->_tpl_vars['states'],'name' => 's_state','default' => $this->_tpl_vars['userinfo']['s_state'],'default_country' => ((is_array($_tmp=@$this->_tpl_vars['userinfo']['s_country'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['config']['General']['default_country']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['config']['General']['default_country'])),'country_name' => 's_country')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  if (( $this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_state'] == "" && $this->_tpl_vars['default_fields']['s_state']['required'] == 'Y' ) || $this->_tpl_vars['error'] == 's_statecode'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['default_fields']['s_state']['avail'] == 'Y' && $this->_tpl_vars['default_fields']['s_country']['avail'] == 'Y' && $this->_tpl_vars['js_enabled'] == 'Y' && $this->_tpl_vars['config']['General']['use_js_states'] == 'Y'): ?>
<tr style="display: none;">
	<td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/register_states.tpl", 'smarty_include_vars' => array('state_name' => 's_state','country_name' => 's_country','county_name' => 's_county','state_value' => $this->_tpl_vars['userinfo']['s_state'],'county_value' => $this->_tpl_vars['userinfo']['s_county'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</td>
</tr>
<?php endif;  if ($this->_tpl_vars['default_fields']['s_zipcode']['avail'] == 'Y'): ?>
<tr>
<td align="right"><?php echo $this->_tpl_vars['lng']['lbl_zip_code']; ?>
</td>
<td><?php if ($this->_tpl_vars['default_fields']['s_zipcode']['required'] == 'Y'): ?><font class="Star">*</font><?php else: ?>&nbsp;<?php endif; ?></td>
<td nowrap="nowrap">
<input type="text" id="s_zipcode" name="s_zipcode" size="32" maxlength="32" value="<?php echo $this->_tpl_vars['userinfo']['s_zipcode']; ?>
" onchange="check_zip_code()" />
<?php if ($this->_tpl_vars['reg_error'] != "" && $this->_tpl_vars['userinfo']['s_zipcode'] == "" && $this->_tpl_vars['default_fields']['s_zipcode']['required'] == 'Y'): ?><font class="Star">&lt;&lt;</font><?php endif; ?>
</td>
</tr>
<?php endif; ?>

<?php endif; ?>