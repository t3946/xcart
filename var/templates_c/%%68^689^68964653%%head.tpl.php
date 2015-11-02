<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from head.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'head.tpl', 1, false),array('modifier', 'replace', 'head.tpl', 107, false),)), $this); ?>
<?php func_load_lang($this, "head.tpl","lbl_top_header_nbsp"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "head.tpl"), $this); endif; ?>
<?php if ($this->_tpl_vars['main'] == 'fast_lane_checkout' && $GLOBALS['_GET']['mode'] == ""): ?>
<script type="text/javascript">
//<![CDATA[
<?php echo '
$(function(){
 document.onkeydown = function(e) {
        if (e.keyCode == "81"){
                if (document.getElementById(\'s3_logo\')){
			$(\'#s3_logo\').attr(\'href\', "javascript: window.open(\'popup_shipquote.php\',\'popup_shipquote\',\'width=800,height=600,toolbar=no,status=no,scrollbars=yes,menubar=no,location=no,direction=no\'); void(0);");
                }
        }
 }

 document.onkeyup = function(e) {
	if (document.getElementById(\'s3_logo\')){
		$(\'#s3_logo\').attr(\'href\', \'javascript: void(0);\');
	}
 }

});
'; ?>

//]]>
</script>
<?php endif; ?>

<CENTER>
<TABLE border="0" cellpadding="0" cellspacing="0"  width="960">

<TR>
<TD height="24" valign="middle">
        <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" style="background-color: #0072BB;">
        <tr>
        <td width="350" nowrap="nowrap" valign="middle" align="left">&nbsp;
<font style="color: #ffffff;">
<?php if ($this->_tpl_vars['geo_litecity_location']['phone'] != ""): ?>
Place order online or call <?php echo $this->_tpl_vars['geo_litecity_location']['phone']; ?>

<?php else: ?>
<?php echo $this->_tpl_vars['config']['Company']['cidev_top_header_code']; ?>

<?php endif; ?>
</font>
	</td>
        <td width="*" align="right" valign="middle" >
<?php if (! ( ( $GLOBALS['_GET']['mode'] == 'checkout' ) || ( $GLOBALS['_GET']['mode'] == 'update' && $GLOBALS['_GET']['action'] == 'cart' ) )): ?>
<table border="0" cellpadding="1" cellspacing="0">
<tr>
<?php if ($this->_tpl_vars['top_pages_menu'] != ""): ?>
<?php unset($this->_sections['top_page']);
$this->_sections['top_page']['name'] = 'top_page';
$this->_sections['top_page']['loop'] = is_array($_loop=$this->_tpl_vars['top_pages_menu']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['top_page']['show'] = true;
$this->_sections['top_page']['max'] = $this->_sections['top_page']['loop'];
$this->_sections['top_page']['step'] = 1;
$this->_sections['top_page']['start'] = $this->_sections['top_page']['step'] > 0 ? 0 : $this->_sections['top_page']['loop']-1;
if ($this->_sections['top_page']['show']) {
    $this->_sections['top_page']['total'] = $this->_sections['top_page']['loop'];
    if ($this->_sections['top_page']['total'] == 0)
        $this->_sections['top_page']['show'] = false;
} else
    $this->_sections['top_page']['total'] = 0;
if ($this->_sections['top_page']['show']):

            for ($this->_sections['top_page']['index'] = $this->_sections['top_page']['start'], $this->_sections['top_page']['iteration'] = 1;
                 $this->_sections['top_page']['iteration'] <= $this->_sections['top_page']['total'];
                 $this->_sections['top_page']['index'] += $this->_sections['top_page']['step'], $this->_sections['top_page']['iteration']++):
$this->_sections['top_page']['rownum'] = $this->_sections['top_page']['iteration'];
$this->_sections['top_page']['index_prev'] = $this->_sections['top_page']['index'] - $this->_sections['top_page']['step'];
$this->_sections['top_page']['index_next'] = $this->_sections['top_page']['index'] + $this->_sections['top_page']['step'];
$this->_sections['top_page']['first']      = ($this->_sections['top_page']['iteration'] == 1);
$this->_sections['top_page']['last']       = ($this->_sections['top_page']['iteration'] == $this->_sections['top_page']['total']);
?>
<?php if ($this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['image']['filename'] != ""): ?>
<td valign="middle"><?php if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['pageid']): ?><a href="/pages.php?pageid=<?php echo $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['pageid']; ?>
"><?php endif; ?><img src="<?php if ($this->_tpl_vars['HTTPS_url'] == 'N' && $this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['xcart_web_dir'];  endif; ?>/image.php?id=<?php echo $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['image']['id']; ?>
&amp;type=A" alt="" <?php if ($this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['image']['image_x'] > '16'): ?>width="16"<?php endif; ?> /><?php if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['pageid']): ?></a><?php endif; ?></td>
<?php endif; ?>
<td valign="middle" nowrap="nowrap"><?php if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['pageid']): ?><a class="top_links" href="/pages.php?pageid=<?php echo $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['pageid']; ?>
"><?php else: ?><font style="color: #cccccc;"><?php endif;  echo $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['title'];  if ($GLOBALS['_GET']['pageid'] != $this->_tpl_vars['top_pages_menu'][$this->_sections['top_page']['index']]['pageid']): ?></a><?php else: ?></font><?php endif; ?></td>
<td width="15" valign="middle" align="center"><font style="color: #ffffff;">|</font></td>
<?php endfor; endif; ?>
<?php endif; ?>

<td nowrap="nowrap" valign="middle"><?php if ($this->_tpl_vars['main'] != 'help' && $GLOBALS['_GET']['section'] != 'contactus'): ?><a class="top_links" href="/help.php?section=contactus&mode=update"><?php else: ?><font style="color: #cccccc;"><?php endif; ?>Contact Us<?php if ($this->_tpl_vars['main'] != 'help' && $GLOBALS['_GET']['section'] != 'contactus'): ?></a><?php else: ?></font><?php endif; ?></td>

<td valign="middle" nowrap="nowrap"><?php echo $this->_tpl_vars['lng']['lbl_top_header_nbsp']; ?>
</td>
</tr>
</table>
<?php endif; ?>
        </td>
        </tr>
        </table>
</TD>
</TR>

<TR>
<TD  valign="bottom">
        <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0">
        <tr>

        <td width="250"  valign="middle">
        <?php if (! ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" ) || $GLOBALS['_GET']['page'] != ""): ?><a href="/"><?php endif; ?><img src="<?php if ($this->_tpl_vars['HTTPS_url'] == 'N' && $this->_tpl_vars['config']['Appearance']['CDN_domain'] != "" && $this->_tpl_vars['config']['Appearance']['Enable_CDN'] == 'Y'):  echo $this->_tpl_vars['config']['Appearance']['CDN_domain'];  else:  echo $this->_tpl_vars['xcart_web_dir'];  endif; ?>/image.php?id=<?php echo $this->_tpl_vars['current_storefront_info']['storefrontid']; ?>
&amp;type=S" <?php if ($this->_tpl_vars['current_storefront_info']['image']['image_x'] > '250'): ?> width="250" <?php endif; ?>  alt="Home page" ><?php if (! ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" )): ?></a><?php endif; ?>
        </td>

<?php if ($this->_tpl_vars['main'] == 'fast_lane_checkout' || $this->_tpl_vars['main'] == 'order_message'): ?>
        <td width="*" valign="middle" align="center">
	        <a href="javascript: void(0);" style="cursor: default;" id="s3_logo"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/S3-Stores-Logo-S2.png" alt="" /></a>
        </td>
        <td width="150" valign="middle" align="right">
<?php if ($this->_tpl_vars['config']['Security']['ssl_seal'] != ""): ?>
<?php echo $this->_tpl_vars['config']['Security']['ssl_seal']; ?>

<?php endif; ?>
        </td>
<?php else: ?>
        <td width="*" valign="middle">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" >
                        <tr>
			<td width="10">&nbsp;</td>
                        <td valign="middle" width="*" align="center">
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                        </td>
                        <td width="10">&nbsp;</td>
                        <td width="204" valign="middle" align="right">
                        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/menu_cart.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['variant_id_for_point5'] != "" && $this->_tpl_vars['variant_id_for_point5'] == '0' && $this->_tpl_vars['main'] != 'product' && ! ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" )): ?>
<?php $this->assign('social_buttons_data_services', $this->_tpl_vars['config']['Appearance']['social_buttons_data_services']); ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['config']['Appearance']['social_buttons_script_code'])) ? $this->_run_mod_handler('replace', true, $_tmp, "[data-services]", ($this->_tpl_vars['social_buttons_data_services'])) : smarty_modifier_replace($_tmp, "[data-services]", ($this->_tpl_vars['social_buttons_data_services']))))) ? $this->_run_mod_handler('replace', true, $_tmp, "[size]", 'medium') : smarty_modifier_replace($_tmp, "[size]", 'medium')); ?>

<?php endif; ?>

                        </td>
                        </tr>
                </table>
        </td>
<?php endif; ?>
        </tr>
        </table>
</TD>
</TR>

<?php if ($this->_tpl_vars['main'] == 'order_message'): ?>
<tr><td>&nbsp;</td></tr>
<tr><td class="cidev_checkout_bar6"></td></tr>
<?php endif; ?>

</TABLE>
</CENTER>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "customer/top_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['variant_id_for_point3'] == '1'): ?>
<?php echo $this->_tpl_vars['config']['Storefront_common_details']['common_header_code']; ?>

<?php endif; ?>

<a id="scrollTop" class="button_new grey" href="#" title="Up">Up</a>

<script type="text/javascript">
//<![CDATA[
<?php echo '

function scrollTop(){
    if(jQuery(window).scrollTop() > jQuery(\'#header\').height()){
        jQuery(\'#scrollTop\').fadeIn(\'slow\');
    } else {
        jQuery(\'#scrollTop\').fadeOut(\'fast\');
    }
}
jQuery(\'#scrollTop\').live(\'click\', function(){
    jQuery(\'body,html\').animate({
        scrollTop: 0
    }, 300);
  return false;
});
jQuery(window).scroll(function(){
    scrollTop();
});

'; ?>

//]]>
</script>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "head.tpl"), $this); endif; ?>