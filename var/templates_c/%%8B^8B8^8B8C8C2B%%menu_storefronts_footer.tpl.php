<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:02
         compiled from modules/Multiple_Storefronts/menu_storefronts_footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Multiple_Storefronts/menu_storefronts_footer.tpl', 1, false),)), $this); ?>
<?php func_load_lang($this, "modules/Multiple_Storefronts/menu_storefronts_footer.tpl","lbl_related_stores"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Multiple_Storefronts/menu_storefronts_footer.tpl"), $this); endif; ?><!--
<?php echo $this->_tpl_vars['config']['Appearance']['storefront_columns']; ?>

-->

<?php if ($this->_tpl_vars['sf_links'] != ''):  $this->assign('count_cells_in_row', 4); ?>

<table cellspacing="0" cellpadding="0" width="100%" style="margin-left: 13px;">
<tr>
<td align="left" style="vertical-align: top;" colspan="4">
<span class="ProductPrice"><?php echo $this->_tpl_vars['lng']['lbl_related_stores']; ?>
</span>
</td>
</tr>

<tr>
<?php $this->assign('cell_counter', 0); ?>

<?php $_from = $this->_tpl_vars['sf_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>

<?php if ($this->_tpl_vars['cell_counter'] == '0'): ?>
<tr>
<?php endif; ?>

<td width="25%" align="left"><a href="<?php echo $this->_tpl_vars['item']['company_website']; ?>
" class="NavigationPath" target="_blank" rel="nofollow"><?php echo $this->_tpl_vars['item']['company_name']; ?>
</a></td>
<?php $this->assign('cell_counter', $this->_tpl_vars['cell_counter']+1); ?>

<?php if ($this->_tpl_vars['cell_counter'] == $this->_tpl_vars['count_cells_in_row']): ?>
</tr>
<?php $this->assign('cell_counter', 0);  endif; ?>

<?php endforeach; endif; unset($_from); ?>

<?php if ($this->_tpl_vars['cell_counter'] == '1'): ?>
<td colspan="3">&nbsp;</td>
<?php elseif ($this->_tpl_vars['cell_counter'] == '2'): ?>
<td colspan="2">&nbsp;</td>
<?php else: ?>
<td>&nbsp;</td>
<?php endif; ?>

<?php if ($this->_tpl_vars['cell_counter'] > '0'): ?>
</tr>
<?php endif; ?>


</table>
<?php endif; ?>

<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Multiple_Storefronts/menu_storefronts_footer.tpl"), $this); endif; ?>