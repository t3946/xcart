<?php /* Smarty version 2.6.12, created on 2015-11-02 03:05:05
         compiled from dialog_message.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xevent', 'dialog_message.tpl', 1, false),array('modifier', 'lower', 'dialog_message.tpl', 53, false),array('modifier', 'escape', 'dialog_message.tpl', 68, false),array('modifier', 'default', 'dialog_message.tpl', 74, false),)), $this); ?>
<?php func_load_lang($this, "dialog_message.tpl","lbl_error,lbl_warning,lbl_close,lbl_go_to_last_edit_section"); ?><?php if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'before','tpl' => "dialog_message.tpl"), $this); endif;  if (( $this->_tpl_vars['top_message']['content'] != "" || $this->_tpl_vars['alt_content'] != "" ) && $this->_tpl_vars['usertype'] == 'C'): ?>

<script type="text/javascript">
//<![CDATA[

<?php echo '
if (!$.browser.msie)
        var trans = "fade";
else
        var trans = "none";

$(document).ready(function(){
  var msgOpts = {
    transition: trans, // Can be set to "elastic", "fade", or "none".
    speed: 200,
    href: "#system_message",
    title: false,
    rel: false,
    width: "24%",
    height: false,
    innerWidth: false,
    innerHeight: false,
    initialWidth: 100,
    initialHeight: 100,
    maxWidth: "90%",
    maxHeight: "90%",
    scalePhotos: true,
    scrolling: true,
    iframe: false,
    inline: true,
    html: false,
    photo: false,
    opacity: 0.3,
    open: true,
        returnFocus: true,
    preloading: true,
    overlayClose: true,
    previous: "Previous",
    next: "Next",
    close: "Close",
    onOpen: false,
    onLoad: false,
    onComplete: false,
    onCleanup: false,
    onClosed: false
  };
  $("").colorbox(msgOpts);
});
'; ?>

//]]>
</script>

<?php if (((is_array($_tmp=$this->_tpl_vars['top_message']['title'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'error' || ((is_array($_tmp=$this->_tpl_vars['top_message']['type'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'e'): ?>
        <?php $this->assign('color', "color: #c02c05;"); ?>
        <?php $this->assign('new_title', 'Error');  elseif (((is_array($_tmp=$this->_tpl_vars['top_message']['title'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'warning' || ((is_array($_tmp=$this->_tpl_vars['top_message']['type'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'w'): ?>
        <?php $this->assign('color', "color: #f6a520;"); ?>
        <?php $this->assign('new_title', 'Warning');  elseif (((is_array($_tmp=$this->_tpl_vars['top_message']['title'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'information' || ((is_array($_tmp=$this->_tpl_vars['top_message']['type'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'i'): ?>
        <?php $this->assign('color', "color: #729a37;"); ?>
        <?php $this->assign('new_title', 'Information');  endif; ?>

  <div style="display:none;">
    <div id="system_message">
        <div id="popup_msgs">

                <div class="title <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['new_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)))) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
">
                        <span style="<?php echo $this->_tpl_vars['color']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['new_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
                </div>

                <hr />

                <div style="font-size: 14px;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['top_message']['content'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['alt_content']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['alt_content'])); ?>
</div>

                <hr />
        </div>
    </div>
  </div>


<?php else: ?>


<?php if ($this->_tpl_vars['top_message']['content'] != "" || $this->_tpl_vars['alt_content'] != ""):  if ($this->_tpl_vars['top_message']['type'] == 'E'):  $this->assign('log_icon', "log_type_Error.gif");  $this->assign('log_title', $this->_tpl_vars['lng']['lbl_error']);  elseif ($this->_tpl_vars['top_message']['type'] == 'W'):  $this->assign('log_icon', "log_type_Warning.gif");  $this->assign('log_title', $this->_tpl_vars['lng']['lbl_warning']);  else:  $this->assign('log_icon', "log_type_Information.gif");  $this->assign('log_title', 'Notification');  endif;  if ($this->_tpl_vars['alt_content'] != ""):  $this->assign('log_icon', "log_type_Warning.gif");  $this->assign('log_title', $this->_tpl_vars['title']);  endif; ?>
<div align="center" id="dialog_message">
<table cellspacing="0" class="DialogInfo">
<tr>
<td class="DialogInfoTitleBorder">
<table width="100%" cellspacing="2">
<tr> 
	<td class="DialogInfoTitle" width="16"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/<?php echo $this->_tpl_vars['log_icon']; ?>
" class="DialogInfoIcon" alt="" /></td>
	<td width="100%" class="DialogInfoTitle" align="left"><?php echo $this->_tpl_vars['log_title']; ?>
</td>
<?php if ($this->_tpl_vars['top_message']['no_close'] == ""): ?>
	<td align="right" class="DialogInfoTitle"><a href="javascript: void(0);" onclick="javascript: document.getElementById('dialog_message').style.display = 'none';"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/close.gif" class="DialogInfoClose" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['lng']['lbl_close'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></a></td>
<?php endif; ?>
</tr>
</table></td>
</tr>
<tr>
<td class="DialogInfoBorder">
<table cellspacing="1" width="100%">
<tr> 
<td valign="top" class="DialogBox"><?php if ($this->_tpl_vars['alt_content'] != ""):  echo $this->_tpl_vars['alt_content'];  else:  echo $this->_tpl_vars['top_message']['content'];  endif;  if ($this->_tpl_vars['top_message']['anchor'] != ""): ?>
<br /><br />
<div align="right">
<table cellspacing="0" cellpadding="0">
<tr>
	<td><a href="#<?php echo $this->_tpl_vars['top_message']['anchor']; ?>
"><?php echo $this->_tpl_vars['lng']['lbl_go_to_last_edit_section']; ?>
</a></td>
	<td><a href="#<?php echo $this->_tpl_vars['top_message']['anchor']; ?>
"><img src="<?php echo $this->_tpl_vars['ImagesDir']; ?>
/goto_arr.gif" width="12" height="10" alt="" /></a></td>
</tr>
</table>
</div><?php endif; ?>
</td>
</tr>
</table></td>
</tr></table>
<br />
</div>
<?php endif; ?>

<?php endif;  if ($this->_tpl_vars['x_core_started']):  echo x_tpl_fire_event(array('name' => 'after','tpl' => "dialog_message.tpl"), $this); endif; ?>