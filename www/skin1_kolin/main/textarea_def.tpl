{* $Id: textarea.tpl,v 1.5.2.3 2006/11/14 08:06:41 max Exp $ *}
{if $active_modules.HTML_Editor && !$disabled}

{include file="main/start_textarea.tpl" _include_once=1}
{assign var="id" value=$name|regex_replace:"/[^\w\d_]/":""}

<div align="right" style="width: 96%;">
<a href="javascript:void(0);" style="display: none;" id="{$id}Dis" onclick="javascript: disableEditor('{$id}','{$name}');">{$lng.lbl_default_editor}</a>
<b id="{$id}DisB">{$lng.lbl_default_editor}</b>
&nbsp;&nbsp;
<a href="javascript:void(0);" id="{$id}Enb" onclick="javascript: enableEditor('{$id}','{$name}');">{$lng.lbl_advanced_editor}</a>
<b id="{$id}EnbB" style="display: none;">{$lng.lbl_advanced_editor}</b>
</div>

<textarea id="{$id}" name="{$name}"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if} style="width: 99%;">{$data|escape:"html"}</textarea>
<div id="{$id}Box" style="width: 100%; display: none;">
<textarea id="{$id}Adv"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if} style="width: 100%;{if $no_links eq 'Y'}display:none;{/if}">{$data|escape:"html"}</textarea>

{include file="modules/HTML_Editor/editors/tinymce/textarea.tpl" id=$id name=$name data=$data}

</div>

<script type="text/javascript">
//<![CDATA[
var isOpen = getCookie('{$id}EditorEnabled');
if (isOpen && isOpen == 'Y')
  $('#{$id}Enb').click();
//]]>
</script>

{else}
<textarea id="{$id}" name="{$name}"{if $cols} cols="{$cols}"{/if}{if $rows} rows="{$rows}"{/if}{if $class} class="{$class}"{/if}{if $style} style="{$style}"{/if}{if $disabled} disabled="disabled"{/if}>{$data|escape:"html"}</textarea>
{/if}
