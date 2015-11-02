<?php /* Smarty version 2.6.12, created on 2011-10-11 06:22:21
         compiled from customer/main/subheader.tpl */ ?>
<?php if ($this->_tpl_vars['class'] == 'grey'): ?>
<table cellspacing="0" class="SubHeaderGrey">
<tr>
	<td class="SubHeaderGrey"><?php echo $this->_tpl_vars['title']; ?>
</td>
</tr>
<tr>
	<td class="SubHeaderGreyLine"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /></td>
</tr>
</table>
<?php elseif ($this->_tpl_vars['class'] == 'black'): ?>
<table cellspacing="0" class="SubHeaderBlack">
<tr>
	<td class="SubHeaderBlack"><?php echo $this->_tpl_vars['title']; ?>
</td>
</tr>
<tr>
	<td class="SubHeaderBlackLine"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /><br /></td>
</tr>
</table>
<?php else: ?>
<table cellspacing="0" class="SubHeader">
<tr>
	<td class="Green2"><?php echo $this->_tpl_vars['title']; ?>
</td>
</tr>
<tr>
	<td class="SubHeaderLine"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/spacer.gif" class="Spc" alt="" /><br /></td>
</tr>
</table>
<?php endif; ?>