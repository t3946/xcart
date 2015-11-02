<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from modules/Detailed_Product_Images/product_images.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'modules/Detailed_Product_Images/product_images.tpl', 1, false),array('modifier', 'escape', 'modules/Detailed_Product_Images/product_images.tpl', 11, false),)), $this); ?>
<?php func_load_lang($this, "modules/Detailed_Product_Images/product_images.tpl","lbl_detailed_images,lbl_product_files"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "modules/Detailed_Product_Images/product_images.tpl"), $this); endif;  if ($this->_tpl_vars['images'] != ""):  ob_start(); ?>
<center>
<?php unset($this->_sections['image']);
$this->_sections['image']['name'] = 'image';
$this->_sections['image']['loop'] = is_array($_loop=$this->_tpl_vars['images']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['image']['show'] = true;
$this->_sections['image']['max'] = $this->_sections['image']['loop'];
$this->_sections['image']['step'] = 1;
$this->_sections['image']['start'] = $this->_sections['image']['step'] > 0 ? 0 : $this->_sections['image']['loop']-1;
if ($this->_sections['image']['show']) {
    $this->_sections['image']['total'] = $this->_sections['image']['loop'];
    if ($this->_sections['image']['total'] == 0)
        $this->_sections['image']['show'] = false;
} else
    $this->_sections['image']['total'] = 0;
if ($this->_sections['image']['show']):

            for ($this->_sections['image']['index'] = $this->_sections['image']['start'], $this->_sections['image']['iteration'] = 1;
                 $this->_sections['image']['iteration'] <= $this->_sections['image']['total'];
                 $this->_sections['image']['index'] += $this->_sections['image']['step'], $this->_sections['image']['iteration']++):
$this->_sections['image']['rownum'] = $this->_sections['image']['iteration'];
$this->_sections['image']['index_prev'] = $this->_sections['image']['index'] - $this->_sections['image']['step'];
$this->_sections['image']['index_next'] = $this->_sections['image']['index'] + $this->_sections['image']['step'];
$this->_sections['image']['first']      = ($this->_sections['image']['iteration'] == 1);
$this->_sections['image']['last']       = ($this->_sections['image']['iteration'] == $this->_sections['image']['total']);
 if ($this->_tpl_vars['images'][$this->_sections['image']['index']]['avail'] == 'Y'):  if ($this->_tpl_vars['images'][$this->_sections['image']['index']]['tmbn_url']):  if ($this->_tpl_vars['use_schema_org'] == 'Y'): ?>
<meta itemprop="image" content="<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['tmbn_url']; ?>
">
<?php endif; ?>
<img src="<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['tmbn_url']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['images'][$this->_sections['image']['index']]['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="padding-bottom: 10px;" />
<?php else: ?>
<img src="<?php echo $this->_tpl_vars['xcart_web_dir']; ?>
/image.php?id=<?php echo $this->_tpl_vars['images'][$this->_sections['image']['index']]['imageid']; ?>
&amp;type=D" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['images'][$this->_sections['image']['index']]['alt'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" style="padding-bottom: 10px;" />
<?php endif; ?>
<br />
<?php endif;  endfor; endif; ?>
</center>
<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean();  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_detailed_images'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%"','do_not_use_h1' => 'Y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['product_files'] != ''): ?>
	<?php ob_start(); ?>
		<ul class="no_marker">
			<?php unset($this->_sections['pfile']);
$this->_sections['pfile']['name'] = 'pfile';
$this->_sections['pfile']['loop'] = is_array($_loop=$this->_tpl_vars['product_files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['pfile']['show'] = true;
$this->_sections['pfile']['max'] = $this->_sections['pfile']['loop'];
$this->_sections['pfile']['step'] = 1;
$this->_sections['pfile']['start'] = $this->_sections['pfile']['step'] > 0 ? 0 : $this->_sections['pfile']['loop']-1;
if ($this->_sections['pfile']['show']) {
    $this->_sections['pfile']['total'] = $this->_sections['pfile']['loop'];
    if ($this->_sections['pfile']['total'] == 0)
        $this->_sections['pfile']['show'] = false;
} else
    $this->_sections['pfile']['total'] = 0;
if ($this->_sections['pfile']['show']):

            for ($this->_sections['pfile']['index'] = $this->_sections['pfile']['start'], $this->_sections['pfile']['iteration'] = 1;
                 $this->_sections['pfile']['iteration'] <= $this->_sections['pfile']['total'];
                 $this->_sections['pfile']['index'] += $this->_sections['pfile']['step'], $this->_sections['pfile']['iteration']++):
$this->_sections['pfile']['rownum'] = $this->_sections['pfile']['iteration'];
$this->_sections['pfile']['index_prev'] = $this->_sections['pfile']['index'] - $this->_sections['pfile']['step'];
$this->_sections['pfile']['index_next'] = $this->_sections['pfile']['index'] + $this->_sections['pfile']['step'];
$this->_sections['pfile']['first']      = ($this->_sections['pfile']['iteration'] == 1);
$this->_sections['pfile']['last']       = ($this->_sections['pfile']['iteration'] == $this->_sections['pfile']['total']);
?>
				<li><a href="get_product_file.php?file=<?php echo $this->_tpl_vars['product_files'][$this->_sections['pfile']['index']]['fileid']; ?>
&amp;productid=<?php echo $this->_tpl_vars['product_files'][$this->_sections['pfile']['index']]['productid']; ?>
" class="VertMenuItems"><font size=2><?php echo $this->_tpl_vars['product_files'][$this->_sections['pfile']['index']]['filename']; ?>
</font></a>&nbsp;::&nbsp;<?php echo $this->_tpl_vars['product_files'][$this->_sections['pfile']['index']]['description']; ?>
</li>
			<?php endfor; endif; ?>
		</ul>
	<?php $this->_smarty_vars['capture']['dialog'] = ob_get_contents(); ob_end_clean(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "dialog.tpl", 'smarty_include_vars' => array('title' => $this->_tpl_vars['lng']['lbl_product_files'],'content' => $this->_smarty_vars['capture']['dialog'],'extra' => 'width="100%" class="recommends no_padding_bottom"')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "modules/Detailed_Product_Images/product_images.tpl"), $this); endif; ?>