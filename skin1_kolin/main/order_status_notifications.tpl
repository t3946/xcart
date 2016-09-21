{*
$Id: order_status_notifications.tpl, v 1.0.0 2011/10/18 12:44:21 kate Exp $
vim: set ts=2 sw=2 sts=2 et:
*}
{include file="page_title.tpl" title=$lng.lbl_order_status_notifications}
<script src="{$SkinDir}/tinymce/js/tinymce/tinymce.min.js" type="text/javascript"></script>
<script type="text/javascript">
    //<![CDATA[
    {literal}
    function initMCEexact() {
        tinymce.remove();
        tinymce.init({
            mode: "textareas",
            resize: "both",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste fullpage"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
            forced_root_block: false,
            force_br_newlines: true,
            force_p_newlines: false,
            convert_urls: false,
            relative_urls: false,
            apply_source_formatting : true
        });
    }
    initMCEexact();
    {/literal}
    //]]>
</script>

<script type="text/javascript">
<!--
{literal}

function show_settings() {
  var status = $('select[name="status"] option:selected').val();
  $.post('ajax_change_status.php', 'status=' + status, function (data) {
    $('.VariableSettings').remove();
    $('#osn_status').after(data);
    initMCEexact();
  }, 'text');
}

$('body').on('change','.plane_checkbox',function(){
    if ($(this).attr('checked')) {
        tinymce.remove();
    } else {
        initMCEexact();
    }
});

{/literal}
-->
</script>

{include file="modules/HTML_Editor/editor.tpl"}

{capture name=dialog}

{if $statuses}

{$lng.lbl_order_status_replace_vars}

<form name="osnotificform" action="order_status_notifications.php" method="post">
<input type="hidden" name="mode" value="update" />

<table cellpadding="1" cellspacing="5" width="100%">

<tr id="osn_status">
  <td><B>{$lng.lbl_when_order_status_changes_to}</B></td>
  <td width="70%">
    <select name="status" onchange="javascript: show_settings();">
    {foreach from=$statuses item=group key=type}
      {if $type ne 'BD' && $type ne 'CA'}
        <optgroup label="{$status_types[$type]}">
          {foreach from=$group item=order_status key="code"}
{* {if $code ne "K" && $code ne "L" && $code ne "M" && $code ne "V"} *}
{if $code ne "K" && $code ne "L" && $code ne "M"}
            <option value="{$code}"{if $status eq $code} selected="selected"{/if}>{$order_status}</option>
{/if}
          {/foreach}
        </optgroup>
      {/if}
    {/foreach}

{*
    {foreach from=$statuses item=group key=type}
      {if $type eq 'CA'}
        <optgroup label="{$status_types[$type]}">
          {foreach from=$group item=order_status key="code"}
            <option value="{$code}"{if $status eq $code} selected="selected"{/if}>{$order_status}</option>
          {/foreach}
        </optgroup>
      {/if}
    {/foreach}
*}
    </select>
    {$lng.lbl_send_email_to_customer|cat:":"}
  </td>
</tr>

{include file="main/osn_settings.tpl"}

<tr>
  <td>&nbsp;</td>
  <td>

{*
  <input name="save" type="button" value="save" onclick="javascript: tinyMCE.triggerSave(); document.osnotificform.submit();" /><br /><br />
*}

{*
  <input name="save" type="button" value="save" onclick="javascript: document.osnotificform.submit();" /><br /><br />
*}


<input type="submit" value="{$lng.lbl_save|strip_tags:false|escape}" />


  </td>
</tr>
</table>

{/if}

{/capture}
{include file="dialog.tpl" title=$lng.lbl_order_status_notifications content=$smarty.capture.dialog extra='width="100%"'}
