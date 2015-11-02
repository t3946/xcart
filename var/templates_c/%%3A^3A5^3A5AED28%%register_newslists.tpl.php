<?php /* Smarty version 2.6.12, created on 2015-11-02 03:06:29
         compiled from modules/Mailchimp_Subscription/customer/register_newslists.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Mailchimp_Subscription/customer/register_newslists.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "modules/Mailchimp_Subscription/customer/register_newslists.tpl","lbl_newsletter,lbl_newsletter_signup_text"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Mailchimp_Subscription/customer/register_newslists.tpl"), $this); endif;  if ($this->_tpl_vars['active_modules']['Mailchimp_Subscription']): ?>

<?php if ($this->_tpl_vars['hide_header'] == ""): ?>
<tr>
<td height="20" colspan="3"><b><?php echo $this->_tpl_vars['lng']['lbl_newsletter']; ?>
</b><hr size="1" noshade="noshade" /></td>
</tr>
<?php endif; ?>
<tr>
<td align="right" valign="top" style="padding-top: 7px;">
<?php echo $this->_tpl_vars['lng']['lbl_newsletter_signup_text']; ?>

</td>
<td>&nbsp;</td>
<td>
<table border="0" cellpadding="0" cellspacing="0">

<?php unset($this->_sections['idx']);
$this->_sections['idx']['name'] = 'idx';
$this->_sections['idx']['loop'] = is_array($_loop=$this->_tpl_vars['mc_newslists']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['idx']['show'] = true;
$this->_sections['idx']['max'] = $this->_sections['idx']['loop'];
$this->_sections['idx']['step'] = 1;
$this->_sections['idx']['start'] = $this->_sections['idx']['step'] > 0 ? 0 : $this->_sections['idx']['loop']-1;
if ($this->_sections['idx']['show']) {
    $this->_sections['idx']['total'] = $this->_sections['idx']['loop'];
    if ($this->_sections['idx']['total'] == 0)
        $this->_sections['idx']['show'] = false;
} else
    $this->_sections['idx']['total'] = 0;
if ($this->_sections['idx']['show']):

            for ($this->_sections['idx']['index'] = $this->_sections['idx']['start'], $this->_sections['idx']['iteration'] = 1;
                 $this->_sections['idx']['iteration'] <= $this->_sections['idx']['total'];
                 $this->_sections['idx']['index'] += $this->_sections['idx']['step'], $this->_sections['idx']['iteration']++):
$this->_sections['idx']['rownum'] = $this->_sections['idx']['iteration'];
$this->_sections['idx']['index_prev'] = $this->_sections['idx']['index'] - $this->_sections['idx']['step'];
$this->_sections['idx']['index_next'] = $this->_sections['idx']['index'] + $this->_sections['idx']['step'];
$this->_sections['idx']['first']      = ($this->_sections['idx']['iteration'] == 1);
$this->_sections['idx']['last']       = ($this->_sections['idx']['iteration'] == $this->_sections['idx']['total']);
 $this->assign('mc_list_id', $this->_tpl_vars['mc_newslists'][$this->_sections['idx']['index']]['mc_list_id']); ?>
<tr>
<td width="40px;">            
<input type="checkbox" name="mailchimp_subscription[<?php echo $this->_tpl_vars['mc_list_id']; ?>
]" <?php if ($this->_tpl_vars['mailchimp_subscription'][$this->_tpl_vars['mc_list_id']] != ""): ?>checked<?php endif; ?> />
</td>
<td><?php echo $this->_tpl_vars['mc_newslists'][$this->_sections['idx']['index']]['name']; ?>
</td>
</tr>
<tr>
<td>&nbsp;</td>
<td><i><?php echo $this->_tpl_vars['mc_newslists'][$this->_sections['idx']['index']]['descr']; ?>
</i></td>
</tr>
<?php endfor; endif; ?>

</table>
</td>
</tr>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Mailchimp_Subscription/customer/register_newslists.tpl"), $this); endif; ?>