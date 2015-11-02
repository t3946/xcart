{if ($top_message.content ne "" or $alt_content ne "") && $usertype eq "C"}

<script type="text/javascript">
//<![CDATA[

{literal}
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
{/literal}
//]]>
</script>

{if $top_message.title|lower eq "error" or $top_message.type|lower eq "e"}
        {assign var="color" value="color: #c02c05;"}
        {assign var="new_title" value="Error"}
{elseif $top_message.title|lower eq "warning" or $top_message.type|lower eq "w"}
        {assign var="color" value="color: #f6a520;"}
        {assign var="new_title" value="Warning"}
{elseif $top_message.title|lower eq "information" or $top_message.type|lower eq "i"}
        {assign var="color" value="color: #729a37;"}
        {assign var="new_title" value="Information"}
{/if}

  <div style="display:none;">
    <div id="system_message">
        <div id="popup_msgs">

                <div class="title {$new_title|escape|lower}">
                        <span style="{$color}">{$new_title|escape}</span>
                </div>

                <hr />

                <div style="font-size: 14px;">{$top_message.content|default:$alt_content}</div>

                <hr />
        </div>
    </div>
  </div>


{else}


{if $top_message.content ne "" or $alt_content ne ""}
{if $top_message.type eq "E"}
{assign var="log_icon" value="log_type_Error.gif"}
{assign var="log_title" value=$lng.lbl_error}
{elseif $top_message.type eq "W"}
{assign var="log_icon" value="log_type_Warning.gif"}
{assign var="log_title" value=$lng.lbl_warning}
{else}
{assign var="log_icon" value="log_type_Information.gif"}
{assign var="log_title" value=$lng.lbl_information}
{/if}
{if $alt_content ne ""}
{assign var="log_icon" value="log_type_Warning.gif"}
{assign var="log_title" value=$title}
{/if}
<div align="center" id="dialog_message">
<table cellspacing="0" class="DialogInfo">
<tr>
<td class="DialogInfoTitleBorder">
<table width="100%" cellspacing="2">
<tr> 
	<td class="DialogInfoTitle" width="16"><img src="{$ImagesDir}/{$log_icon}" class="DialogInfoIcon" alt="" /></td>
	<td width="100%" class="DialogInfoTitle" align="left">{$log_title}</td>
{if $top_message.no_close eq ""}
	<td align="right" class="DialogInfoTitle"><a href="javascript: void(0);" onclick="javascript: document.getElementById('dialog_message').style.display = 'none';"><img src="{$ImagesDir}/close.gif" class="DialogInfoClose" alt="{$lng.lbl_close|escape}" /></a></td>
{/if}
</tr>
</table></td>
</tr>
<tr>
<td class="DialogInfoBorder">
<table cellspacing="1" width="100%">
<tr> 
<td valign="top" class="DialogBox">{if $alt_content ne ""}{$alt_content}{else}{$top_message.content}{/if}
{if $top_message.anchor ne ""}
<br /><br />
<div align="right">
<table cellspacing="0" cellpadding="0">
<tr>
	<td><a href="#{$top_message.anchor}">{$lng.lbl_go_to_last_edit_section}</a></td>
	<td><a href="#{$top_message.anchor}"><img src="{$ImagesDir}/goto_arr.gif" width="12" height="10" alt="" /></a></td>
</tr>
</table>
</div>{/if}
</td>
</tr>
</table></td>
</tr></table>
<br />
</div>
{/if}

{/if}
