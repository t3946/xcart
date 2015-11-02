<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from meta.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'meta.tpl', 1, false),array('modifier', 'default', 'meta.tpl', 2, false),array('modifier', 'truncate', 'meta.tpl', 82, false),array('modifier', 'escape', 'meta.tpl', 82, false),array('modifier', 'strip', 'meta.tpl', 82, false),)), $this); ?>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "meta.tpl"), $this); endif; ?><meta http-equiv="Content-Type" content="text/html; charset=<?php echo ((is_array($_tmp=@$this->_tpl_vars['default_charset'])) ? $this->_run_mod_handler('default', true, $_tmp, "iso-8859-1") : smarty_modifier_default($_tmp, "iso-8859-1")); ?>
" />

<!-- Google verification META tags -->
<meta name="google-site-verification" content="PK6Exg58lxvKvOxDTtMymHgTCmUipFuJS9O9ZrYYiVg" />
<meta name="google-site-verification" content="6k-TabU_BDiTSvqSlFcEi8vkUrUObseKUFaOWlJJ1E4" />
<meta name="google-site-verification" content="MYF20ERG7ywK7wPxsqofeXlj-sDmTMYUUsbhTC6NYKo" />
<meta name="google-site-verification" content="XyLJfEHvSqDZ9w6AoydM87Zy-1FWNhqOewD9jzJaKjI" />
<meta name="google-site-verification" content="tj9nRSIwEyGYhijxN1_IoXNVDQ_PNzmdi6-vgAW9GXQ" />
<meta name="google-site-verification" content="EKTx1KNnsWDhqFHJAIvxYPbtW3N16DVQLIcHy6gkAOw" />
<meta name="google-site-verification" content="h2qdwaSe3hT0TwJm717fc_5U5StP_sGhE1JP2xgm8UA" />

<!-- Google verification META tags -->

<!-- bench time -->
<meta name="<?php echo $this->_tpl_vars['bench_name']; ?>
" content="<?php echo $this->_tpl_vars['bench_time']; ?>
" />

<!-- vewport test -->
<meta name="viewport" content="width=device-width, initial-scale=2">

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "presets_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (( ( $this->_tpl_vars['main'] == 'product' || $this->_tpl_vars['main'] == 'fast_lane_checkout' ) || $this->_tpl_vars['usertype'] != 'C' )):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main/include_js.tpl", 'smarty_include_vars' => array('src' => "common.js")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 
<?php endif; ?>

<?php if ($this->_tpl_vars['config']['Adaptives']['isJS'] == '' && $this->_tpl_vars['config']['Adaptives']['is_first_start'] == 'Y'): ?>
<script type="text/javascript">
<!--
var usertype = "<?php echo $this->_tpl_vars['usertype']; ?>
";
-->
</script>
<script id="adaptives_script" type="text/javascript" language="JavaScript 1.2"></script>


<?php endif;  if ($this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A'): ?>
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
<?php else:  $this->assign('_meta_descr', "");  $this->assign('_meta_keywords', "");  if ($this->_tpl_vars['product']['meta_descr'] != "" && $this->_tpl_vars['config']['SEO']['include_meta_products'] == 'Y'):  $this->assign('_meta_descr', ($this->_tpl_vars['product']['meta_descr']));  $this->assign('_meta_keywords', ($this->_tpl_vars['product']['meta_keywords']));  endif;  if ($this->_tpl_vars['current_category']['meta_descr'] != "" && $this->_tpl_vars['config']['SEO']['include_meta_categories'] == 'Y' && ! $this->_tpl_vars['product']['productid']):  $this->assign('_meta_descr', ($this->_tpl_vars['_meta_descr']).($this->_tpl_vars['current_category']['meta_descr']));  $this->assign('_meta_keywords', ($this->_tpl_vars['_meta_keywords']).($this->_tpl_vars['current_category']['meta_keywords']));  endif;  if ($this->_tpl_vars['brand']['meta_descr'] != "" && $this->_tpl_vars['config']['Brands']['include_meta_brands'] == 'Y'):  $this->assign('_meta_descr', ($this->_tpl_vars['_meta_descr']).($this->_tpl_vars['brand']['meta_descr']));  $this->assign('_meta_keywords', ($this->_tpl_vars['_meta_keywords']).($this->_tpl_vars['brand']['meta_keywords']));  endif;  if ($this->_tpl_vars['_meta_descr'] == ''):  $this->assign('_meta_descr', ' ');  endif;  if ($this->_tpl_vars['_meta_keywords'] == ''):  $this->assign('_meta_keywords', ($this->_tpl_vars['_meta_keywords']).($this->_tpl_vars['brand']['meta_keywords'])." ");  endif;  $this->assign('_meta_descr', ($this->_tpl_vars['_meta_descr']).($this->_tpl_vars['config']['SEO']['meta_descr']));  $this->assign('_meta_keywords', ($this->_tpl_vars['_meta_keywords']).($this->_tpl_vars['config']['SEO']['meta_keywords'])); ?>

<?php if ($this->_tpl_vars['config']['Company']['cidev_keywords'] != "" && ( ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" ) || ( $this->_tpl_vars['_meta_keywords'] == "" ) )):  $this->assign('_meta_keywords', $this->_tpl_vars['config']['Company']['cidev_keywords']);  endif; ?>

<?php if ($this->_tpl_vars['config']['Company']['cidev_description'] != "" && ( ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" ) || ( $this->_tpl_vars['_meta_descr'] == "" ) )):  $this->assign('_meta_descr', $this->_tpl_vars['config']['Company']['cidev_description']);  endif; ?>

<?php if ($this->_tpl_vars['main'] == 'product'): ?>

	<?php if ($this->_tpl_vars['product']['map_price'] > $this->_tpl_vars['product']['taxed_price']): ?>
		<?php $this->assign('meta_current_price', $this->_tpl_vars['product']['map_price']); ?>
	<?php else: ?>
		<?php $this->assign('meta_current_price', $this->_tpl_vars['product']['taxed_price']); ?>
	<?php endif; ?>

        <?php if ($this->_tpl_vars['product']['seo_meta_descr'] != ""): ?>
                <meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['product']['seo_meta_descr'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '500', "...", false) : smarty_modifier_truncate($_tmp, '500', "...", false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
        <?php else: ?>
                <meta name="description" content="Buy online or call <?php echo $this->_tpl_vars['config']['Company']['company_phone']; ?>
. <?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_meta_descr'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '500', "...", false) : smarty_modifier_truncate($_tmp, '500', "...", false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
        <?php endif; ?>

<?php elseif ($this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] != ""): ?>

	<?php if ($this->_tpl_vars['current_category']['meta_descr_orig'] != ""): ?>
		<meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['current_category']['meta_descr_orig'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
	<?php elseif ($this->_tpl_vars['current_category']['description'] != ""): ?>
                <meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['current_category']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
        <?php else: ?>
                <meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_meta_descr'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '500', "...", false) : smarty_modifier_truncate($_tmp, '500', "...", false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
        <?php endif; ?>

	<?php if ($this->_tpl_vars['current_category']['meta_keywords_orig'] != ""): ?>
		<meta name="keywords" content="<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['current_category']['meta_keywords_orig'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '500', "", false) : smarty_modifier_truncate($_tmp, '500', "", false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
	<?php endif;  else: ?>
		<meta name="description" content="<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_meta_descr'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '500', "...", false) : smarty_modifier_truncate($_tmp, '500', "...", false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
<?php endif; ?>

 <?php if ($this->_tpl_vars['config']['Appearance']['config_keywords_meta_tag'] != "" && ( ( $this->_tpl_vars['main'] == 'catalog' && $this->_tpl_vars['current_category']['category'] == "" ) )): ?>
  <?php $this->assign('_meta_keywords', $this->_tpl_vars['config']['Appearance']['config_keywords_meta_tag']); ?>
  <meta name="keywords" content="<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_meta_keywords'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '500', "", false) : smarty_modifier_truncate($_tmp, '500', "", false)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('strip', true, $_tmp) : smarty_modifier_strip($_tmp)); ?>
" />
 <?php else: ?>

  <?php endif;  endif;  if ($this->_tpl_vars['webmaster_mode'] == 'editor'): ?>
<script type="text/javascript" language="JavaScript 1.2">
<!--
var store_language = "<?php if (( $this->_tpl_vars['usertype'] == 'P' || $this->_tpl_vars['usertype'] == 'A' ) && $this->_tpl_vars['current_language'] != ""):  echo $this->_tpl_vars['current_language'];  else:  echo $this->_tpl_vars['store_language'];  endif; ?>";
var catalogs = new Object();
catalogs.admin = "<?php echo $this->_tpl_vars['catalogs']['admin']; ?>
";
catalogs.provider = "<?php echo $this->_tpl_vars['catalogs']['provider']; ?>
";
catalogs.customer = "<?php echo $this->_tpl_vars['catalogs']['customer']; ?>
";
catalogs.partner = "<?php echo $this->_tpl_vars['catalogs']['partner']; ?>
";
catalogs.images = "<?php echo $this->_tpl_vars['ImagesDir']; ?>
";
catalogs.skin = "<?php echo $this->_tpl_vars['SkinDir']; ?>
";
var lng_labels = [];
<?php $_from = $this->_tpl_vars['webmaster_lng']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lbl_name'] => $this->_tpl_vars['lbl_val']):
?>
lng_labels['<?php echo $this->_tpl_vars['lbl_name']; ?>
'] = '<?php echo $this->_tpl_vars['lbl_val']; ?>
';
<?php endforeach; endif; unset($_from); ?>
var page_charset = "<?php echo ((is_array($_tmp=@$this->_tpl_vars['default_charset'])) ? $this->_run_mod_handler('default', true, $_tmp, "iso-8859-1") : smarty_modifier_default($_tmp, "iso-8859-1")); ?>
";
-->
</script>
<script type="text/javascript" language="JavaScript 1.2" src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/editor_common.js"></script>
<?php if ($this->_tpl_vars['user_agent'] == 'ns'): ?>
<script type="text/javascript" language="JavaScript 1.2" src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/editorns.js"></script>
<?php else: ?>
<script type="text/javascript" language="JavaScript 1.2" src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/editor.js"></script>
<?php endif;  endif; ?>

 <script src="<?php echo $this->_tpl_vars['SkinDir']; ?>
/jquery.min.1.7.1.js" type="text/javascript"></script>
<?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "meta.tpl"), $this); endif; ?>